<?php

namespace App\Http\Controllers\Api\HotelController;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Hotel\HotelServiceCategoryItemCollection;
use App\Http\Resources\Hotel\HotelServiceCategoryItemResource;
use App\Models\Hotel\HotelServiceCategory;
use App\Models\Hotel\HotelServiceCategoryItem;
use App\Models\Hotel\HotelServiceCategoryItemPhoto;
use Illuminate\Http\Request;
use Validator;

class HotelServiceCategoryItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $category = HotelServiceCategory::find($id);
        if ($category) {
            $items = HotelServiceCategoryItem::where('hotel_service_cat_id', $category->id)
                ->orderBy('id', 'desc')
                ->paginate();
            return ApiController::respondWithSuccess(new HotelServiceCategoryItemCollection($items));
        } else {
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, $id)
    {
        $category = HotelServiceCategory::find($id);
        if ($category) {
            $rules = [
                'name_ar' => 'required|string|max:191',
                'name_en' => 'required|string|max:191',
                'description_ar' => 'required|string',
                'description_en' => 'required|string',
                'sliders' => 'required',
                'sliders*' => 'mimes:jpg,jpeg,png,gif,tif,psd,pmp,webp|max:5000',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails())
                return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

            // create new info category item
            $item = HotelServiceCategoryItem::create([
                'hotel_service_cat_id' => $category->id,
                'name_ar' => $request->name_ar,
                'name_en' => $request->name_en,
                'description_ar' => $request->description_ar,
                'description_en' => $request->description_en,
            ]);

            // create slider images
            if ($request->sliders != null) {
                foreach ($request->sliders as $image) {
                    if($image)
                    {
                        HotelServiceCategoryItemPhoto::create([
                            'hotel_service_item_id' => $item->id,
                            'photo' => UploadImage($image, 'photo', '/uploads/service_slider_images'),
                        ]);
                    }
                }
            }
            return ApiController::respondWithSuccess(new HotelServiceCategoryItemResource($item));
        } else {
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function edit(Request $request, $id)
    {
        $item = HotelServiceCategoryItem::find($id);
        if ($item) {
            $rules = [
                'name_ar' => 'nullable|string|max:191',
                'name_en' => 'nullable|string|max:191',
                'description_ar' => 'nullable|string',
                'description_en' => 'nullable|string',
                'sliders' => 'nullable',
                'sliders*' => 'mimes:jpg,jpeg,png,gif,tif,psd,pmp,webp|max:5000',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails())
                return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

            $item->update([
                'name_ar' => $request->name_ar == null ? $item->name_ar : $request->name_ar,
                'name_en' => $request->name_en == null ? $item->name_en : $request->name_en,
                'description_ar' => $request->description_ar == null ? $item->description_ar : $request->description_ar,
                'description_en' => $request->description_en == null ? $item->description_en : $request->description_en,
            ]);

            // create slider images
            if ($request->sliders != null) {
                foreach ($request->sliders as $image) {
                    if($image)
                    {
                        HotelServiceCategoryItemPhoto::create([
                            'hotel_service_item_id' => $item->id,
                            'photo' => UploadImage($image, 'photo', '/uploads/service_slider_images'),
                        ]);
                    }
                }
            }
            return ApiController::respondWithSuccess(new HotelServiceCategoryItemResource($item));
        } else {
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function show($id)
    {
        $item = HotelServiceCategoryItem::find($id);
        if ($item)
        {
            return ApiController::respondWithSuccess(new HotelServiceCategoryItemResource($item));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function destroy($id)
    {
        $item = HotelServiceCategoryItem::find($id);
        if ($item)
        {
            // remove item slider photos
            if ($item->sliders->count() > 0)
            {
                foreach ($item->sliders as $slider)
                {
                    @unlink(public_path('/uploads/service_slider_images/' . $slider->photo));
                }
            }
            $item->delete();
            $success = [
                'message' => trans('messages.deleted')
            ];
            return ApiController::respondWithSuccess($success);
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function remove_service_item_photo($id)
    {
        $slider = HotelServiceCategoryItemPhoto::find($id);
        if ($slider)
        {
            @unlink(public_path('/uploads/service_slider_images/' . $slider->photo));
            $slider->delete();
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
