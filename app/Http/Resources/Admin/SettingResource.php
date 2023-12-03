<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'    => $this->id,
            'bearer_token' => $this->bearer_token,
            'sender_name' => $this->sender_name,
            'contact_number' => $this->contact_number,
            'technical_support_number' => $this->technical_support_number,
            'active_whatsapp_number' => $this->active_whatsapp_number,
            'tentative_period'  => $this->tentative_period,
            'tax'  => $this->tax,
            'online_token'  => $this->online_token,
            'online_payment'    => $this->online_payment,
            'edfa_merchant_key' => $this->edfa_merchant_key,
            'edfa_password'     => $this->edfa_password,
        ];
    }
}
