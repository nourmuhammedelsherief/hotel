<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use PHPUnit\Framework\Constraint\Count;

class SellerCodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'marketer_id'      => new MarketerResource($this->marketer),
            'country_id'       => new CountryResource($this->country),
            'package_id'       => $this->package_id,
            'seller_name'      => $this->seller_name,
            'permanent'        => $this->permanent,
            'status'           => $this->status,
            'percentage'       => $this->percentage,
            'code_percentage'  => $this->code_percentage,
            'commission'       => $this->commission,
            'start_at'         => $this->start_at->format('Y-m-d'),
            'end_at'           => $this->end_at->format('Y-m-d'),
            'type'             => $this->type,
        ];
    }
}
