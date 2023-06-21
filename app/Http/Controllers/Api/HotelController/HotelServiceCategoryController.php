<?php

namespace App\Http\Controllers\Api\HotelController;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Hotel\HotelServiceCategoryCollection;
use App\Http\Resources\Hotel\HotelServiceCategoryResource;
use App\Models\Hotel\HotelService;
use App\Models\Hotel\HotelServiceCategory;
use Illuminate\Http\Request;
use Validator;

class HotelServiceCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $hotel = $request->user();
        $categories = HotelServiceCategory::whereHotelId($hotel->id)
            ->orderBy('id' , 'desc')
            ->paginate();
        return ApiController::respondWithSuccess(new HotelServiceCategoryCollection($categories));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $hotel = $request->user();
        $rules = [
            'name_ar'   => 'required|string|max:191',
            'name_en'   => 'required|string|max:191',
            'photo'     => 'required|mimes:jpg,jpeg,png,gif,tif,psd,pmp,webp|max:5000'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));
        $service = HotelService::whereHotelId($hotel->id)->first();
        // create new service category
        $category = HotelServiceCategory::create([
            'hotel_id' => $hotel->id,
            'hotel_service_id' => $service->id,
            'name_ar'  => $request->name_ar,
            'name_en'  => $request->name_en,
            'photo'    => $request->file('photo') == null ? null : UploadImage($request->file('photo') , 'photo' , '/uploads/service_categories')
        ]);
        return ApiController::respondWithSuccess(new HotelServiceCategoryResource($category));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = HotelServiceCategory::find($id);
        if ($category)
        {
            return ApiController::respondWithSuccess(new HotelServiceCategoryResource($category));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request , $id)
    {
        $category = HotelServiceCategory::find($id);
        if ($category)
        {
            $rules = [
                'name_ar'   => 'nullable|string|max:191',
                'name_en'   => 'nullable|string|max:191',
                'photo'     => 'nullable|mimes:jpg,jpeg,png,gif,tif,psd,pmp,webp|max:5000'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails())
                return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

            $category->update([
                'name_ar'  => $request->name_ar == null ? $category->name_ar : $request->name_ar,
                'name_en'  => $request->name_en == null ? $category->name_en : $request->name_en,
                'photo'    => $request->file('photo') == null ? $category->photo : UploadImageEdit($request->file('photo') , 'photo' , '/uploads/service_categories' , $category->photo)
            ]);
            return ApiController::respondWithSuccess(new HotelServiceCategoryResource($category));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = HotelServiceCategory::find($id);
        if ($category)
        {
            if ($category->photo != null)
            {
                @unlink(public_path('/uploads/service_categories/' . $category->photo));
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
