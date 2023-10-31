<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'  => $this->id,
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'currency_ar' => $this->currency_ar,
            'currency_en' => $this->currency_en,
            'code'  => $this->code,
            'currency_code' => $this->currency_code,
            'subscription_price' => intval($this->subscription_price),
            'rial_price' => intval($this->rial_price),
            'active' => $this->active,
            'flag'  => $this->flag == null ? null : asset('uploads/flags/' . $this->flag),
        ];
    }
}
