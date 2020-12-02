<?php

namespace App\Http\Controllers;

use App\Models\Buy;
use App\Models\TotalProfit;
use App\Models\UserConfig;
use Carbon\Carbon;
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
        $coinsBox = collect($coinsBox)->sortBy('profit_percent')->reverse()->toArray();
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
        $binanceResult = [
            'profit' => ($buy['amount'] * $content['lastPrice']) - $buy['total'],
            'profit_percent' => ((($buy['amount'] * $content['lastPrice']) - $buy['total']) / $buy['total']) * 100,
        ];

        //sell
        $userConfig = UserConfig::where('user', $buy['user'])->first();
        $endpointSell = "https://api.binance.com/api/v3/order";
        $responseSell = $client->request('POST', $endpointSell, ['query' => [
            'symbol' => $buy['symbol'],
            'side' => 'SELL',
            'type' => 'LIMIT',
            'timeInForce' => 'GTC',
            'quantity' => $buy['amount'],
            'price' => '100',
            'newClientOrderId' => 'my_order_id_' . $buy['id'],
            'timestamp' => '2326558065',
            'signature' => 'arasico',
            'recvWindow' => 10000
        ], 'headers' => [
            'Content-Type' => 'application/json',
            'X-MBX-APIKEY' => $userConfig['binance_api_key']
        ]]);
//        'price' => $content['lastPrice'],
        $contentSell = json_decode($responseSell->getBody(), true);

        dd($contentSell, $responseSell->getStatusCode());
        if ($responseSell->getStatusCode() === 200) {
            TotalProfit::create([
                'user' => $buy['user'],
                'symbol' => $binanceResult['symbol'],
                'profit' => $binanceResult['profit'],
                'profit_percent' => $binanceResult['profit_percent'],
            ]);
            Buy::where('id', $id)->delete();
        }
    }

    public function destroyBuy(Request $request, $id)
    {
        $buy = Buy::find($id);
        Buy::where('id', $id)->delete();
        return redirect('/coinsBox/' . $buy['user']);
    }

}
