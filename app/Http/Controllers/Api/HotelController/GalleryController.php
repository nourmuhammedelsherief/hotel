<?php

namespace App\Http\Controllers\Api\HotelController;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Hotel\HotelGalleryCollection;
use App\Http\Resources\Hotel\HotelGalleryResource;
use App\Http\Resources\Hotel\PhotoGalleryIconResource;
use App\Models\Hotel\HotelGallery;
use App\Models\Hotel\HotelGalleryIcon;
use Illuminate\Http\Request;
use Validator;

class GalleryController extends Controller
{
    public function get_hotel_gallery_info(Request $request)
    {
        $hotel = $request->user();
        $gallery_icon = HotelGalleryIcon::whereHotelId($hotel->id)->first();
        if ($gallery_icon == null)
        {
            // create new gallery icon
            $gallery_icon = HotelGalleryIcon::create([
                    'hotel_id'  => $hotel->id,
                    'name_ar'   => 'معرض الصور',
                    'name_en'   => 'photo gallery',
                    'icon'      => 'photo_icon.png'
                ]);
        }
        return ApiController::respondWithSuccess(new PhotoGalleryIconResource($gallery_icon));
    }
    public function edit_hotel_gallery_info(Request $request)
    {
        $hotel = $request->user();
        $gallery_icon = HotelGalleryIcon::whereHotelId($hotel->id)->first();
        if ($gallery_icon)
        {
            $rules = [
                'name_ar'   => 'nullable|string|max:191',
                'name_en'   => 'nullable|string|max:191',
                'photo'     => 'nullable|mimes:jpg,jpeg,png,gif,tif,webp,psd,pmp|max:5000'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails())
                return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

            $gallery_icon->update([
                'name_ar'  => $request->name_ar == null ? $gallery_icon->name_ar : $request->name_ar,
                'name_en'  => $request->name_en == null ? $gallery_icon->name_en : $request->name_en,
                'icon'  => $request->file('photo') == null ? $gallery_icon->icon : ($gallery_icon->icon == 'photo_icon.png' ? UploadImage($request->file('photo') , 'icon' , '/uploads/icons') : UploadImageEdit($request->file('photo') , 'icon' , '/uploads/icons' , $gallery_icon->icon))
            ]);
            return ApiController::respondWithSuccess(new PhotoGalleryIconResource($gallery_icon));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function index(Request $request)
    {
        $hotel = $request->user();
        $galleries = HotelGallery::whereHotelId($hotel->id)
            ->orderBy('id' , 'desc')
            ->paginate();
        return ApiController::respondWithSuccess(new HotelGalleryCollection($galleries));
    }
    public function create(Request $request)
    {
        $hotel = $request->user();
        $rules = [
            'gallery_category_id'  => 'required|exists:hotel_gallery_categories,id',
            'photo'  => 'required|mimes:jpg,jpeg,png,gif,tif,psd,webp,pmp|max:5000',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        // create new Gallery
        $gallery = HotelGallery::create([
            'hotel_id'  => $hotel->id,
            'gallery_category_id' => $request->gallery_category_id,
            'photo'  => $request->photo == null ? null : UploadImage($request->file('photo') , 'photo' , '/uploads/galleries'),
        ]);
        return ApiController::respondWithSuccess(new HotelGalleryResource($gallery));
    }
    public function edit(Request $request , $id)
    {
        $gallery = HotelGallery::find($id);
        if ($gallery)
        {
            $rules = [
                'gallery_category_id'  => 'nullable|exists:hotel_gallery_categories,id',
                'photo'  => 'nullable|mimes:jpg,jpeg,png,gif,tif,psd,webp,pmp|max:5000',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails())
                return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

            // update Gallery
            $gallery->update([
                'gallery_category_id' => $request->gallery_category_id == null ? $gallery->gallery_category_id : $request->gallery_category_id,
                'photo'  => $request->photo == null ? $gallery->photo : UploadImageEdit($request->file('photo') , 'photo' , '/uploads/galleries' , $gallery->photo),
            ]);
            return ApiController::respondWithSuccess(new HotelGalleryResource($gallery));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function show($id)
    {
        $gallery = HotelGallery::find($id);
        if ($gallery)
        {
            return ApiController::respondWithSuccess(new HotelGalleryResource($gallery));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }

    public function destroy($id)
    {
        $gallery = HotelGallery::find($id);
        if ($gallery)
        {
            $gallery->delete();
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
