<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Constraint\Count;

class City extends Model
{
    use HasFactory;
    protected $table = 'cities';
    protected $fillable = [
        'country_id',
        'name_ar',
        'name_en',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class , 'country_id');
    }
}
