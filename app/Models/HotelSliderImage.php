<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelSliderImage extends Model
{
    use HasFactory;
    protected $table = 'hotel_slider_images';
    protected $fillable = [
        'slider_id',
        'image',
    ];
    public function slider()
    {
        return $this->belongsTo(HotelSlider::class , 'slider_id');
    }
}
