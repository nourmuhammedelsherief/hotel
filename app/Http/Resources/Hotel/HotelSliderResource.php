<?php

namespace App\Http\Resources\Hotel;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelSliderResource extends JsonResource
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
            'hotel_id' => $this->hotel_id,
            'type'     => $this->type,
            'gif'      => $this->gif == null ? null : asset('uploads/gifs/' . $this->gif),
            'youtube'  => $this->youtube == null ? null : 'https://www.youtube.com/embed/'.$this->youtube,
            'video'    => $this->video == null ? null : asset('uploads/slider_videos/' . $this->video),
            'images'   => $this->type == 'image' ? HotelSliderImageResource::collection($this->images) : null,
            'created_at' => $this->created_at->format('Y-m-d')
        ];
    }
}
