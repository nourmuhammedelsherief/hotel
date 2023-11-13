<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HistoryCollection extends ResourceCollection
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
                    'id'    => $query->id,
                    'hotel_id' => new HotelResource($query->hotel),
                    'package_id' => $query->package_id,
                    'branch_id' => $query->branch_id,
                    'bank_id' => $query->bank_id,
                    'status'  => $query->status,
                    'type'   => $query->type,
                    'payment_type' => $query->payment_type,
                    'details'  => $query->details,
                    'transfer_photo' => $query->transfer_photo == null ? null : asset('/uploads/transfers/' . $query->transfer_photo),
                    'invoice_id' => $query->invoice_id,
                    'operation_date' => $query->operation_date?->format('Y-m-d'),
                    'price'        => doubleval(number_format((float)$query->hotel->country->rial_price, 2, '.', '')),
                    'discount_value' => doubleval(number_format((float)$query->discount_value, 2, '.', '')),
                    'tax_value'  => doubleval(number_format((float)$query->tax_value, 2, '.', '')),
                    'total_amount' => doubleval(number_format((float)$query->paid_amount, 2, '.', '')),
                    'accepted_admin_name' => $query->accepted_admin_name,
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
