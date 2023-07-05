<?php

namespace App\Http\Resources\Hotel;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HotelNearCategoryItemCollection extends ResourceCollection
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
                    'id'   => $query->id,
                    'hotel_near_cat_id' => $query->hotel_near_cat_id,
                    'name'   => app()->getLocale() == 'ar' ? $query->name_ar : $query->name_en,
                    'contact_number' => $query->contact_number,
                    'latitude' => $query->latitude,
                    'longitude' => $query->longitude,
                    'images'   => HotelNearCategoryItemPhotoResource::collection($query->photos),
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
