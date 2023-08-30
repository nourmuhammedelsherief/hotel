<?php

namespace App\Http\Controllers\Api\Site;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Hotel\HotelContactResource;
use App\Http\Resources\Hotel\HotelGalleryCategoryCollection;
use App\Http\Resources\Hotel\HotelGalleryCollection;
use App\Http\Resources\Hotel\HotelLocationCollection;
use App\Http\Resources\Hotel\HotelPixelResource;
use App\Http\Resources\Hotel\HotelRateBranchCollection;
use App\Http\Resources\Hotel\HotelReservationCollection;
use App\Http\Resources\Hotel\HotelSliderResource;
use App\Http\Resources\Hotel\SiteHotelResource;
use App\Http\Resources\Site\SitePhotoGalleryIconResource;
use App\Models\Hotel;
use App\Models\Hotel\HotelRate;
use App\Models\Hotel\HotelGallery;
use App\Models\Hotel\HotelGalleryIcon;
use App\Models\Hotel\HotelGalleryCategory;
use App\Models\Hotel\HotelContact;
use App\Models\HotelSlider;
use App\Models\Hotel\HotelPixel;
use Illuminate\Http\Request;
use Validator;

class HomeController extends Controller
{
    public function index($subdomain)
    {
        $hotel = Hotel::whereSubdomain($subdomain)->first();
        if ($hotel)
        {
            if ($hotel->status == 'tentative_finished')
            {
                $errors = [
                    'message' => trans('messages.hotelSubscriptionTentativeFinished'),
                ];
                return ApiController::respondWithErrorObject(array($errors));
            }elseif ($hotel->status == 'finished')
            {
                $errors = [
                    'message' => trans('messages.hotelSubscriptionFinished'),
                ];
                return ApiController::respondWithErrorObject(array($errors));
            }elseif ($hotel->status == 'in_complete')
            {
                $errors = [
                    'message' => trans('messages.hotelInComplete'),
                ];
                return ApiController::respondWithErrorObject(array($errors));
            }elseif ($hotel->admin_activation == 'false')
            {
                $errors = [
                    'message' => trans('messages.hotelWaitAdminActivation'),
                ];
                return ApiController::respondWithErrorObject(array($errors));
            }
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
    public function gallery($subdomain)
    {
        $hotel = Hotel::whereSubdomain($subdomain)->first();
        if ($hotel)
        {
            $gallery_icon = HotelGalleryIcon::whereHotelId($hotel->id)->first();
            if ($gallery_icon == null)
            {
                // create new gallery icon
                $gallery_icon = HotelGalleryIcon::create([
                    'hotel_id'  => $hotel->id,
                    'name_ar'   => 'معرض الصور',
                    'name_en'   => 'photo gallery',
                    'icon'      => 'photo_icon.png'
                ]);
            }
            return ApiController::respondWithSuccess(new SitePhotoGalleryIconResource($gallery_icon));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function gallery_categories($subdomain)
    {
        $hotel = Hotel::whereSubdomain($subdomain)->first();
        if ($hotel)
        {
            $categories = HotelGalleryCategory::whereHotelId($hotel->id)->paginate();
            return ApiController::respondWithSuccess(new HotelGalleryCategoryCollection($categories));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function photos($subdomain , $id)
    {
        $hotel = Hotel::whereSubdomain($subdomain)->first();
        if ($hotel)
        {
            $photos = HotelGallery::whereHotelId($hotel->id)
                ->where('gallery_category_id' , $id)
                ->paginate();
            return ApiController::respondWithSuccess(new HotelGalleryCollection($photos));
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
    public function contact_us($subdomain)
    {
        $hotel = Hotel::whereSubdomain($subdomain)->first();
        if ($hotel)
        {
            $contact = HotelContact::whereHotelId($hotel->id)->first();
            if ($contact)
            {
                return ApiController::respondWithSuccess(new HotelContactResource($contact));
            }else{
                $error = ['message' => trans('messages.not_found')];
                return ApiController::respondWithErrorNOTFoundObject($error);
            }
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function pixel_codes($subdomain)
    {
        $hotel = Hotel::whereSubdomain($subdomain)->first();
        if ($hotel)
        {
            $codes = HotelPixel::whereHotelId($hotel->id)->get();
            return ApiController::respondWithSuccess(HotelPixelResource::collection($codes));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
}
