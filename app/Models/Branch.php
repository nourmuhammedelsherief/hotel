<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Constraint\Count;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'branches';
    protected $fillable = [
        'hotel_id',
        'country_id',
        'city_id',
        'status',
        'main',
        'archive',
        'name_ar',
        'name_en',
        'subdomain',
        'email',
        'phone_number',
        'password',
        'latitude',
        'longitude',
        'views',
        'tax',
        'tax_value',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class , 'hotel_id');
    }
    public function country()
    {
        return $this->belongsTo(Count::class , 'country_id');
    }
    public function city()
    {
        return $this->belongsTo(City::class , 'city_id');
    }
}
