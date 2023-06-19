<?php

namespace App\Http\Controllers\Api\AdminController;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\SettingResource;
use App\Models\Setting;
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

}
