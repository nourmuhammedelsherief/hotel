<?php

namespace App\Http\Controllers\Api\HotelController;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Models\History;
use App\Models\MarketerOperation;
use App\Models\Package;
use App\Models\SellerCode;
use App\Models\Setting;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\FlareClient\Api;
use Validator;

class SubscriptionController extends Controller
{
    /**
     * @store or @renew subscription for @hotel
     */
    public function store_subscription(Request $request)
    {
        $rules = [
            'payment_method' => 'required|in:online,bank',
            'transfer_photo' => 'required_if:payment_method,bank|mimes:jpg,jpeg,png,gif,tif,bmp,psd|max:5000',
            'online_type' => 'required_if:payment_method,online|in:visa,mada,apple_pay',
            'seller_code' => 'nullable|exists:seller_codes,seller_name',
            'bank_id' => 'required_if:payment_method,bank|exists:banks,id',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $hotel = $request->user();
        /**
         * 1- get the subscription price
         */
        $seller_code = null;
        $tax = Setting::find(1)->tax;
        $discount = 0;
        $package_price = Package::find(1)->price;
        // check if there are a seller code or not
        if ($request->seller_code != null) {
            $seller_code = SellerCode::where('seller_name', $request->seller_code)
                ->where('status', 'active')
                ->where('country_id', $hotel->country_id)
                ->whereIn('type', ['hotel', 'all'])
                ->first();
            if ($seller_code) {
                if ($seller_code->start_at <= Carbon::now() and $seller_code->end_at >= Carbon::now()) {
                    $discount_percentage = $seller_code->code_percentage;
                    $discount = ($package_price * $discount_percentage) / 100;
                    $price_after_percentage = $package_price - $discount;
                    $commission = $package_price * ($seller_code->percentage - $seller_code->code_percentage) / 100;
                    $seller_code->update([
                        'commission' => $commission + $seller_code->commission,
                    ]);
                    // store this operation to marketer history
                    MarketerOperation::create([
                        'hotel_id' => $hotel->id,
                        'marketer_id' => $seller_code->marketer_id,
                        'seller_code_id' => $seller_code->id,
                        'subscription_id' => $hotel->subscription->id,
                        'status' => 'not_done',
                        'amount' => $commission,
                    ]);
                    $price = $price_after_percentage;
                    $tax_value = $price * $tax / 100;
                    $price = $price + $tax_value;
                } else {
                    $price = $package_price;
                    $tax_value = $price * $tax / 100;
                    $price = $price + $tax_value;
                }
            } else {
                $price = $package_price;
                $tax_value = $price * $tax / 100;
                $price = $price + $tax_value;
            }
        } else {
            $price = $package_price;
            $tax_value = $price * $tax / 100;
            $price = $price + $tax_value;
        }
        if ($request->payment_method == 'bank') {
            $hotel->subscription->update([
                'bank_id' => $request->bank_id,
                'payment_type' => $request->payment_method,
                'amount' => $price,
                'tax_value' => $tax_value,
                'discount_value' => $discount,
                'seller_code_id' => $seller_code == null ? null : $seller_code->id,
                'transfer_photo' => UploadImage($request->file('transfer_photo'), 'photo', '/uploads/transfers'),
            ]);
            $success = [
                'message' => trans('messages.your_request_sent_successfully'),
            ];
            return ApiController::respondWithSuccess($success);
        } elseif ($request->payment_method == 'online') {
            // online payment by my fatoourah
            $amount = number_format((float)$price, 2, '.', '');
            if ($request->online_type == 'visa') {
                $charge = 2;
            } elseif ($request->online_type == 'mada') {
                $charge = 6;
            } elseif ($request->online_type == 'apple_pay') {
                $charge = 11;
            } else {
                $charge = 2;
            }
            $name = $hotel->subdomain;
            $token = Setting::first()->online_token;
            $data = array(
                'PaymentMethodId' => $charge,
                'CustomerName' => $name,
                'DisplayCurrencyIso' => 'SAR',
                'MobileCountryCode' => $hotel->country->code,
                'CustomerMobile' => $hotel->phone_number,
                'CustomerEmail' => $hotel->email,
                'InvoiceValue' => $amount,
                'CallBackUrl' => url('/check-hotel-status'),
                'ErrorUrl' => url('/error'),
                'Language' => app()->getLocale(),
                'CustomerReference' => 'ref 1',
                'CustomerCivilId' => '12345678',
                'UserDefinedField' => 'Custom field',
                'ExpireDate' => '',
                'CustomerAddress' => array(
                    'Block' => '',
                    'Street' => '',
                    'HouseBuildingNo' => '',
                    'Address' => '',
                    'AddressInstructions' => '',
                ),
                'InvoiceItems' => [array(
                    'ItemName' => $name,
                    'Quantity' => '1',
                    'UnitPrice' => $amount,
                )],
            );
            $data = json_encode($data);
            $fatooraRes = MyFatoorah($token, $data);
            $result = json_decode($fatooraRes);
            if ($result != null and $result->IsSuccess === true) {
                $hotel->subscription->update([
                    'invoice_id' => $result->Data->InvoiceId,
                    'payment_type' => $request->payment_method,
                    'amount' => $price,
                    'tax_value' => $tax_value,
                    'discount_value' => $discount,
                    'seller_code_id' => $seller_code == null ? null : $seller_code->id,
                ]);
                $success = [
                    'payment_url' => $result->Data->PaymentURL
                ];
                return ApiController::respondWithSuccess($success);
            } else {
                $error = [
                    'message' => trans('messages.errorPayment')
                ];
                return ApiController::respondWithErrorObject($error);
            }
        }
    }

    public function check_status(Request $request)
    {
        $token = Setting::first()->online_token;
        $PaymentId = $request->query('paymentId');
        $resData = MyFatoorahStatus($token, $PaymentId);
        $result = json_decode($resData);
        if ($result->IsSuccess === true and $result->Data->InvoiceStatus === "Paid") {
            $InvoiceId = $result->Data->InvoiceId;
            $subscription = Subscription::where('invoice_id', $InvoiceId)->first();
            $end_at = Carbon::now()->addMonths($subscription->package->duration);
            History::create([
                'hotel_id' => $subscription->hotel->id,
                'package_id' => $subscription->package->id,
                'branch_id' => $subscription->branch_id,
                'payment_type' => 'online',
                'details' =>   ($subscription->status == 'tentative' or $subscription->status == 'tentative_finished') ? trans('messages.hotel_new_subscribe') : trans('messages.hotel_renew_subscribe'),
                'invoice_id' => $subscription->invoice_id,
                'price'     => $subscription->package->price,
                'paid_amount' => $subscription->amount,
                'discount_value' => $subscription->discount_value,
                'tax_value'      => $subscription->tax_value,
                'operation_date' => Carbon::now(),
                'status'     => ($subscription->status == 'tentative' or $subscription->status == 'tentative_finished') ? 'new' : 'renew'
            ]);
            $subscription->update([
                'status' => 'active',
                'end_at' => $end_at,
                'paid_at' => Carbon::now(),
                'is_payment' => 'true',
                'payment_type' => 'online',
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
        }else{
            $error = [
                'message' => trans('messages.errorPayment')
            ];
            return ApiController::respondWithErrorObject($error);
        }
    }
    public function subscribe_price(Request $request)
    {
        $package_price = Package::find(1)->price;
        $success = [
            'subscribe_price' => $package_price
        ];
        return ApiController::respondWithSuccess($success);
    }
}
