<?php

namespace App\Http\Controllers\Api\HotelController;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\HotelResource;
use App\Models\Branch;
use App\Models\Country;
use App\Models\Hotel;
use App\Models\HotelSlider;
use App\Models\HotelSliderImage;
use App\Models\Setting;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class AuthHotelController extends Controller
{
    public function register(Request $request)
    {
        $rules = [
            'name_ar'    => 'required|string|max:191',
            'name_en'    => 'required|string|max:191',
            'subdomain'  => 'required|string|unique:hotels',
//            'logo'       => 'sometimes|mimes:jpg,jpeg,gif,tif,psd,pmp,png|max:5000',
            'country_id' => 'required|exists:countries,id',
            'city_id'    => 'required|exists:cities,id',
            'email'      => 'required|email|unique:hotels',
            'password'   => 'required|min:6',
            'password_confirmation' => 'required|same:password|min:6',
            'phone_number'  => 'required|min:8|unique:hotels',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        // create new Hotel
        $code = mt_rand(1000, 9999);
        $hotel = Hotel::create([
            'name_ar'       => $request->name_ar,
            'name_en'       => $request->name_en,
            'subdomain'     => $request->subdomain,
            'status'        => 'in_complete',
            'logo'          => 'logo.png',
            'country_id'    => $request->country_id,
            'city_id'       => $request->city_id,
            'package_id'    => 1,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'phone_number'  => $request->phone_number,
            'admin_activation' => 'false',
            'phone_verification' => $code,
            'description_ar' => 'وصف عن الفندق ومميزاته',
            'description_en' => 'Hotel Description About It And It\'s Advantages',
        ]);
        // create hotel slider
        $slider = HotelSlider::create([
            'hotel_id' => $hotel->id,
            'type'     => 'image',
        ]);
        HotelSliderImage::create([
            'slider_id' => $slider->id,
            'image'     => 'slider1.jpg'
        ]);
        HotelSliderImage::create([
            'slider_id' => $slider->id,
            'image'     => 'slider2.jpg'
        ]);

        // send sms to hotel owner
        $country = $hotel->country->code;
        // send code to phone_number
        $message = trans('messages.hotel_verification_code') . $code .' '. trans('messages.tqnee');
        $check = substr($hotel->phone_number, 0, 2) === '05';
        if ($check == true) {
            $phone = $country . ltrim($hotel->phone_number, '0');
        } else {
            $phone = $country . $hotel->phone_number;
        }
        taqnyatSms($message, $phone);

        // create hotel main branch
        $branch = Branch::create([
            'hotel_id'   => $hotel->id,
            'country_id' => $hotel->country_id,
            'city_id'    => $hotel->city_id,
            'status'     => 'tentative',
            'main'       => 'true',
            'name_ar'    => $hotel->name_ar,
            'name_en'    => $hotel->name_en,
            'subdomain'  => $hotel->subdomain,
            'email'      => $hotel->email,
            'phone_number' => $hotel->phone_number,
            'password'     => $hotel->password,
        ]);
        // create hotel subscription
        $tentative_period = Setting::first()->tentative_period;
        Subscription::create([
            'hotel_id'          => $hotel->id,
            'branch_id'         => $branch->id,
            'package_id'        => 1,
            'type'              => 'hotel',
            'status'            => 'tentative',
            'subscription_type' => 'subscription',
            'end_at'            => Carbon::now()->addDays($tentative_period),
            'is_payment'        => 'false',
        ]);

        return  ApiController::respondWithSuccess(new HotelResource($hotel));
    }
    public function resend_code(Request $request)
    {
        $rules = [
            'country_id' => 'required|exists:countries,id',
            'phone_number'  => 'required|min:8',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $code = mt_rand(1000, 9999);
        $country = Country::find($request->country_id)->code;
        // send code to phone_number
        $message = trans('messages.hotel_verification_code') . $code .' '. trans('messages.tqnee');
        $check = substr($request->phone_number, 0, 2) === '05';
        if ($check == true) {
            $phone = $country . ltrim($request->phone_number, '0');
        } else {
            $phone = $country . $request->phone_number;
        }
        taqnyatSms($message, $phone);
        $success = [
            'message' => trans('messages.codeSentSuccessfully')
        ];
        return ApiController::respondWithSuccess($success);
    }
    public function verify_phone(Request $request)
    {
        $rules = [
            'phone_number'    => 'required|min:8',
            'code'            => 'required|min:4',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $hotel = Hotel::wherePhoneNumber($request->phone_number)
            ->orderBy('id' , 'desc')
            ->first();
        if ($hotel)
        {
            if ($hotel->phone_verification == $request->code)
            {
                $hotel->update([
                    'phone_verified_at' => Carbon::now(),
                    'status' => 'tentative',
                    'api_token' => generateApiToken($hotel->id, 50),
                    'phone_verification' => null
                ]);
                $success = [
                    'message' => trans('messages.phone_verified_successfully'),
                ];
                return ApiController::respondWithSuccess(new HotelResource($hotel));
            }else{
                $error = [
                    'message' => trans('messages.error_code')
                ];
                return ApiController::respondWithErrorObject($error);
            }
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function login(Request $request)
    {
        $rules = [
            'email'    => 'required|email',
            'password' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $hotel = Hotel::whereEmail($request->email)->first();
        if (auth()->guard('hotel')->attempt(['email' => $request->email, 'password' => $request->password])) {
            if ($hotel->status == 'in_complete')
            {
                $errors = [
                    'message' => trans('messages.hotelInComplete'),
                ];
                return ApiController::respondWithErrorObject(array($errors));
            }
            elseif($hotel->admin_activation == 'false')
            {
                $errors = [
                    'message' => trans('messages.hotelWaitAdminActivation'),
                ];
                return ApiController::respondWithErrorObject(array($errors));
            }
            $hotel->update([
                'api_token' => generateApiToken($hotel->id, 50),
            ]);
            return ApiController::respondWithSuccess(new HotelResource($hotel));
        } else {
            if ($hotel == null) {
                $errors = [
                    'message' => trans('messages.wrong_email'),
                ];
                return ApiController::respondWithErrorObject(array($errors));
            } else {
                $errors = [
                    'message' => trans('messages.wrong_password'),
                ];
                return ApiController::respondWithErrorObject(array($errors));
            }
        }

    }
    public function logout(Request $request)
    {
        $hotel = Hotel::find($request->user()->id);
        $hotel->update([
            'api_token' => null
        ]);
        $success = [
            'message' => trans('messages.logout_successfully')
        ];
        return ApiController::respondWithSuccess($success);
    }
}
