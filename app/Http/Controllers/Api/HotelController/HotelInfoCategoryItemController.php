<?php

namespace App\Http\Controllers\Api\HotelController;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Hotel\HotelInfoCategoryCollection;
use App\Http\Resources\Hotel\HotelInfoCategoryItemCollection;
use App\Http\Resources\Hotel\HotelInfoCategoryItemResource;
use App\Http\Resources\Hotel\HotelInfoCategoryResource;
use App\Models\Hotel\HotelInformation;
use App\Models\Hotel\HotelInformationCategory;
use App\Models\Hotel\HotelInformationCategoryItem;
use App\Models\Hotel\HotelInformationCategoryItemPhoto;
use Illuminate\Http\Request;
use Validator;

class HotelInfoCategoryItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $category = HotelInformationCategory::find($id);
        if ($category) {
            $items = HotelInformationCategoryItem::where('hotel_info_category_id', $category->id)
                ->orderBy('id', 'desc')
                ->paginate();
            return ApiController::respondWithSuccess(new HotelInfoCategoryItemCollection($items));
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
        $category = HotelInformationCategory::find($id);
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
            $item = HotelInformationCategoryItem::create([
                'hotel_info_category_id' => $category->id,
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
                        HotelInformationCategoryItemPhoto::create([
                            'info_item_id' => $item->id,
                            'photo' => UploadImage($image, 'photo', '/uploads/info_slider_images'),
                        ]);
                    }
                }
            }
            return ApiController::respondWithSuccess(new HotelInfoCategoryItemResource($item));
        } else {
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }

    public function edit(Request $request, $id)
    {
        $item = HotelInformationCategoryItem::find($id);
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
                        HotelInformationCategoryItemPhoto::create([
                            'info_item_id' => $item->id,
                            'photo' => UploadImage($image, 'photo', '/uploads/info_slider_images'),
                        ]);
                    }
                }
            }
            return ApiController::respondWithSuccess(new HotelInfoCategoryItemResource($item));
        } else {
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function show($id)
    {
        $item = HotelInformationCategoryItem::find($id);
        if ($item)
        {
            return ApiController::respondWithSuccess(new HotelInfoCategoryItemResource($item));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function destroy($id)
    {
        $item = HotelInformationCategoryItem::find($id);
        if ($item)
        {
            // remove item slider photos
            if ($item->sliders->count() > 0)
            {
                foreach ($item->sliders as $slider)
                {
                    @unlink(public_path('/uploads/info_slider_images/' . $slider->photo));
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

    public function remove_item_slider_photo($id)
    {
        $slider = HotelInformationCategoryItemPhoto::find($id);
        if ($slider)
        {
            @unlink(public_path('/uploads/info_slider_images/' . $slider->photo));
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
