<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;
    protected $table = 'subscriptions';
    protected $fillable = [
        'hotel_id',
        'branch_id',
        'package_id',
        'seller_code_id',
        'bank_id',
        'type',
        'status',
        'amount',
        'tax_value',
        'discount_value',
        'subscription_type',
        'transfer_photo',
        'invoice_id',
        'paid_at',
        'end_at',
        'payment_type',
        'is_payment',
    ];

    protected $casts = [
        'paid_at'  => 'datetime',
        'end_at'   => 'datetime',
    ];
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
    public function seller_code()
    {
        return $this->belongsTo(SellerCode::class , 'seller_code_id');
    }
    public function bank()
    {
        return $this->belongsTo(Bank::class , 'bank_id');
    }
}
