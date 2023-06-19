<?php

namespace App\Http\Resources\Hotel;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelRateBranchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'     => $this->id,
            'hotel_id' => $this->hotel_id,
            'name_ar'  => $this->name_ar,
            'name_en'  => $this->name_en,
            'created_at' => $this->created_at->format('Y-m-d')
        ];
    }
}
