<?php

namespace App\Http\Controllers\Api\Site;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Hotel\HotelNearCategoryItemCollection;
use App\Http\Resources\Site\HotelNearCategoryResource;
use App\Http\Resources\Site\HotelServiceResource;
use App\Models\Hotel;
use App\Models\Hotel\HotelNearService;
use App\Models\Hotel\HotelNearServiceCategory;
use App\Models\Hotel\HotelNearServiceCategoryItem;
use Illuminate\Http\Request;

class HotelNearServiceController extends Controller
{
    public function near_services($subdomain)
    {
        $hotel = Hotel::whereSubdomain($subdomain)->first();
        if ($hotel)
        {
            $service = HotelNearService::whereHotelId($hotel->id)->first();
            if ($service == null)
            {
                // create new near Service
                $service = HotelNearService::create([
                    'hotel_id'   => $hotel->id,
                    'name_ar'    => 'الخدمات القريبة',
                    'name_en'    => 'Near Services',
                    'description_ar' => 'وصف تعبيري عن قسم الخدمات القريبة',
                    'description_en' => 'Description About Near Service Category',
                    'icon'           => 'service.png'
                ]);
            }
            if ($service)
            {
                return ApiController::respondWithSuccess(new HotelServiceResource($service));
            }else{
                $error = ['message' => trans('messages.not_found')];
                return ApiController::respondWithErrorNOTFoundObject($error);
            }
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function near_services_categories($subdomain)
    {
        $hotel = Hotel::whereSubdomain($subdomain)->first();
        if ($hotel)
        {
            $categories = HotelNearServiceCategory::whereHotelId($hotel->id)->get();
            return ApiController::respondWithSuccess(HotelNearCategoryResource::collection($categories));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function near_services_category_items($subdomain , $id)
    {
        $hotel = Hotel::whereSubdomain($subdomain)->first();
        if ($hotel)
        {
            $category = HotelNearServiceCategory::find($id);
            if ($category)
            {
                $items = HotelNearServiceCategoryItem::where('hotel_near_cat_id',$category->id)->paginate();
                return ApiController::respondWithSuccess(new HotelNearCategoryItemCollection($items));
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
