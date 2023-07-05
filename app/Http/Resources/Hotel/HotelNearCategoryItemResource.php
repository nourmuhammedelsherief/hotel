<?php

namespace App\Http\Resources\Hotel;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelNearCategoryItemResource extends JsonResource
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
            'hotel_near_cat_id' => $this->hotel_near_cat_id,
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'contact_number' => $this->contact_number,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'images'   => HotelNearCategoryItemPhotoResource::collection($this->photos),
        ];
    }
}
