<?php

namespace App\Http\Controllers\Api\AdminController;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CityCollection;
use App\Http\Resources\Admin\CityResource;
use App\Models\City;
use Illuminate\Http\Request;
use Validator;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::orderBy('id' , 'desc')->paginate();
        return ApiController::respondWithSuccess(new CityCollection($cities));
    }
    public function country_cities($id)
    {
        $cities = City::whereCountryId($id)->orderBy('id' , 'desc')->get();
        return ApiController::respondWithSuccess(CityResource::collection($cities));
    }
    public function create(Request $request)
    {
        $rules = [
            'country_id'=> 'required|exists:countries,id',
            'name_ar'   => 'required|string|max:191',
            'name_en'   => 'required|string|max:191',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));
        // create new city
        $city = City::create([
            'country_id'  => $request->country_id,
            'name_ar'     => $request->name_ar,
            'name_en'     => $request->name_en,
        ]);
        return ApiController::respondWithSuccess(new CityResource($city));
    }
    public function show($id)
    {
        $city = City::find($id);
        if ($city)
        {
            return ApiController::respondWithSuccess(new CityResource($city));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function edit(Request $request , $id)
    {
        $rules = [
            'country_id'=> 'nullable|exists:countries,id',
            'name_ar'   => 'nullable|string|max:191',
            'name_en'   => 'nullable|string|max:191',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));
        $city = City::find($id);
        if ($city)
        {
            $city->update([
                'country_id'  => $request->country_id == null ? $city->country_id : $request->country_id,
                'name_ar'     => $request->name_ar == null ? $city->name_ar : $request->name_ar,
                'name_en'     => $request->name_en == null ? $city->name_en : $request->name_en,
            ]);
            return ApiController::respondWithSuccess(new CityResource($city));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function destroy($id)
    {
        $city = City::find($id);
        if ($city)
        {
            $city->delete();
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
