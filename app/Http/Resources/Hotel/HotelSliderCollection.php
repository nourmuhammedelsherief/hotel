<?php

namespace App\Http\Resources\Hotel;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HotelSliderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'current_page' => $this->currentPage(),
            'data' => $this->collection->transform(function ($query){
                return [
                    'id'       => $query->id,
                    'hotel_id' => $query->hotel_id,
                    'type'     => $query->type,
                    'gif'      => $query->gif == null ? null : asset('/uploads/gifs/' . $query->gif),
                    'youtube'  => $query->youtube == null ? null : 'https://www.youtube.com/embed/'.$query->youtube,
                    'video'    => $query->video == null ? null : asset('uploads/slider_videos/' . $query->video),
                    'images'   => $query->type == 'image' ? HotelSliderImageResource::collection($query->images) : null,
                    'created_at' => $query->created_at->format('Y-m-d')
                ];
            }),
            'first_page_url' => $this->url(1),
            'from' => $this->firstItem(),
            'last_page' => $this->lastPage(),
            'last_page_url' => $this->url($this->lastPage()),
            'next_page_url' => $this->nextPageUrl(),
            'path' => $this->path(),
            'per_page' => $this->perPage(),
            'prev_page_url' => $this->previousPageUrl(),
            'to' => $this->lastItem(),
            'total' => $this->total(),
        ];
    }
}
