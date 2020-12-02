<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CoinBoxController extends Controller
{
    public function index(Request $request)
    {
        $buy = array(
            [
                'symbol' => 'BTCUSDT',
                'amount' => 0.007292,
                'total' => 134.91
            ],
            [
                'symbol' => 'JSTUSDT',
                'amount' => 7863.5,
                'total' => 207.98958
            ]
        );
        $endpoint = "https://api.binance.com/api/v3/ticker/24hr";
        $client = new \GuzzleHttp\Client();
        $coinsBox = array();

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
            $coinsBox[$key] = array_merge($value, $binanceResult);
        }
        dd($coinsBox);
//        return view('coinsBox', ['coinsBox' => Buy::all()]);
//        return view('coinsBox', ['coinsBox' => $coinsBox]);
    }
}
