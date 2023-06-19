<?php

namespace App\Models\Hotel;

use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelRate extends Model
{
    use HasFactory;

    protected $table = 'hotel_rates';
    protected $fillable = [
        'hotel_id',
        'rate_branch_id',
        'name',
        'phone_number',
        'message',
        'food',
        'place',
        'service',
        'reception',
        'speed',
        'staff',
    ];
    public function hotel()
    {
        return $this->belongsTo(Hotel::class , 'hotel_id');
    }
    public function rate_branch()
    {
        return $this->belongsTo(HotelRateBranch::class , 'rate_branch_id');
    }
}
