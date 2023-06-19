<?php

namespace App\Http\Resources\Hotel;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelSliderImageResource extends JsonResource
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
            'slider_id' => $this->slider_id,
            'image'  => $this->image == null ? null : asset('/uploads/slider_images/' . $this->image),
        ];
    }
}
