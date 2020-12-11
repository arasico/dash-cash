<?php

namespace App\Http\Controllers;

use App\Models\BuyBot;
use App\Models\SellBotHistory;
use App\Models\SettingBot;
use Illuminate\Http\Request;

class CoinsBotDefaultBoxController extends Controller
{
    public function index(Request $request)
    {
        $settingBots = SettingBot::all();
        $coinsBox = array();
        $all_profit = 0;
        foreach ($settingBots as $key => $value) {
            $profit_percent = SellBotHistory::where([
                'user' => $value->user,
                'symbol' => $value->symbol,
            ])->sum('profit_percent');
            $from = date('Y-m-d 00:00:00');
            $to = date('Y-m-d 23:59:59');
            $profit_percent_daily = SellBotHistory::where([
                'user' => $value->user,
                'symbol' => $value->symbol,
            ])->whereBetween('created_at', [$from, $to])->sum('profit_percent');
            $all_profit += $profit_percent;
            $coinsBox[$key] = array_merge($value->toArray(), [
                'profit_percent' => $profit_percent,
                'profit_percent_daily' => $profit_percent_daily,
                'buyBot' => BuyBot::where([
                    'user' => $value->user,
                    'symbol' => $value->symbol,
                ])->count()
            ]);
        }
        return view('coinsBotDefaultBox', [
            'coinsBox' => $coinsBox,
            'allProfit' => $all_profit,
            'buyBot' => BuyBot::count()
        ]);
    }
}
