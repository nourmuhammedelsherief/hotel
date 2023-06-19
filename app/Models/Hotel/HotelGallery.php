<?php

namespace App\Models\Hotel;

use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelGallery extends Model
{
    use HasFactory;
    protected $table = 'hotel_galleries';
    protected $fillable = [
        'hotel_id',
        'gallery_category_id',
        'photo'
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class , 'hotel_id');
    }
    public function gallery_category()
    {
        return $this->belongsTo(HotelGalleryCategory::class , 'gallery_category_id');
    }
}
