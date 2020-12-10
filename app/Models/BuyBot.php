<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyBot extends Model
{
    use HasFactory;

    protected $table = 'buy_bot';
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
        'price_change_percent_buy',
    ];
}







