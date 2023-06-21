<?php

namespace App\Http\Controllers\Api\Site;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Site\HotelInfoCategoryItemCollection;
use App\Http\Resources\Site\HotelServiceCategoryResource;
use App\Http\Resources\Site\HotelServiceResource;
use App\Models\Hotel;
use App\Models\Hotel\HotelService;
use App\Models\Hotel\HotelServiceCategory;
use App\Models\Hotel\HotelServiceCategoryItem;
use Illuminate\Http\Request;

class HotelOurServiceController extends Controller
{
    public function our_services($subdomain)
    {
        $hotel = Hotel::whereSubdomain($subdomain)->first();
        if ($hotel)
        {
            $service = HotelService::whereHotelId($hotel->id)->first();
            if ($service == null)
            {
                // create new Service
                $service = HotelService::create([
                    'hotel_id'   => $hotel->id,
                    'name_ar'    => 'خدماتنا',
                    'name_en'    => 'Our Services',
                    'description_ar' => 'وصف تعبيري عن قسم خدماتنا',
                    'description_en' => 'Description About Service Category',
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
    public function our_services_categories($subdomain)
    {
        $hotel = Hotel::whereSubdomain($subdomain)->first();
        if ($hotel)
        {
            $categories = HotelServiceCategory::whereHotelId($hotel->id)->get();
            return ApiController::respondWithSuccess(HotelServiceCategoryResource::collection($categories));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function our_services_category_items($subdomain , $id)
    {
        $hotel = Hotel::whereSubdomain($subdomain)->first();
        if ($hotel)
        {
            $category = HotelServiceCategory::find($id);
            if ($category)
            {
                $items = HotelServiceCategoryItem::where('hotel_service_cat_id',$category->id)->paginate();
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
