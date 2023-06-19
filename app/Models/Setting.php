<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';
    protected $fillable = [
        'bearer_token',
        'sender_name',
        'contact_number',
        'technical_support_number',
        'active_whatsapp_number',
        'tentative_period',
        'tax',
        'online_token',
    ];
}
