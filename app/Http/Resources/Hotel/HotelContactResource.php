<?php

namespace App\Http\Resources\Hotel;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelContactResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'hotel_id'  => $this->hotel_id,
            'email'     => $this->email,
            'phone'     => $this->phone,
            'address'   => $this->address,
            'twitter'   => $this->twitter,
            'instagram' => $this->instagram,
            'snapchat'  => $this->snapchat,
            'site'      => $this->site,
        ];
    }
}
