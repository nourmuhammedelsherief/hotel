<?php

namespace App\Models\Hotel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelServiceCategoryItemPhoto extends Model
{
    use HasFactory;
    protected $table = 'hotel_service_category_item_photos';
    protected $fillable = [
        'hotel_service_item_id',
        'photo'
    ];

    public function item()
    {
        return $this->belongsTo(HotelServiceCategoryItem::class , 'hotel_service_item_id');
    }
}
