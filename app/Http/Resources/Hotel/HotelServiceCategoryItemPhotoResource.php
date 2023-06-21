<?php

namespace App\Http\Resources\Hotel;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelServiceCategoryItemPhotoResource extends JsonResource
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
            'hotel_service_item_id' => $this->hotel_service_item_id,
            'photo' => $this->photo == null ? null : asset('/uploads/service_slider_images/' . $this->photo)
        ];
    }
}
