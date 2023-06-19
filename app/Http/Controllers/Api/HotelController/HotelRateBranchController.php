<?php

namespace App\Http\Controllers\Api\HotelController;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Hotel\HotelRateBranchCollection;
use App\Http\Resources\Hotel\HotelRateBranchResource;
use App\Http\Resources\Hotel\HotelRateCollection;
use App\Models\Hotel\HotelRate;
use App\Models\Hotel\HotelRateBranch;
use Illuminate\Http\Request;
use Validator;

class HotelRateBranchController extends Controller
{
    public function index(Request $request)
    {
        $hotel = $request->user();
        $branches = HotelRateBranch::whereHotelId($hotel->id)
            ->orderBy('id', 'desc')
            ->paginate();
        return ApiController::respondWithSuccess(new HotelRateBranchCollection($branches));
    }

    public function create(Request $request)
    {
        $hotel = $request->user();
        $rules = [
            'name_ar' => 'required|string|max:191',
            'name_en' => 'required|string|max:191',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        // create new Rate Branch
        $branch = HotelRateBranch::create([
            'hotel_id' => $hotel->id,
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
        ]);
        return ApiController::respondWithSuccess(new HotelRateBranchResource($branch));
    }

    public function edit(Request $request, $id)
    {
        $branch = HotelRateBranch::find($id);
        if ($branch) {
            $rules = [
                'name_ar' => 'nullable|string|max:191',
                'name_en' => 'nullable|string|max:191',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails())
                return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

            // update Rate Branch
            $branch->update([
                'name_ar' => $request->name_ar == null ? $branch->name_ar : $request->name_ar,
                'name_en' => $request->name_en == null ? $branch->name_en : $request->name_en,
            ]);
            return ApiController::respondWithSuccess(new HotelRateBranchResource($branch));
        } else {
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function show($id)
    {
        $branch = HotelRateBranch::find($id);
        if ($branch) {
            return ApiController::respondWithSuccess(new HotelRateBranchResource($branch));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function destroy($id)
    {
        $branch = HotelRateBranch::find($id);
        if ($branch) {
            $branch->delete();
            $success = [
                'message' => trans('messages.deleted')
            ];
            return ApiController::respondWithSuccess($success);
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function rates(Request $request)
    {
        $hotel = $request->user();
        $rates = $hotel->rates()->orderBy('id' , 'desc')->paginate();
        return ApiController::respondWithSuccess(new HotelRateCollection($rates));
    }
    public function destroy_rate($id)
    {
        $rate = HotelRate::find($id);
        if ($rate) {
            $rate->delete();
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
