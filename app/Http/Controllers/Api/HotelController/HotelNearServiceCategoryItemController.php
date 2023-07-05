<?php

namespace App\Http\Controllers\Api\HotelController;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Hotel\HotelNearCategoryItemCollection;
use App\Http\Resources\Hotel\HotelNearCategoryItemResource;
use App\Models\Hotel\HotelNearServiceCategory;
use App\Models\Hotel\HotelNearServiceCategoryItem;
use App\Models\Hotel\HotelNearServiceCategoryItemPhoto;
use Illuminate\Http\Request;
use Validator;

class HotelNearServiceCategoryItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $category = HotelNearServiceCategory::find($id);
        if ($category) {
            $items = HotelNearServiceCategoryItem::where('hotel_near_cat_id', $category->id)
                ->orderBy('id', 'desc')
                ->paginate();
            return ApiController::respondWithSuccess(new HotelNearCategoryItemCollection($items));
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
        $category = HotelNearServiceCategory::find($id);
        if ($category) {
            $rules = [
                'name_ar' => 'required|string|max:191',
                'name_en' => 'required|string|max:191',
                'contact_number' => 'required|max:191',
                'latitude' => 'required|max:191',
                'longitude' => 'required|max:191',
                'menu_photos' => 'required',
                'menu_photos*' => 'mimes:jpg,jpeg,png,gif,tif,psd,pmp,webp|max:5000',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails())
                return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

            // create new info category item
            $item = HotelNearServiceCategoryItem::create([
                'hotel_near_cat_id' => $category->id,
                'name_ar'           => $request->name_ar,
                'name_en'           => $request->name_en,
                'contact_number'    => $request->contact_number,
                'latitude'          => $request->latitude,
                'longitude'         => $request->longitude,
            ]);

            // create near service item images
            if ($request->menu_photos != null) {
                foreach ($request->menu_photos as $image) {
                    if($image)
                    {
                        HotelNearServiceCategoryItemPhoto::create([
                            'near_item_id' => $item->id,
                            'photo' => UploadImage($image, 'photo', '/uploads/near_service_images'),
                        ]);
                    }
                }
            }
            return ApiController::respondWithSuccess(new HotelNearCategoryItemResource($item));
        } else {
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function edit(Request $request, $id)
    {
        $item = HotelNearServiceCategoryItem::find($id);
        if ($item) {
            $rules = [
                'name_ar' => 'nullable|string|max:191',
                'name_en' => 'nullable|string|max:191',
                'contact_number' => 'nullable|max:191',
                'latitude' => 'nullable|max:191',
                'longitude' => 'nullable|max:191',
                'menu_photos*' => 'mimes:jpg,jpeg,png,gif,tif,psd,pmp,webp|max:5000',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails())
                return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

            $item->update([
                'name_ar'        => $request->name_ar == null ? $item->name_ar : $request->name_ar,
                'name_en'        => $request->name_en == null ? $item->name_en : $request->name_en,
                'contact_number' => $request->contact_number == null ? $item->contact_number : $request->contact_number,
                'latitude'       => $request->latitude == null ? $item->latitude : $request->latitude,
                'longitude'      => $request->longitude == null ? $item->longitude : $request->longitude,
            ]);

            // create near service item images
            if ($request->menu_photos != null) {
                foreach ($request->menu_photos as $image) {
                    if($image)
                    {
                        HotelNearServiceCategoryItemPhoto::create([
                            'near_item_id' => $item->id,
                            'photo' => UploadImage($image, 'photo', '/uploads/near_service_images'),
                        ]);
                    }
                }
            }
            return ApiController::respondWithSuccess(new HotelNearCategoryItemResource($item));
        } else {
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function show($id)
    {
        $item = HotelNearServiceCategoryItem::find($id);
        if ($item)
        {
            return ApiController::respondWithSuccess(new HotelNearCategoryItemResource($item));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function destroy($id)
    {
        $item = HotelNearServiceCategoryItem::find($id);
        if ($item)
        {
            // remove item slider photos
            if ($item->photos->count() > 0)
            {
                foreach ($item->photos as $photo)
                {
                    @unlink(public_path('/uploads/near_service_images/' . $photo->photo));
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
    public function remove_near_item_photo($id)
    {
        $slider = HotelNearServiceCategoryItemPhoto::find($id);
        if ($slider)
        {
            @unlink(public_path('/uploads/near_service_images/' . $slider->photo));
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
