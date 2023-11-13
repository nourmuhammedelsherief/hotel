<?php

namespace App\Http\Controllers\Api\AdminController;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\HistoryCollection;
use App\Http\Resources\Hotel\SubscriptionCollection;
use App\Models\History;
use App\Models\MarketerOperation;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\FlareClient\Api;
use Validator;

class OperationController extends Controller
{
    public function hotel_bank_transfers()
    {
        $transfers = Subscription::whereType('hotel')
            ->where('transfer_photo' , '!=' , null)
            ->where('bank_id' , '!=' , null)
            ->where('payment_type' , 'bank')
            ->whereIn('status' , ['tentative' , 'finished','tentative_finished'])
            ->paginate();
        return ApiController::respondWithSuccess(new SubscriptionCollection($transfers));
    }
    public function confirm_hotel_bank_transfers(Request $request)
    {
        $rules = [
            'subscription_id' => 'required|exists:subscriptions,id',
            'status'          => 'required|in:confirm,cancel',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $subscription = Subscription::find($request->subscription_id);
        if ($request->status == 'confirm')
        {
            $end_at = Carbon::now()->addMonths($subscription->package->duration);
            History::create([
                'hotel_id' => $subscription->hotel->id,
                'package_id' => $subscription->package->id,
                'branch_id' => $subscription->branch_id,
                'payment_type' => 'bank',
                'details' =>   ($subscription->status == 'tentative' or $subscription->status == 'tentative_finished') ? trans('messages.hotel_new_subscribe') : trans('messages.hotel_renew_subscribe'),
                'bank_id' => $subscription->bank_id,
                'price'   => $subscription->hotel->country->rial_price,
                'paid_amount' => $subscription->amount,
                'discount_value' => $subscription->discount_value,
                'tax_value'      => $subscription->tax_value,
                'operation_date' => Carbon::now(),
                'transfer_photo' => $subscription->transfer_photo,
                'status'     => ($subscription->status == 'tentative' or $subscription->status == 'tentative_finished') ? 'new' : 'renew',
                'accepted_admin_id'   => $request->user()->id,
                'accepted_admin_name' => $request->user()->name,
            ]);
            $subscription->update([
                'status' => 'active',
                'end_at' => $end_at,
                'paid_at' => Carbon::now(),
                'is_payment' => 'true',
                'payment_type' => 'bank',
                'subscription_type' => ($subscription->status == 'tentative' or $subscription->status == 'tentative_finished') ? 'subscription' : 'renew',
            ]);
            if ($subscription->type == 'hotel') {
                $subscription->hotel->update([
                    'status' => 'active',
                    'admin_activation' => 'true',
                ]);
                // update the main branch
                $subscription->branch->update([
                    'status' => 'active',
                ]);
                $operation = MarketerOperation::whereSubscriptionId($subscription->id)
                    ->whereStatus('not_done')
                    ->first();
                if ($operation) {
                    $operation->update([
                        'status' => 'done',
                    ]);
                    $balance = $operation->marketer->balance + $operation->amount;
                    $operation->marketer->update([
                        'balance' => $balance
                    ]);
                    $subscription->update(['seller_code_id' => $operation->seller_code_id]);
                }

            }
            $success = [
                'message'  => trans('messages.paymentDoneSuccessfully'),
            ];
            return ApiController::respondWithSuccess($success);
        }elseif ($request->status == 'cancel')
        {
            $subscription->update([
                'is_payment' => 'false',
                'bank_id'    => null,
                'transfer_photo' => null,
            ]);
            $success = [
                'message'  => trans('messages.paymentCanceledSuccessfully'),
            ];
            return ApiController::respondWithSuccess($success);
        }
    }

    public function history($month = null , $year = null)
    {
        $year = $year == null ? Carbon::now()->format('Y') : $year;
        $month = $month == null ? Carbon::now()->format('m') : $month;
        $histories = History::whereyear('created_at','=',$year)
            ->whereMonth('created_at','=',$month)
            ->orderBy('id' , 'desc')
            ->paginate();
        $month_total_amount = History::whereyear('created_at','=',$year)
            ->whereMonth('created_at','=',$month)
            ->sum('paid_amount');
        $tax_values = History::whereyear('created_at','=',$year)
            ->whereMonth('created_at','=',$month)
            ->sum('tax_value');
        $subscribed_hotels = History::whereStatus('new')
            ->whereyear('created_at','=',$year)
            ->whereMonth('created_at','=',$month)
            ->count();
        $renewed_hotels = History::whereStatus('renew')
            ->whereyear('created_at','=',$year)
            ->whereMonth('created_at','=',$month)
            ->count();
        $success = [
            'month_total_amount' => doubleval(number_format((float)$month_total_amount, 2, '.', '')),
            'month_total_taxes'  => doubleval(number_format((float)$tax_values, 2, '.', '')),
            'month_subscribed_hotels' => doubleval(number_format((float)$subscribed_hotels, 2, '.', '')),
            'month_renewed_hotels' => doubleval(number_format((float)$renewed_hotels, 2, '.', '')),
            'month_histories'   => new HistoryCollection($histories),
        ];
        return ApiController::respondWithSuccess($success);
    }

    public function delete_history($id)
    {
        $history = History::find($id);
        if ($history)
        {
            if ($history->transfer_photo != null)
            {
                @unlink(public_path('/uploads/transfers/' . $history->transfer_photo));
            }
            $history->delete();
            $success = [
                'message' => trans('messages.deleted')
            ];
            return ApiController::respondWithSuccess($success);
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
}
