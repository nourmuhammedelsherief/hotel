<?php

namespace App\Http\Resources\Hotel;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelNearServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'hotel_id'         => $this->hotel_id,
            'name_ar'          => $this->name_ar,
            'name_en'          => $this->name_en,
            'description_ar'   => $this->description_ar,
            'description_en'   => $this->description_en,
            'icon'             => $this->icon == null ? null : asset('/uploads/icons/' . $this->icon)
        ];
    }
}
