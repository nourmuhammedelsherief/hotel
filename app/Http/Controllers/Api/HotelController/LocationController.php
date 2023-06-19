<?php

namespace App\Http\Controllers\Api\HotelController;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Hotel\HotelLocationCollection;
use App\Http\Resources\Hotel\HotelLocationResource;
use App\Models\Hotel\HotelLocation;
use Illuminate\Http\Request;
use Validator;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $hotel = $request->user();
        $locations = HotelLocation::whereHotelId($hotel->id)->orderBy('id' , 'desc')->paginate();
        return ApiController::respondWithSuccess(new HotelLocationCollection($locations));
    }
    public function create(Request $request)
    {
        $hotel = $request->user();
        $rules = [
            'name_ar'  => 'required|string|max:191',
            'name_en'  => 'required|string|max:191',
            'link'     => 'required|max:191',
            'photo'    => 'required|mimes:jpg,jpeg,png,gif,tif,psd,webp,pmp|max:5000',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        // create new location
        $location = HotelLocation::create([
            'hotel_id'  => $hotel->id,
            'name_ar'   => $request->name_ar,
            'name_en'   => $request->name_en,
            'link'      => $request->link,
            'photo'     => $request->file('photo') == null ? null : UploadImage($request->file('photo') , 'photo' , '/uploads/locations'),
        ]);
        return ApiController::respondWithSuccess(new HotelLocationResource($location));
    }
    public function edit(Request $request , $id)
    {
        $location = HotelLocation::find($id);
        if ($location)
        {
            $hotel = $request->user();
            $rules = [
                'name_ar'  => 'nullable|string|max:191',
                'name_en'  => 'nullable|string|max:191',
                'link'     => 'nullable|max:191',
                'photo'    => 'nullable|mimes:jpg,jpeg,png,gif,tif,psd,webp,pmp|max:5000',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails())
                return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

            // update location
            $location->update([
                'name_ar'   => $request->name_ar == null ? $location->name_ar : $request->name_ar,
                'name_en'   => $request->name_en == null ? $location->name_en :$request->name_en,
                'link'      => $request->link == null ? $location->link : $request->link ,
                'photo'     => $request->file('photo') == null ? $location->photo: UploadImageEdit($request->file('photo') , 'photo' , '/uploads/locations' , $location->photo),
            ]);
            return ApiController::respondWithSuccess(new HotelLocationResource($location));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function show($id)
    {
        $location = HotelLocation::find($id);
        if ($location)
        {
            return ApiController::respondWithSuccess(new HotelLocationResource($location));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function destroy($id)
    {
        $location = HotelLocation::find($id);
        if ($location)
        {
            if ($location->photo != null)
            {
                @unlink(public_path('/uploads/locations/' . $location->photo));
            }
            $location->delete();
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
