<?php

namespace App\Models\Hotel;

use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelNearServiceCategory extends Model
{
    use HasFactory;
    protected $table = 'hotel_near_service_categories';
    protected $fillable = [
        'hotel_id',
        'hotel_near_id',
        'name_ar',
        'name_en',
        'photo',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class , 'hotel_id');
    }
    public function near_service()
    {
        return $this->belongsTo(HotelNearService::class , 'hotel_near_id');
    }
}
