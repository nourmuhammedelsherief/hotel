<?php

namespace App\Models\Hotel;

use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelContact extends Model
{
    use HasFactory;
    protected $table = 'hotel_contacts';
    protected $fillable = [
        'hotel_id',
        'email',
        'phone',
        'address',
        'twitter',
        'instagram',
        'snapchat',
        'site',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class , 'hotel_id');
    }
}
