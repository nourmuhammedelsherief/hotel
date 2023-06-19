<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelSlider extends Model
{
    use HasFactory;
    protected $table = 'hotel_sliders';
    protected $fillable = [
        'hotel_id',
        'type',
        'gif',
        'youtube',
        'video',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class , 'hotel_id');
    }
    public function images()
    {
        return $this->hasMany(HotelSliderImage::class , 'slider_id');
    }
}
