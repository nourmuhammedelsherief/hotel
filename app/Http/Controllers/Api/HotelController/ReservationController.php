<?php

namespace App\Http\Controllers\Api\HotelController;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Hotel\HotelReservationCollection;
use App\Http\Resources\Hotel\HotelReservationResource;
use App\Models\Hotel\HotelReservation;
use Illuminate\Http\Request;
use Spatie\FlareClient\Api;
use Validator;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $hotel = $request->user();
        $reservations = HotelReservation::whereHotelId($hotel->id)->orderBy('id' , 'desc')->paginate();
        return ApiController::respondWithSuccess(new HotelReservationCollection($reservations));
    }

    public function create(Request $request)
    {
        $hotel = $request->user();
        $rules = [
            'name_ar'  => 'required|string|max:191',
            'name_en'  => 'required|string|max:191',
            'link'     => 'required|max:191',
            'photo'    => 'required|mimes:jpg,jpeg,png,gif,tif,psd,webp,pmp|max:5000',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        // create new reservation
        $reservation = HotelReservation::create([
            'hotel_id'  => $hotel->id,
            'name_ar'   => $request->name_ar,
            'name_en'   => $request->name_en,
            'link'      => $request->link,
            'photo'     => $request->file('photo') == null ? null : UploadImage($request->file('photo') , 'photo' , '/uploads/reservations'),
        ]);
        return ApiController::respondWithSuccess(new HotelReservationResource($reservation));
    }
    public function edit(Request $request , $id)
    {
        $reservation =HotelReservation::find($id);
        if ($reservation)
        {
            $hotel = $request->user();
            $rules = [
                'name_ar'  => 'nullable|string|max:191',
                'name_en'  => 'nullable|string|max:191',
                'link'     => 'nullable|max:191',
                'photo'    => 'nullable|mimes:jpg,jpeg,png,gif,tif,psd,webp,pmp|max:5000',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails())
                return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

            // update reservation
            $reservation->update([
                'name_ar'   => $request->name_ar == null ? $reservation->name_ar : $request->name_ar,
                'name_en'   => $request->name_en == null ? $reservation->name_en : $request->name_en,
                'link'      => $request->link == null ? $reservation->link : $request->link,
                'photo'     => $request->file('photo') == null ? $reservation->photo : UploadImageEdit($request->file('photo') , 'photo' , '/uploads/reservations' , $reservation->photo),
            ]);
            return ApiController::respondWithSuccess(new HotelReservationResource($reservation));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }

    public function show($id)
    {
        $reservation =HotelReservation::find($id);
        if ($reservation)
        {
            return ApiController::respondWithSuccess(new HotelReservationResource($reservation));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function destroy($id)
    {
        $reservation =HotelReservation::find($id);
        if ($reservation)
        {
            if ($reservation->photo != null)
            {
                @unlink(public_path('/uploads/reservations/' . $reservation->photo));
            }
            $reservation->delete();
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
