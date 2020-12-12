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
        $all_profit_percent_daily = 0;
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
            $all_profit_percent_daily += $profit_percent_daily;
            $coinsBox[$key] = array_merge($value->toArray(), [
                'profit_percent' => $profit_percent,
                'profit_percent_daily' => $profit_percent_daily,
                'buyBot' => BuyBot::where([
                    'user' => $value->user,
                    'symbol' => $value->symbol,
                ])->count(),
                'count_all' => SellBotHistory::where([
                    'user' => $value->user,
                    'symbol' => $value->symbol,
                ])->count()
            ]);
        }
        return view('coinsBotDefaultBox', [
            'coinsBox' => $coinsBox,
            'allProfit' => $all_profit,
            'profitPercentDaily' => $all_profit_percent_daily,
            'buyBot' => BuyBot::count()
        ]);
    }

    public function storeSetting(Request $request)
    {
        if (!SettingBot::where('symbol', $request->input('symbol'))->first()) {
            SettingBot::create([
                'user' => 'yaser',
                'symbol' => $request->input('symbol'),
                'budget' => $request->input('budget'),
                'purchase_amount' => $request->input('purchase_amount'),
                'buy_percent' => $request->input('buy_percent'),
                'sell_percent' => $request->input('sell_percent'),
            ]);
            return redirect('/coin/bot/box');
        }
        return redirect('/coin/bot/box/setting');
    }

    public function dashboardBot(Request $request)
    {
        $buyBot = BuyBot::all();
        $endpoint = "https://api.binance.com/api/v3/ticker/24hr";
        $client = new \GuzzleHttp\Client();
        $coinsBox = array();
        $all_profit = 0;
        $all_profit_percent = 0;
        foreach ($buyBot as $key => $value) {
            $response = $client->request('GET', $endpoint, ['query' => [
                'symbol' => $value['symbol']
            ]]);
            $content = json_decode($response->getBody(), true);
            $amount = $value['amount'];
            $binanceResult = [
                'current_price' => $content['lastPrice'],
                'current_total' => ($amount * $content['lastPrice']),
                'profit' => ($amount * $content['lastPrice']) - $value['total'],
                'profit_percent' => ((($amount * $content['lastPrice']) - $value['total']) / $value['total']) * 100,
                'price_change_percent' => $content['priceChangePercent']
            ];
            $coinsBox[$key] = array_merge($value->toArray(), $binanceResult);
            $all_profit += $binanceResult['profit'];
            $all_profit_percent += $binanceResult['profit_percent'];
        }
        return view('dashboardBotCoin', [
            'coinsBox' => $coinsBox,
            'allProfit' => $all_profit,
            'allProfitPercent' => $all_profit_percent,
        ]);
    }
}
