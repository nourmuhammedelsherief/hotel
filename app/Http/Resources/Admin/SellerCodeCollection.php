<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SellerCodeCollection extends ResourceCollection
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
                    'id'               => $query->id,
                    'marketer_id'      => new MarketerResource($query->marketer),
                    'country_id'       => new CountryResource($query->country),
                    'package_id'       => $query->package_id,
                    'seller_name'      => $query->seller_name,
                    'permanent'        => $query->permanent,
                    'status'           => $query->status,
                    'percentage'       => $query->percentage,
                    'code_percentage'  => $query->code_percentage,
                    'commission'       => $query->commission,
                    'start_at'         => $query->start_at->format('Y-m-d'),
                    'end_at'           => $query->end_at->format('Y-m-d'),
                    'type'             => $query->type,
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
