<?php

namespace App\Http\Controllers\Api\HotelController;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Hotel\HotelPixelResource;
use App\Models\Hotel\HotelPixel;
use Illuminate\Http\Request;
use Validator;

class HotelPixelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $hotel = $request->user();
        $codes = HotelPixel::whereHotelId($hotel->id)->orderBy('id' , 'desc')->get();
        return ApiController::respondWithSuccess(HotelPixelResource::collection($codes));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $hotel = $request->user();
        $rules = [
            'pixel_name'   => 'required|string|max:191',
            'type'         => 'required|in:top,after',
            'top_head'     => 'required_if:type,top',
            'top_body'     => 'required_if:type,top',
            'after_head'   => 'required_if:type,after',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        // create new pixel
        $pixel = HotelPixel::create([
            'hotel_id'     => $hotel->id,
            'pixel_name'   => $request->pixel_name,
            'type'         => $request->type,
            'top_head'     => $request->top_head,
            'top_body'     => $request->top_body,
            'after_head'   => $request->after_head,
        ]);
        return ApiController::respondWithSuccess(new HotelPixelResource($pixel));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pixel = HotelPixel::find($id);
        if ($pixel)
        {
            return ApiController::respondWithSuccess(new HotelPixelResource($pixel));
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
        $pixel = HotelPixel::find($id);
        if ($pixel)
        {
            $rules = [
                'pixel_name'   => 'nullable|string|max:191',
                'type'         => 'nullable|in:top,after',
                'top_head'     => 'nullable',
                'top_body'     => 'nullable',
                'after_head'   => 'nullable',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails())
                return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

            // update pixel
            $pixel->update([
                'pixel_name'   => $request->pixel_name == null ? $pixel->pixel_name : $request->pixel_name,
                'type'         => $request->type == null ? $pixel->type : $request->type,
                'top_head'     => $request->top_head == null ? $pixel->top_head : $request->top_head,
                'top_body'     => $request->top_body == null ? $pixel->top_body : $request->top_body,
                'after_head'   => $request->after_head == null ? $pixel->after_head : $request->after_head,
            ]);
            return ApiController::respondWithSuccess(new HotelPixelResource($pixel));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pixel = HotelPixel::find($id);
        if ($pixel)
        {
            $pixel->delete();
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
