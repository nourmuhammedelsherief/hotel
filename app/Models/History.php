<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;
    protected $table = 'histories';
    protected $fillable = [
        'hotel_id',
        'package_id',
        'branch_id',
        'bank_id',
        'status',
        'type',
        'payment_type',
        'details',
        'transfer_photo',
        'invoice_id',
        'operation_date',
        'price',
        'paid_amount',
        'discount_value',
        'tax_value',
    ];

    protected $casts = ['operation_date' => 'datetime'];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class , 'hotel_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class , 'branch_id');
    }
    public function package()
    {
        return $this->belongsTo(Package::class , 'package_id');
    }
    public function bank()
    {
        return $this->belongsTo(Bank::class , 'bank_id');
    }
}
