<?php

namespace App\Console\Commands;

use App\Models\BuyBot;
use App\Models\SettingBot;
use Illuminate\Console\Command;

class buy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coin:buy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'buy bot';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $settingBot = SettingBot::all();
        foreach ($settingBot as $value) {
            $buyBot = BuyBot::where([
                'user' => $value->user,
                'symbol' => $value->symbol,
            ])->orderBy('id', 'DESC')->get();
            $coinInfo = $this->getCoinInfo($value->symbol, $value->purchase_amount);
            if (sizeof($buyBot) === 0) {
                BuyBot::create([
                    'user' => $value->user,
                    'symbol' => $value->symbol,
                    'amount' => $coinInfo['amount'],
                    'price' => $coinInfo['price'],
                    'total' => $value->purchase_amount,
                    'price_change_percent_buy' => $coinInfo['price_change_percent_buy']
                ]);
                echo "buy\n";
            } else if (sizeof($buyBot) < $value->budget / $value->purchase_amount) {
                $coinInfoProfitPercent = $this->getCoinProfitPercent($buyBot[0]);
                if ((-$value->buy_percent) <= $coinInfoProfitPercent['profit_percent']) {
                    BuyBot::create([
                        'user' => $value->user,
                        'symbol' => $value->symbol,
                        'amount' => $coinInfo['amount'],
                        'price' => $coinInfo['price'],
                        'total' => $value->purchase_amount,
                        'price_change_percent_buy' => $coinInfo['price_change_percent_buy']
                    ]);
                    echo "buy\n";
                }
            }
        }
    }

    public function getCoinProfitPercent($buyBot)
    {
        $endpoint = "https://api.binance.com/api/v3/ticker/24hr";
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $endpoint, ['query' => [
            'symbol' => $buyBot->symbol
        ]]);
        $content = json_decode($response->getBody(), true);
        $amount = $buyBot->amount;
        return [
            'profit_percent' => ((($amount * $content['lastPrice']) - $buyBot->total) / $buyBot->total) * 100,
        ];
    }

    public function getCoinInfo($symbol, $purchaseAmount)
    {
        $endpoint = "https://api.binance.com/api/v3/ticker/24hr";
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $endpoint, ['query' => [
            'symbol' => $symbol
        ]]);
        $content = json_decode($response->getBody(), true);
        return [
            'amount' => ($purchaseAmount / $content['lastPrice']),
            'price' => $content['lastPrice'],
            'price_change_percent_buy' => $content['priceChangePercent'],
        ];
    }
}
