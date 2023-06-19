<?php

namespace App\Models\Hotel;

use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelReservation extends Model
{
    use HasFactory;
    protected $table = 'hotel_reservations';
    protected $fillable = [
        'hotel_id',
        'name_ar',
        'name_en',
        'link',
        'photo',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class , 'hotel_id');
    }

}
