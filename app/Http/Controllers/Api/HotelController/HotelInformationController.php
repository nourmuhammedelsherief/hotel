<?php

namespace App\Http\Controllers\Api\HotelController;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Hotel\HotelInformationResource;
use App\Models\Hotel\HotelInformation;
use Illuminate\Http\Request;
use Validator;

class HotelInformationController extends Controller
{
    public function show(Request $request)
    {
        $hotel = $request->user();
        $info = HotelInformation::whereHotelId($hotel->id)->first();
        if ($info == null)
        {
            // create new Information
            $info = HotelInformation::create([
                'hotel_id'   => $hotel->id,
                'name_ar'    => 'معلومات عنا',
                'name_en'    => 'Information About Us',
                'description_ar' => 'وصف تعبيري عن قسم معلومات عنا',
                'description_en' => 'Description About Information Category',
                'icon'           => 'icon.png'
            ]);
        }
        dd($info);
        return ApiController::respondWithSuccess(new HotelInformationResource($info));
    }
    public function edit(Request $request , $id)
    {
        $info = HotelInformation::find($id);
        if ($info)
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

            $info->update([
                'name_ar'  => $request->name_ar == null ? $info->name_ar : $request->name_ar,
                'name_en'  => $request->name_en == null ? $info->name_en : $request->name_en,
                'description_ar' => $request->description_ar == null ? $info->description_ar : $request->description_ar,
                'description_en' => $request->description_en == null ? $info->description_en : $request->description_en,
                'icon'  => $request->file('icon') == null ? $info->icon : ($info->icon == 'icon.png' ? UploadImage($request->file('icon') , 'icon' , '/uploads/icons') : UploadImageEdit($request->file('icon') , 'icon' , '/uploads/icons' , $info->icon))
            ]);
            return ApiController::respondWithSuccess(new HotelInformationResource($info));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
}
