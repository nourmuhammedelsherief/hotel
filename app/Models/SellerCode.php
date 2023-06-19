<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerCode extends Model
{
    use HasFactory;

    protected $table = 'seller_codes';
    protected $fillable = [
        'marketer_id',
        'country_id',
        'package_id',
        'seller_name',
        'permanent',
        'status',
        'percentage',
        'code_percentage',
        'commission',
        'start_at',
        'end_at',
        'type',
    ];

    protected $casts = [ 'start_at'=>'datetime' , 'end_at'=>'datetime'];

    public function marketer()
    {
        return $this->belongsTo(Marketer::class , 'marketer_id');
    }
    public function country()
    {
        return $this->belongsTo(Country::class , 'country_id');
    }
    public function package()
    {
        return $this->belongsTo(Package::class , 'package_id');
    }
}
