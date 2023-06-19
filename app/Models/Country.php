<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $table = 'countries';
    protected $fillable = [
        'name_ar',
        'name_en',
        'currency_ar',
        'currency_en',
        'code',
        'currency_code',
        'flag',
    ];
}
