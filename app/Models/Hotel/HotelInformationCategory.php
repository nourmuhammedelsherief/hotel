<?php

namespace App\Models\Hotel;

use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelInformationCategory extends Model
{
    use HasFactory;
    protected $table = 'hotel_information_categories';
    protected $fillable = [
        'hotel_id',
        'hotel_information_id',
        'name_ar',
        'name_en',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class , 'hotel_id');
    }
    public function hotel_information()
    {
        return $this->belongsTo(HotelInformation::class , 'hotel_information_id');
    }
}
