<?php

namespace App\Http\Resources\Site;

use App\Http\Resources\Hotel\HotelServiceCategoryItemPhotoResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SiteOurServiceCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'   => $this->id,
            'hotel_service_cat_id' => $this->hotel_service_cat_id,
            'name'         => app()->getLocale() == 'ar' ? $this->name_ar : $this->name_en,
            'description'  => app()->getLocale() == 'ar' ? $this->description_ar : $this->description_en,
            'sliders'         => HotelServiceCategoryItemPhotoResource::collection($this->sliders)
        ];
    }
}
