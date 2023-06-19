<?php

namespace App\Http\Resources\Hotel;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SiteHotelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'   => $this->id,
            'name' => app()->getLocale() == 'ar' ? $this->name_ar : $this->name_en,
            'subdomain' => $this->subdomain,
            'status'  => $this->status,
            'archive' => $this->archive,
            'logo' => $this->logo == null ? null : asset('uploads/logo/' . $this->logo),
            'country_id' => $this->country_id,
            'city_id' => $this->city_id,
            'package_id' => $this->package_id,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'latitude'   => $this->latitude,
            'longitude'  => $this->longitude,
            'lang'  => $this->lang,
            'tax'   => $this->tax,
            'tax_value' => $this->tax_value,
            'description' => app()->getLocale() == 'ar' ? $this->description_ar : $this->description_en,
            'views'   => $this->views,
            'api_token' => $this->api_token,
            'admin_activation' => $this->admin_activation,
            'phone_verified_at' => $this->phone_verified_at == null ? null : $this->phone_verified_at->format('Y-m-d H:i:s'),
            'created_at' => $this->created_at->format('Y-m-d')
        ];
    }
}
