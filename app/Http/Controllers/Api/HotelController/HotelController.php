<?php

namespace App\Http\Controllers\Api\HotelController;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\BankCollection;
use App\Http\Resources\Admin\BankResource;
use App\Http\Resources\Admin\CityResource;
use App\Http\Resources\Admin\CountryResource;
use App\Http\Resources\Admin\HotelResource;
use App\Models\Bank;
use App\Models\City;
use App\Models\Country;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class HotelController extends Controller
{
    public function profile()
    {
        $hotel = auth()->guard('hotel-api')->user();
        return ApiController::respondWithSuccess(new HotelResource($hotel));
    }
    public function changePassword(Request $request)
    {
        $rules = [
            'password' => 'required',
            'password_confirmation' => 'required|same:password'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $hotel = $request->user();
        $hotel->update([
            'password' => Hash::make($request->password)
        ]);
        $success = [
            'message' => trans('messages.password_changed_successfully')
        ];
        return ApiController::respondWithSuccess($success);
    }
    public function edit_account(Request $request)
    {
        $hotel = $request->user();
        $rules = [
            'name_ar'    => 'required|string|max:191',
            'name_en'    => 'required|string|max:191',
            'subdomain'  => 'required|string|unique:hotels,subdomain,'.$hotel->id,
            'logo'       => 'sometimes|mimes:jpg,jpeg,gif,tif,psd,pmp,png|max:5000',
            'country_id' => 'required|exists:countries,id',
            'city_id'    => 'required|exists:cities,id',
            'email'      => 'required|email|unique:hotels,email,'.$hotel->id,
            'phone_number'  => 'required|min:8||unique:hotels,phone_number,'.$hotel->id,
            'description_ar' => 'sometimes',
            'description_en' => 'sometimes',
            'latitude'    => 'sometimes|string|max:191',
            'longitude'   => 'sometimes|string|max:191',
            'lang'        => 'sometimes|in:ar,en,both',
            'tax'         => 'sometimes|in:true,false',
            'tax_value'   => 'required_if:tax,true',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $hotel->update([
            'name_ar'       => $request->name_ar,
            'name_en'       => $request->name_en,
            'subdomain'     => $request->subdomain,
            'logo'          => $request->file('logo') == null ? $hotel->logo : UploadImageEdit($request->file('logo') , 'logo' , '/uploads/logo' , $hotel->logo),
            'country_id'    => $request->country_id,
            'city_id'       => $request->city_id,
            'email'         => $request->email,
            'phone_number'  => $request->phone_number,
            'description_ar' => $request->description_ar == null ? $hotel->description_ar : $request->description_ar,
            'description_en' => $request->description_en == null ? $hotel->description_en : $request->description_en,
            'latitude'    => $request->latitude == null ? $hotel->latitude : $request->latitude,
            'longitude'   => $request->longitude == null ? $hotel->longitude : $request->longitude,
            'lang'        => $request->lang == null ? $hotel->lang : $request->lang,
            'tax'         => $request->tax == null ? $hotel->tax : $request->tax,
            'tax_value'   => $request->tax_value == null ? $hotel->tax_value : $request->tax_value,
        ]);
        return ApiController::respondWithSuccess(new HotelResource($hotel));
    }
    public function barcode(Request $request)
    {
        $hotel = $request->user();
        $url = $hotel->subdomain.'.'.domain();
        return ApiController::respondWithSuccess(['barcode_url' => $url]);
    }
    public function logout(Request $request)
    {
        $admin = Hotel::find($request->user()->id);
        $admin->update([
            'api_token' => null
        ]);
        $success = [
            'message' => trans('messages.logout_successfully')
        ];
        return ApiController::respondWithSuccess($success);
    }
    public function countries()
    {
        $countries = Country::orderBy('id' , 'desc')->get();
        return ApiController::respondWithSuccess(CountryResource::collection($countries));
    }
    public function cities($id)
    {
        $cities = City::whereCountryId($id)->orderBy('id' , 'desc')->get();
        return ApiController::respondWithSuccess(CityResource::collection($cities));
    }
    public function banks(Request $request)
    {
        $hotel = $request->user();
        $banks = Bank::whereCountryId($hotel->country_id)->get();
        return ApiController::respondWithSuccess(BankResource::collection($banks));
    }
}

