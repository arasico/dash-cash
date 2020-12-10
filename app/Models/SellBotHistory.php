<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellBotHistory extends Model
{
    use HasFactory;

    protected $table = 'sell_bot_history';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user',
        'symbol',
        'amount',
        'price',
        'total',
        'sell_amount',
        'sell_price',
        'sell_total',
        'profit_percent',
        'price_change_percent_buy',
        'price_change_percent_sell',
    ];
}
