<?php

namespace App\Http\Controllers\Api\Site;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Hotel\HotelGalleryCollection;
use App\Http\Resources\Hotel\HotelLocationCollection;
use App\Http\Resources\Hotel\HotelRateBranchCollection;
use App\Http\Resources\Hotel\HotelReservationCollection;
use App\Http\Resources\Hotel\HotelSliderResource;
use App\Http\Resources\Hotel\SiteHotelResource;
use App\Models\Hotel;
use App\Models\Hotel\HotelRate;
use App\Models\HotelSlider;
use Illuminate\Http\Request;
use Validator;

class HomeController extends Controller
{
    public function index($subdomain)
    {
        $hotel = Hotel::whereSubdomain($subdomain)->first();
        if ($hotel)
        {
            return ApiController::respondWithSuccess(new SiteHotelResource($hotel));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function sliders($subdomain)
    {
        $hotel = Hotel::whereSubdomain($subdomain)->first();
        if ($hotel)
        {
            $slider = HotelSlider::whereHotelId($hotel->id)->orderBy('id' , 'desc')->first();
            if ($slider)
            {
                return ApiController::respondWithSuccess(new HotelSliderResource($slider));
            }else{
                $error = ['message' => trans('messages.not_found')];
                return ApiController::respondWithErrorNOTFoundObject($error);
            }
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function reservations($subdomain)
    {
        $hotel = Hotel::whereSubdomain($subdomain)->first();
        if ($hotel)
        {
            $reservations = $hotel->reservations()->paginate();
            return ApiController::respondWithSuccess(new HotelReservationCollection($reservations));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function locations($subdomain)
    {
        $hotel = Hotel::whereSubdomain($subdomain)->first();
        if ($hotel)
        {
            $locations = $hotel->locations()->paginate();
            return ApiController::respondWithSuccess(new HotelLocationCollection($locations));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function photos($subdomain)
    {
        $hotel = Hotel::whereSubdomain($subdomain)->first();
        if ($hotel)
        {
            $locations = $hotel->photos()->paginate();
            return ApiController::respondWithSuccess(new HotelGalleryCollection($locations));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function rate_branches($subdomain)
    {
        $hotel = Hotel::whereSubdomain($subdomain)->first();
        if ($hotel)
        {
            $branches = $hotel->rate_branches()->paginate();
            return ApiController::respondWithSuccess(new HotelRateBranchCollection($branches));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function rate_hotel(Request $request , $subdomain)
    {
        $hotel = Hotel::whereSubdomain($subdomain)->first();
        if ($hotel)
        {
            $rules = [
                'rate_branch_id'  => 'required|exists:hotel_rate_branches,id',
                'name'            => 'required|string|max:191',
                'phone_number'    => 'required|min:8',
                'message'         => 'required|string',
                'food'            => 'required|in:1,2,3,4,5',
                'place'           => 'required|in:1,2,3,4,5',
                'service'         => 'required|in:1,2,3,4,5',
                'reception'       => 'required|in:1,2,3,4,5',
                'speed'           => 'required|in:1,2,3,4,5',
                'staff'           => 'required|in:1,2,3,4,5',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails())
                return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

            // create new rate
            $rate = HotelRate::create([
                'hotel_id'        => $hotel->id,
                'rate_branch_id'  => $request->rate_branch_id,
                'name'            => $request->name,
                'phone_number'    => $request->phone_number,
                'message'         => $request->message,
                'food'            => $request->food,
                'place'           => $request->place,
                'service'         => $request->service,
                'reception'       => $request->reception,
                'speed'           => $request->speed,
                'staff'           => $request->staff,
            ]);
            $success = [
                'message' => trans('messages.hotelRatedSuccessfully'),
            ];
            return ApiController::respondWithSuccess($success);
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
}
