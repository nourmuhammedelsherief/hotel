<?php

namespace App\Http\Controllers\Api\HotelController;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Hotel\HotelInfoCategoryCollection;
use App\Http\Resources\Hotel\HotelInfoCategoryResource;
use App\Models\Hotel\HotelInformation;
use App\Models\Hotel\HotelInformationCategory;
use Illuminate\Http\Request;
use Validator;

class HotelInfoCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $hotel = $request->user();
        $categories = HotelInformationCategory::whereHotelId($hotel->id)
            ->orderBy('id' , 'desc')
            ->paginate();
        return ApiController::respondWithSuccess(new HotelInfoCategoryCollection($categories));
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
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));
        $info = HotelInformation::whereHotelId($hotel->id)->first();
        // create new info category
        $category = HotelInformationCategory::create([
            'hotel_id' => $hotel->id,
            'hotel_information_id' => $info->id,
            'name_ar'  => $request->name_ar,
            'name_en'  => $request->name_en,
        ]);
        return ApiController::respondWithSuccess(new HotelInfoCategoryResource($category));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = HotelInformationCategory::find($id);
        if ($category)
        {
            return ApiController::respondWithSuccess(new HotelInfoCategoryResource($category));
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
        $category = HotelInformationCategory::find($id);
        if ($category)
        {
            $rules = [
                'name_ar'   => 'nullable|string|max:191',
                'name_en'   => 'nullable|string|max:191',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails())
                return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

            $category->update([
                'name_ar'  => $request->name_ar == null ? $category->name_ar : $request->name_ar,
                'name_en'  => $request->name_en == null ? $category->name_en : $request->name_en,
            ]);
            return ApiController::respondWithSuccess(new HotelInfoCategoryResource($category));
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
        $category = HotelInformationCategory::find($id);
        if ($category)
        {
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
