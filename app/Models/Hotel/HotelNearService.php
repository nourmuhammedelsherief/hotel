<?php

namespace App\Models\Hotel;

use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelNearService extends Model
{
    use HasFactory;
    protected $table = 'hotel_near_services';
    protected $fillable = [
        'hotel_id',
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'icon'
    ];
    public function hotel()
    {
        return $this->belongsTo(Hotel::class , 'hotel_id');
    }
}
