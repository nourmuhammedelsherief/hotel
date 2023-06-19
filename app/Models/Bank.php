<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Constraint\Count;

class Bank extends Model
{
    use HasFactory;

    protected $table = 'banks';
    protected $fillable = [
        'country_id',
        'name_ar',
        'name_en',
        'account_number',
        'iban_number',
        'useful',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class , 'country_id');
    }
}
