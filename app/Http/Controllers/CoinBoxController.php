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
        $coinBox = array();

        foreach ($buy as $key => $value) {
            $response = $client->request('GET', $endpoint, ['query' => [
                'symbol' => $value['symbol']
            ]]);
            $content = json_decode($response->getBody(), true);
            $binanceResult = [
                'current_price' => $content['lastPrice'],
                'profit' => ($value['amount'] * $content['lastPrice']) - $value['total'],
                'price_change_percent' => $content['priceChangePercent']];
            $coinBox[$key] = array_merge($value, $binanceResult);
        }
        dd($coinBox);
//        return view('welcome', ['coinBox' => Buy::all()]);
//        return view('welcome', ['coinBox' => $coinBox]);
    }
}
