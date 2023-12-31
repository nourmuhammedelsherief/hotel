<?php

namespace App\Http\Controllers\Api\HotelController;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Hotel\HotelServiceResource;
use App\Models\Hotel\HotelService;
use Illuminate\Http\Request;
use Validator;

class HotelServiceController extends Controller
{
    public function show(Request $request)
    {
        $hotel = $request->user();
        $service = HotelService::whereHotelId($hotel->id)->first();
        if ($service == null)
        {
            // create new Service
            $service = HotelService::create([
                'hotel_id'   => $hotel->id,
                'name_ar'    => 'خدماتنا',
                'name_en'    => 'Our Services',
                'description_ar' => 'وصف تعبيري عن قسم خدماتنا',
                'description_en' => 'Description About Service Category',
                'icon'           => 'service.png'
            ]);
        }
        return ApiController::respondWithSuccess(new HotelServiceResource($service));
    }
    public function edit(Request $request)
    {
        $hotel = $request->user();
        $service = HotelService::whereHotelId($hotel->id)->first();
        if ($service)
        {
            $rules = [
                'name_ar'   => 'nullable|string|max:191',
                'name_en'   => 'nullable|string|max:191',
                'description_ar' => 'nullable|string',
                'description_en' => 'nullable|string',
                'icon'           => 'nullable|mimes:jpg,jpeg,png,gif,tif,webp,psd,pmp|max:5000'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails())
                return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

            $service->update([
                'name_ar'  => $request->name_ar == null ? $service->name_ar : $request->name_ar,
                'name_en'  => $request->name_en == null ? $service->name_en : $request->name_en,
                'description_ar' => $request->description_ar == null ? $service->description_ar : $request->description_ar,
                'description_en' => $request->description_en == null ? $service->description_en : $request->description_en,
                'icon'  => $request->file('icon') == null ? $service->icon : ($service->icon == 'service.png' ? UploadImage($request->file('icon') , 'icon' , '/uploads/icons') : UploadImageEdit($request->file('icon') , 'icon' , '/uploads/icons' , $service->icon))
            ]);
            return ApiController::respondWithSuccess(new HotelServiceResource($service));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
}
