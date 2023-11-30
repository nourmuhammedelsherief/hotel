<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelColor extends Model
{
    use HasFactory;

    protected $table = 'hotel_colors';
    protected $fillable = [
        'hotel_id',
        'main_heads',
        'icons',
        'options_description',
        'background',
        'product_background',
        'category_background',
    ];
    public function hotel()
    {
        return $this->belongsTo(Hotel::class , 'hotel_id');
    }
}
