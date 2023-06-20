<?php

namespace App\Http\Controllers\Api\Site;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Hotel\HotelInfoCategoryItemCollection;
use App\Http\Resources\Hotel\HotelInfoCategoryResource;
use App\Http\Resources\Hotel\HotelInformationResource;
use App\Models\Hotel;
use App\Models\Hotel\HotelInformation;
use App\Models\Hotel\HotelInformationCategory;
use App\Models\Hotel\HotelInformationCategoryItem;
use Illuminate\Http\Request;

class HotelInformationController extends Controller
{
    public function information_about_us($subdomain)
    {
        $hotel = Hotel::whereSubdomain($subdomain)->first();
        if ($hotel)
        {
            $info = HotelInformation::whereHotelId($hotel->id)->first();
            if ($info == null)
            {
                // create new Information
                $info = HotelInformation::create([
                    'hotel_id'   => $hotel->id,
                    'name_ar'    => 'معلومات عنا',
                    'name_en'    => 'Information About Us',
                    'description_ar' => 'وصف تعبيري عن قسم معلومات عنا',
                    'description_en' => 'Description About Information Category',
                    'icon'           => 'icon.png'
                ]);
            }
            if ($info)
            {
                return ApiController::respondWithSuccess(new HotelInformationResource($info));
            }else{
                $error = ['message' => trans('messages.not_found')];
                return ApiController::respondWithErrorNOTFoundObject($error);
            }
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }

    public function information_categories($subdomain)
    {
        $hotel = Hotel::whereSubdomain($subdomain)->first();
        if ($hotel)
        {
            $categories = HotelInformationCategory::whereHotelId($hotel->id)->get();
            return ApiController::respondWithSuccess(HotelInfoCategoryResource::collection($categories));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function information_category_items($subdomain , $id)
    {
        $hotel = Hotel::whereSubdomain($subdomain)->first();
        if ($hotel)
        {
            $category = HotelInformationCategory::find($id);
            if ($category)
            {
                $items = HotelInformationCategoryItem::where('hotel_info_category_id',$category->id)->paginate();
                return ApiController::respondWithSuccess(new HotelInfoCategoryItemCollection($items));
            }else{
                $error = ['message' => trans('messages.not_found')];
                return ApiController::respondWithErrorNOTFoundObject($error);
            }
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
}
