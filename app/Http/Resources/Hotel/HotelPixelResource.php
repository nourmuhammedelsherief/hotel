<?php

namespace App\Http\Resources\Hotel;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelPixelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'hotel_id'   => $this->hotel_id,
            'pixel_name' => $this->pixel_name,
            'type'       => $this->type,
            'top_head'   => $this->top_head,
            'top_body'   => $this->top_body,
            'after_head' => $this->after_head,
        ];
    }
}
