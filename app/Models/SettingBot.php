<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingBot extends Model
{
    use HasFactory;

    protected $table = 'setting_bot';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user',
        'symbol',
        'budget',
        'purchase_amount',
        'buy_percent',
        'sell_percent',
    ];
}
