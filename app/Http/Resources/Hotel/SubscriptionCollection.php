<?php

namespace App\Http\Resources\Hotel;

use App\Http\Resources\Admin\BankResource;
use App\Http\Resources\Admin\CountryResource;
use App\Http\Resources\Admin\HotelResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SubscriptionCollection extends ResourceCollection
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
                    'id'             => $query->id,
                    'hotel_id'       => new HotelResource($query->hotel),
                    'branch_id'      => $query->branch_id,
                    'package_id'     => $query->package_id,
                    'seller_code_id' => $query->seller_code_id,
                    'bank_id'        => new BankResource($query->bank),
                    'type'           => $query->type,
                    'status'         => $query->status,
                    'amount'         => $query->amount,
                    'tax_value'      => $query->tax_value,
                    'discount_value' => $query->discount_value,
                    'subscription_type' => $query->subscription_type,
                    'transfer_photo' => $query->transfer_photo == null ? null : asset('/uploads/transfers/' . $query->transfer_photo),
                    'invoice_id'     => $query->invoice_id,
                    'paid_at'        => $query->paid_at == null ? null : $query->paid_at->format('Y-m-d H:i:s'),
                    'end_at'         => $query->end_at == null ? null : $query->end_at->format('Y-m-d'),
                    'payment_type'   => $query->payment_type,
                    'is_payment'     => $query->is_payment,
                    'created_at'     => $query->created_at->format('Y-m-d')
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
