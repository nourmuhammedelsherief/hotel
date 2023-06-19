<?php

namespace App\Http\Resources\Hotel;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HotelRateCollection extends ResourceCollection
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
                    'hotel_id' => intval($query->hotel_id),
                    'rate_branch_id' => $query->rate_branch_id,
                    'name'        => $query->name,
                    'phone_number' => $query->phone_number,
                    'message' => $query->message,
                    'food' => $query->food,
                    'place' => $query->place,
                    'service' => $query->service,
                    'reception' => $query->reception,
                    'speed' => $query->speed,
                    'staff' => $query->staff,
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
