<?php

namespace App\Models\Hotel;

use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelPixel extends Model
{
    use HasFactory;
    protected $table = 'hotel_pixels';
    protected $fillable = [
        'hotel_id',
        'pixel_name',
        'type',
        'top_head',
        'top_body',
        'after_head',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class , 'hotel_id');
    }
}
