<?php

namespace App\Http\Controllers\Api\HotelController;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Hotel\HotelColorResource;
use App\Models\HotelColor;
use Illuminate\Http\Request;
use Spatie\FlareClient\Api;
use Validator;

class HotelColorController extends Controller
{
    public function add_colors(Request $request)
    {
        $hotel = $request->user();
        $rules = [
            'main_heads'           => 'nullable|string|max:191',
            'icons'                => 'nullable|string|max:191',
            'options_description'  => 'nullable|string|max:191',
            'background'           => 'nullable|string|max:191',
            'product_background'   => 'nullable|string|max:191',
            'category_background'  => 'nullable|string|max:191',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        // update or create hotel colors
        $color = HotelColor::updateOrCreate([
            'hotel_id' => $hotel->id,
        ] , [
            'main_heads'            => $request->main_heads,
            'icons'                 => $request->icons,
            'options_description'   => $request->options_description,
            'background'            => $request->background,
            'product_background'    => $request->product_background,
            'category_background'   => $request->category_background,
        ]);
        return ApiController::respondWithSuccess(new HotelColorResource($color));
    }

    public function get_hotel_colors(Request $request)
    {
        $hotel = $request->user();
        dd($hotel->color);
        return ApiController::respondWithSuccess(new HotelColorResource($hotel->color));
    }
}
