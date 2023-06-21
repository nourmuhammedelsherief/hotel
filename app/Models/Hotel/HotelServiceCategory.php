<?php

namespace App\Models\Hotel;

use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelServiceCategory extends Model
{
    use HasFactory;
    protected $table = 'hotel_service_categories';
    protected $fillable = [
        'hotel_id',
        'hotel_service_id',
        'name_ar',
        'name_en',
        'photo',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class , 'hotel_id');
    }
    public function service()
    {
        return $this->belongsTo(HotelService::class , 'hotel_service_id');
    }
}
