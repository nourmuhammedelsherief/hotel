<?php

namespace App\Models\Hotel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelNearServiceCategoryItem extends Model
{
    use HasFactory;
    protected $table = 'hotel_near_service_category_items';
    protected $fillable = [
        'hotel_near_cat_id',
        'name_ar',
        'name_en',
        'contact_number',
        'latitude',
        'longitude',
    ];

    public function category()
    {
        return $this->belongsTo(HotelNearServiceCategory::class , 'hotel_near_cat_id');
    }
    public function photos()
    {
        return $this->hasMany(HotelNearServiceCategoryItemPhoto::class , 'near_item_id');
    }
}
