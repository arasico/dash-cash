<?php

namespace App\Http\Controllers;

use App\Models\Buy;
use App\Models\TotalProfit;
use App\Models\UserConfig;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
            if($value['amount'] < 1 )
                $amount = $value['amount'] - ((0.2 * $value['amount']) / 100);
            $amount = $amount;
            $binanceResult = [
                'current_price' => $content['lastPrice'],
                'current_total' => ($amount * $content['lastPrice']),
                'profit' => ($amount * $content['lastPrice']) - $value['total'],
                'profit_percent' => ((($amount * $content['lastPrice']) - $value['total']) / $value['total']) * 100,
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

    public function sellBuy($id)
    {
        //get buy data
        $buy = Buy::find($id);
        $endpoint = "https://api.binance.com/api/v3/ticker/24hr";
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $endpoint, ['query' => [
            'symbol' => $buy['symbol']
        ]]);
        $content = json_decode($response->getBody(), true);
        if($buy['amount'] < 1 )
            $amount = $buy['amount'] - ((0.2 * $buy['amount']) / 100);
        $amount = $amount + 0;
        $binanceResult = [
            'profit' => ($amount * $content['lastPrice']) - $buy['total'],
            'profit_percent' => ((($amount * $content['lastPrice']) - $buy['total']) / $buy['total']) * 100,
        ];
        //sell
        $userConfig = UserConfig::where('user', $buy['user'])->first();
        $timestamp = strtotime('now') * 1000;
        $string = 'symbol=' . $buy['symbol'] . '&side=SELL&type=MARKET&quantity=' .
            $amount . '&newClientOrderId=my_order_id_' . $buy['id'] . '&timestamp=' . $timestamp;
        $sig = hash_hmac('sha256', $string, $userConfig['binance_api_secret']);
        $endpointSell = "https://api.binance.com/api/v3/order";
        $responseSell = $client->request('POST', $endpointSell, ['query' => [
            'symbol' => $buy['symbol'],
            'side' => 'SELL',
            'type' => 'MARKET',
            'quantity' => $amount,
            'newClientOrderId' => 'my_order_id_' . $buy['id'],
            'timestamp' => $timestamp,
            'signature' => $sig,
        ], 'headers' => [
            'Content-Type' => 'application/json',
            'X-MBX-APIKEY' => $userConfig['binance_api_key']
        ]]);
        $contentSell = json_decode($responseSell->getBody(), true);
        if ($responseSell->getStatusCode() === 200) {
            TotalProfit::create([
                'user' => $buy['user'],
                'symbol' => $buy['symbol'],
                'profit' => $binanceResult['profit'],
                'profit_percent' => $binanceResult['profit_percent'],
            ]);
            Buy::where('id', $id)->delete();
        }
        return redirect('/coinsBox/' . $buy['user']);
    }

    public function sellManualBuy($id)
    {
        //get buy data
        $buy = Buy::find($id);
        $endpoint = "https://api.binance.com/api/v3/ticker/24hr";
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $endpoint, ['query' => [
            'symbol' => $buy['symbol']
        ]]);
        $content = json_decode($response->getBody(), true);
        if($buy['amount'] < 1 )
            $amount = $buy['amount'] - ((0.2 * $buy['amount']) / 100);
        $amount = $amount + 0;
        $binanceResult = [
            'profit' => ($amount * $content['lastPrice']) - $buy['total'],
            'profit_percent' => ((($amount * $content['lastPrice']) - $buy['total']) / $buy['total']) * 100,
        ];
        TotalProfit::create([
            'user' => $buy['user'],
            'symbol' => $buy['symbol'],
            'profit' => $binanceResult['profit'],
            'profit_percent' => $binanceResult['profit_percent'],
        ]);
        Buy::where('id', $id)->delete();
        return redirect('/coinsBox/' . $buy['user']);
    }

    public function destroyBuy(Request $request, $id)
    {
        $buy = Buy::find($id);
        Buy::where('id', $id)->delete();
        return redirect('/coinsBox/' . $buy['user']);
    }

}
