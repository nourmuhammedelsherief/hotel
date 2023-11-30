<?php

namespace App\Http\Resources\Hotel;

use App\Http\Resources\Admin\HotelResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelColorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'hotel_id'              => new HotelResource($this->hotel),
            'main_heads'            => $this->main_heads,
            'icons'                 => $this->icons,
            'options_description'   => $this->options_description,
            'background'            => $this->background,
            'product_background'    => $this->product_background,
            'category_background'   => $this->category_background,
        ];
    }
}
