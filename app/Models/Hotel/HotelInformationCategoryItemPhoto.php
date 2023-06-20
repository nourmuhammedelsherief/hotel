<?php

namespace App\Models\Hotel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelInformationCategoryItemPhoto extends Model
{
    use HasFactory;
    protected $table = 'hotel_information_category_item_photos';
    protected $fillable = [
        'info_item_id',
        'photo'
    ];

    public function hotel_info_category_item()
    {
        return $this->belongsTo(HotelInformationCategoryItem::class , 'hotel_info_category_item_id');
    }
}
