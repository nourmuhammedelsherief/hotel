<?php

namespace App\Http\Controllers\Api\AdminController;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\HistoryCollection;
use App\Http\Resources\Admin\PackageResource;
use App\Http\Resources\Admin\SettingResource;
use App\Models\Admin;
use App\Models\City;
use App\Models\Country;
use App\Models\History;
use App\Models\Hotel;
use App\Models\Marketer;
use App\Models\Package;
use App\Models\SellerCode;
use App\Models\Setting;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Validator;

class SettingController extends Controller
{
    public function settings()
    {
        $setting = Setting::first();
        return ApiController::respondWithSuccess(new SettingResource($setting));
    }

    public function edit_setting(Request $request)
    {
        $rules = [
            'bearer_token' => 'sometimes',
            'sender_name' => 'sometimes',
            'contact_number' => 'sometimes',
            'technical_support_number' => 'sometimes',
            'active_whatsapp_number' => 'sometimes',
            'tentative_period' => 'sometimes',
            'tax' => 'sometimes',
            'online_token' => 'sometimes',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $setting = Setting::first();
        $setting->update([
            'bearer_token' => $request->bearer_token == null ? $setting->bearer_token : $request->bearer_token,
            'sender_name'  => $request->sender_name == null ? $setting->sender_name : $request->sender_name,
            'contact_number' => $request->contact_number == null ? $setting->contact_number : $request->contact_number,
            'technical_support_number' => $request->technical_support_number == null ? $setting->technical_support_number : $request->technical_support_number,
            'active_whatsapp_number' => $request->active_whatsapp_number == null ? $setting->active_whatsapp_number : $request->active_whatsapp_number,
            'tentative_period' => $request->tentative_period == null ? $setting->tentative_period : $request->tentative_period,
            'tax'   => $request->tax == null ? $setting->tax : $request->tax,
            'online_token'   => $request->online_token == null ? $setting->online_token : $request->online_token,
        ]);
        return ApiController::respondWithSuccess(new SettingResource($setting));
    }

    public function subscription_info()
    {
        $package = Package::first();
        if ($package)
        {
            return ApiController::respondWithSuccess(new PackageResource($package));
        }
    }
    public function edit_subscription_info(Request $request)
    {
        $rules = [
            'price'    => 'sometimes',
            'duration' => 'sometimes',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $package = Package::first();
        $package->update([
            'price' => $request->price == null ? $package->price : $request->price,
            'duration' => $request->duration == null ? $package->duration : $request->duration,
        ]);
        return ApiController::respondWithSuccess(new PackageResource($package));
    }
    public function control_panel_home()
    {
        $admins = Admin::count();
        $active_hotels = Hotel::whereStatus('active')->count();
        $tentative_hotels = Hotel::whereStatus('tentative')->count();
        $inCompleteHotels = Hotel::whereStatus('in_complete')->count();
        $waiting_admin_hotels = Hotel::whereAdminActivation('false')->count();
        $archived_hotels = Hotel::whereArchive('true')->count();
        $marketers = Marketer::count();
        $seller_codes = SellerCode::count();
        $countries = Country::count();
        $cities = City::count();
        $transfers = Subscription::whereType('hotel')
            ->where('transfer_photo' , '!=' , null)
            ->where('bank_id' , '!=' , null)
            ->where('payment_type' , 'bank')
            ->whereIn('status' , ['tentative' , 'finished','tentative_finished'])
            ->count();
        $histories = History::count();
        $success = [
            'admins'  => $admins,
            'active_hotels'  => $active_hotels,
            'tentative_hotels' => $tentative_hotels,
            'inCompleteHotels' => $inCompleteHotels,
            'waiting_admin_activation_hotels' => $waiting_admin_hotels,
            'archived_hotels' => $archived_hotels,
            'marketers' => $marketers,
            'seller_codes' => $seller_codes,
            'countries' => $countries,
            'cities' => $cities,
            'hotels_bank_transfers' => $transfers,
            'histories' => $histories,
        ];
        return ApiController::respondWithSuccess($success);
    }
}
