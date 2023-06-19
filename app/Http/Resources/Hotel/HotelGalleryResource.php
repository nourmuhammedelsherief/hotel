<?php

namespace App\Http\Resources\Hotel;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelGalleryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'hotel_id' => intval($this->hotel_id),
            'gallery_category_id' => intval($this->gallery_category_id),
            'photo' => $this->photo == null ? null : asset('/uploads/galleries/' . $this->photo),
            'created_at' => $this->created_at->format('Y-m-d')
        ];
    }
}
