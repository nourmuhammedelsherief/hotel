<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HotelCollection extends ResourceCollection
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
                    'name_ar' => $query->name_ar,
                    'name_en' => $query->name_en,
                    'subdomain' => $query->subdomain,
                    'status'  => $query->status,
                    'archive' => $query->archive,
                    'logo' => $query->logo == null ? null : asset('uploads/logo/' . $query->logo),
                    'country_id' => new CountryResource($query->country),
                    'city_id' => new CityResource($query->city),
                    'package_id' => $query->package_id,
                    'email' => $query->email,
                    'phone_number' => $query->phone_number,
                    'latitude'   => $query->latitude,
                    'longitude'  => $query->longitude,
                    'lang'  => $query->lang,
                    'tax'   => $query->tax,
                    'tax_value' => $query->tax_value,
                    'description_ar' => $query->description_ar,
                    'description_en' => $query->description_en,
                    'views'   => $query->views,
                    'api_token' => $query->api_token,
                    'admin_activation' => $query->admin_activation,
                    'phone_verified_at' => $query->phone_verified_at == null ? null :$query->phone_verified_at->format('Y-m-d H:i:s'),
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
