<?php

namespace App\Models\Hotel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelNearServiceCategoryItemPhoto extends Model
{
    use HasFactory;
    protected $table = 'hotel_near_service_category_item_photos';
    protected $fillable = [
        'near_item_id',
        'photo',
    ];

    public function item()
    {
        return $this->belongsTo(HotelNearServiceCategoryItem::class , 'near_item_id');
    }
}
