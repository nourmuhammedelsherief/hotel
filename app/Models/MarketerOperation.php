<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketerOperation extends Model
{
    use HasFactory;
    protected $table = 'marketer_operations';
    protected $fillable = [
        'hotel_id',
        'marketer_id',
        'subscription_id',
        'seller_code_id',
        'status',
        'amount',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class , 'hotel_id');
    }
    public function marketer()
    {
        return $this->belongsTo(Marketer::class , 'marketer_id');
    }
    public function subscription()
    {
        return $this->belongsTo(Subscription::class , 'subscription_id');
    }
    public function seller_code()
    {
        return $this->belongsTo(SellerCode::class , 'seller_code_id');
    }
}
