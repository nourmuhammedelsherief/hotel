<?php

namespace App\Http\Controllers\Api\AdminController;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CountryResource;
use App\Models\Country;
use Illuminate\Http\Request;
use Validator;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::orderBy('id' , 'desc')->get();
        return ApiController::respondWithSuccess(CountryResource::collection($countries));
    }
    public function create(Request $request)
    {
        $rules = [
            'name_ar'   => 'required|string|max:191',
            'name_en'   => 'required|string|max:191',
            'currency_ar' => 'required|string|max:191',
            'currency_en' => 'required|string|max:191',
            'code'        => 'required|max:191',
            'subscription_price' => 'required',
            'currency_code' => 'sometimes',
            'active' => 'nullable',
            'flag'        => 'required|mimes:jpg,jpeg,png,gif,tif,psd,bmp|max:5000',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        // create new country
        $country = Country::create([
            'name_ar'        => $request->name_ar,
            'name_en'        => $request->name_en,
            'currency_ar'    => $request->currency_ar,
            'currency_en'    => $request->currency_en,
            'active'         => $request->active == null ? 'true' : $request->active,
            'code'           => $request->code,
            'subscription_price' => $request->subscription_price,
            'currency_code'  => $request->currency_code,
            'flag'           => $request->flag == null ? null : UploadImage($request->file('flag') , 'flag' , '/uploads/flags'),
        ]);
        return ApiController::respondWithSuccess(new CountryResource($country));
    }
    public function show($id)
    {
        $country = Country::find($id);
        if ($country)
        {
            return ApiController::respondWithSuccess(new CountryResource($country));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function edit(Request $request , $id)
    {
        $rules = [
            'name_ar'   => 'nullable|string|max:191',
            'name_en'   => 'nullable|string|max:191',
            'currency_ar' => 'nullable|string|max:191',
            'currency_en' => 'nullable|string|max:191',
            'code'        => 'nullable|max:191',
            'currency_code' => 'sometimes',
            'subscription_price' => 'sometimes',
            'active' => 'nullable',
            'flag'        => 'nullable|mimes:jpg,jpeg,png,gif,tif,psd,bmp|max:5000',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $country = Country::find($id);
        if ($country)
        {
            $country->update([
                'name_ar'        => $request->name_ar == null ? $country->name_ar : $request->name_ar,
                'name_en'        => $request->name_en == null ? $country->name_en : $request->name_en,
                'currency_ar'    => $request->currency_ar == null ? $country->currency_ar : $request->currency_ar,
                'currency_en'    => $request->currency_en == null ? $country->currency_en : $request->currency_en,
                'code'           => $request->code == null ? $country->code : $request->code,
                'active'         => $request->active == null ? $country->active : $request->active,
                'subscription_price' => $request->subscription_price == null ? $country->subscription_price : $request->subscription_price,
                'currency_code'  => $request->currency_code == null ? $country->currency_code : $request->currency_code,
                'flag'           => $request->flag == null ? $country->flag : UploadImageEdit($request->file('flag') , 'flag' , '/uploads/flags' , $country->flag),
            ]);
            return ApiController::respondWithSuccess(new CountryResource($country));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function destroy($id)
    {
        $country = Country::find($id);
        if ($country){
            if (isset($country->flag))
            {
                @unlink(public_path('/uploads/flags/' . $country->flag));
            }
            $country->delete();
            $success = [
                'message' => trans('messages.deleted')
            ];
            return ApiController::respondWithSuccess($success);
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function active(Request $request , $id)
    {
        $country = Country::find($id);
        if ($country){
            $rules = [
                'active'   => 'required|in:true,false',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails())
                return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

            $country->update([
                'active'   => $request->active,
            ]);
            return ApiController::respondWithSuccess(new CountryResource($country));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }


}
