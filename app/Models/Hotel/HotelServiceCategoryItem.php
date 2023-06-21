<?php

namespace App\Models\Hotel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelServiceCategoryItem extends Model
{
    use HasFactory;
    protected $table = 'hotel_service_category_items';
    protected $fillable = [
        'hotel_service_cat_id',
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
    ];

    public function category()
    {
        return $this->belongsTo(HotelServiceCategory::class , 'hotel_service_cat_id');
    }
    public function sliders()
    {
        return $this->hasMany(HotelServiceCategoryItemPhoto::class , 'hotel_service_item_id');
    }
}
