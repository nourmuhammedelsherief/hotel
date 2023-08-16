<?php

namespace App\Http\Controllers\Api\HotelController;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Hotel\HotelGalleryCategoryCollection;
use App\Http\Resources\Hotel\HotelGalleryCategoryResource;
use App\Models\Hotel\HotelGalleryCategory;
use Illuminate\Http\Request;
use Validator;

class GalleryCategoryController extends Controller
{
    public function index(Request $request)
    {
        $hotel = $request->user();
        $categories = HotelGalleryCategory::whereHotelId($hotel->id)
            ->orderBy('id' , 'desc')
            ->paginate();
        return ApiController::respondWithSuccess(new HotelGalleryCategoryCollection($categories));
    }
    public function create(Request $request)
    {
        $hotel = $request->user();
        $rules = [
            'name_ar'  => 'required|string|max:191',
            'name_en'  => 'required|string|max:191',
            'icon'     => 'required|mimes:jpg,jpeg,png,gif,tif,psd,pmp,webp|max:5000',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        // create new Gallery Category
        $category = HotelGalleryCategory::create([
            'hotel_id'  => $hotel->id,
            'name_ar'   => $request->name_ar,
            'name_en'   => $request->name_en,
            'icon'      => UploadImage($request->file('icon') , 'icon' , '/uploads/gallery_category_icons')
        ]);
        return ApiController::respondWithSuccess(new HotelGalleryCategoryResource($category));
    }
    public function edit(Request $request , $id)
    {
        $category = HotelGalleryCategory::find($id);
        if ($category)
        {
            $hotel = $request->user();
            $rules = [
                'name_ar'  => 'nullable|string|max:191',
                'name_en'  => 'nullable|string|max:191',
                'icon'     => 'nullable|mimes:jpg,jpeg,png,gif,tif,psd,bmp,webp|max:5000'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails())
                return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

            // update Gallery Category
            $category->update([
                'name_ar'   => $request->name_ar == null ? $category->name_ar : $request->name_ar,
                'name_en'   => $request->name_en == null ? $category->name_en : $request->name_en ,
                'icon'      => $request->file('icon') == null ? $category->icon : UploadImageEdit($request->file('icon') , 'icon' , '/uploads/gallery_category_icons' , $category->icon)
            ]);
            return ApiController::respondWithSuccess(new HotelGalleryCategoryResource($category));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function show($id)
    {
        $category = HotelGalleryCategory::find($id);
        if ($category)
        {
            return ApiController::respondWithSuccess(new HotelGalleryCategoryResource($category));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function destroy($id)
    {
        $category = HotelGalleryCategory::find($id);
        if ($category)
        {
            if ($category->icon != null)
            {
                @unlink(public_path('/uploads/gallery_category_icons/'.$category->icon));
            }
            $category->delete();
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
