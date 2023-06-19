<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BankResource extends JsonResource
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
            'country' => new CountryResource($this->country),
            'name_ar'   => $this->name_ar,
            'name_en'   => $this->name_en,
            'account_number' => $this->account_number,
            'iban_number'  => $this->iban_number,
            'useful'     => $this->useful,
        ];
    }
}
