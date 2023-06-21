<?php

namespace App\Http\Resources\Site;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelServiceCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'  => $this->id,
            'hotel_id' => $this->hotel_id,
            'hotel_service_id' => $this->hotel_service_id,
            'name' => app()->getLocale() == 'ar' ? $this->name_ar : $this->name_en,
            'photo' => $this->photo == null ? null : asset('/uploads/service_categories/'.$this->photo)
        ];
    }
}
