<?php

namespace App\Models\Hotel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelInformationCategoryItem extends Model
{
    use HasFactory;
    protected $table = 'hotel_information_category_items';
    protected $fillable = [
        'hotel_info_category_id',
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
    ];
    public function hotel_info_category()
    {
        return $this->belongsTo(HotelInformationCategory::class , 'hotel_info_category_id');
    }
    public function sliders()
    {
        return $this->hasMany(HotelInformationCategoryItemPhoto::class , 'info_item_id');
    }
}
