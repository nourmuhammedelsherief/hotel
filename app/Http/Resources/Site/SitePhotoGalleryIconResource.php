<?php

namespace App\Http\Resources\Site;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SitePhotoGalleryIconResource extends JsonResource
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
            'hotel_id' => $this->hotel_id,
            'name' => app()->getLocale() == 'ar' ? $this->name_ar : $this->name_en,
            'photo'  => $this->icon == null ? null : asset('/uploads/icons/'.$this->icon),
        ];
    }
}
