<?php

namespace App\Http\Resources\Hotel;

use App\Http\Resources\Admin\BankResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
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
            'hotel_id'       => $this->hotel_id,
            'branch_id'      => $this->branch_id,
            'package_id'     => $this->package_id,
            'seller_code_id' => $this->seller_code_id,
            'bank_id'        => new BankResource($this->bank),
            'type'           => $this->type,
            'status'         => $this->status,
            'amount'         => $this->amount,
            'tax_value'      => $this->tax_value,
            'discount_value' => $this->discount_value,
            'subscription_type' => $this->subscription_type,
            'transfer_photo' => $this->transfer_photo == null ? null : asset('public/uploads/transfers/' . $this->transfer_photo),
            'invoice_id'     => $this->invoice_id,
            'paid_at'        => $this->paid_at == null ? null : $this->paid_at->format('Y-m-d H:i:s'),
            'end_at'         => $this->end_at == null ? null : $this->end_at->format('Y-m-d'),
            'payment_type'   => $this->payment_type,
            'is_payment'     => $this->is_payment,
            'created_at'     => $this->created_at->format('Y-m-d')
        ];
    }
}
