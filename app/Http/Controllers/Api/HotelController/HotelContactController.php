<?php

namespace App\Http\Controllers\Api\HotelController;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Hotel\HotelContactResource;
use App\Models\Hotel;
use App\Models\Hotel\HotelContact;
use Illuminate\Http\Request;
use Validator;

class HotelContactController extends Controller
{
    public function show(Request $request)
    {
        $hotel = $request->user();
        $contact = HotelContact::whereHotelId($hotel->id)->first();
        if ($contact == null)
        {
            // create new contact
            $contact = HotelContact::create([
                'hotel_id'   => $hotel->id,
            ]);
        }
        return ApiController::respondWithSuccess(new HotelContactResource($contact));
    }
    public function edit(Request $request , $id)
    {
        $contact = HotelContact::find($id);
        if ($contact)
        {
            $rules = [
                'email'    => 'nullable|email|max:191',
                'phone'     => 'nullable|min:8',
                'address'   => 'nullable|string|max:191',
                'twitter'   => 'nullable|max:191',
                'instagram' => 'nullable|max:191',
                'snapchat'  => 'nullable|max:191',
                'site'      => 'nullable|max:191',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails())
                return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

            $contact->update([
                'email'     => $request->email == null ? $contact->email : $request->email,
                'phone'     => $request->phone == null ? $contact->phone : $request->phone,
                'address'   => $request->address == null ? $contact->address : $request->address,
                'twitter'   => $request->twitter  == null ? $contact->twitter : $request->twitter,
                'instagram' => $request->instagram == null ? $contact->instagram : $request->instagram,
                'snapchat'  => $request->snapchat == null ? $contact->snapchat : $request->snapchat,
                'site'      => $request->site == null ? $contact->site : $request->site,
            ]);
            return ApiController::respondWithSuccess(new HotelContactResource($contact));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
}
