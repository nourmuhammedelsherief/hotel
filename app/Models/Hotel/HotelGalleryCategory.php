<?php

namespace App\Models\Hotel;

use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelGalleryCategory extends Model
{
    use HasFactory;
    protected $table = 'hotel_gallery_categories';
    protected $fillable = [
        'hotel_id',
        'name_ar',
        'name_en',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class , 'hotel_id');
    }
}
