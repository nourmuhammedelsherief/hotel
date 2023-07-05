<?php

namespace App\Http\Resources\Hotel;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelNearCategoryItemPhotoResource extends JsonResource
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
            'near_item_id' => $this->near_item_id,
            'photo' => $this->photo == null ? null : asset('/uploads/near_service_images/' . $this->photo)
        ];
    }
}
