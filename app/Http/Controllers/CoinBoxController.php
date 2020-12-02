<?php

namespace App\Http\Controllers;

use App\Models\Buy;
use Illuminate\Http\Request;

class CoinBoxController extends Controller
{
    public function index(Request $request, $user)
    {
        $buy = Buy::where('user', $user)->get();
        $endpoint = "https://api.binance.com/api/v3/ticker/24hr";
        $client = new \GuzzleHttp\Client();
        $coinsBox = array();
        $all_profit = 0;
        $all_profit_percent = 0;
        foreach ($buy as $key => $value) {
            $response = $client->request('GET', $endpoint, ['query' => [
                'symbol' => $value['symbol']
            ]]);
            $content = json_decode($response->getBody(), true);
            $binanceResult = [
                'current_price' => $content['lastPrice'],
                'current_total' => ($value['amount'] * $content['lastPrice']),
                'profit' => ($value['amount'] * $content['lastPrice']) - $value['total'],
                'profit_percent' => ((($value['amount'] * $content['lastPrice']) - $value['total']) / $value['total']) * 100,
                'price_change_percent' => $content['priceChangePercent']];
            $coinsBox[$key] = array_merge($value->toArray(), $binanceResult);
            $all_profit += $binanceResult['profit'];
            $all_profit_percent += $binanceResult['profit_percent'];
        }
        return view('coinsBox', [
            'coinsBox' => $coinsBox,
            'allProfit' => $all_profit,
            'allProfitPercent' => $all_profit_percent,
        ]);
    }

    public function createBuy()
    {
        return view('buyCoin');
    }

    public function storeBuy(Request $request)
    {
        Buy::create($request->all());
        return redirect('/coinsBox/' . $request->input('user'));
    }

}
