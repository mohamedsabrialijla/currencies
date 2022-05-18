<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    use HasFactory;

    protected $fillable = [
        'symbol', 'side', 'type', 'price', 'quantity', 'stop_price', 'price_change_percent',
        'order_id', 'order_status', 'response', 'commission','last_price','new_price'
    ];

    protected $casts = [
        'price' => 'float',
        'quantity' => 'float',
        'stop_price' => 'float',
        'price_change_percent' => 'float',
        'commission' => 'float',
        'response' => 'json',
        //'order_id' => 'int',
    ];
}
