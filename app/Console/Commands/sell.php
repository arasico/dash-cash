<?php

namespace App\Console\Commands;

use App\Models\BuyBot;
use App\Models\SellBotHistory;
use App\Models\SettingBot;
use Illuminate\Console\Command;

class sell extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coin:sell';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sell bot';

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
        $settingBots = SettingBot::all();
        foreach ($settingBots as $settingBot) {
            $buyBots = BuyBot::where('user', $settingBot->user)->orderBy('id', 'DESC')->get();
            foreach ($buyBots as $buyBot) {
                $coinInfo = $this->getCoinInfoForNextBuy($buyBot->symbol, $buyBot);
                if ($settingBot->sell_percent <= $coinInfo['profit_percent']) {
                    SellBotHistory::create([
                        'user' => $buyBots->user,
                        'symbol' => $buyBots->symbol,
                        'amount' => $buyBots->amount,
                        'price' => $buyBots->price,
                        'total' => $buyBots->total,
                        'price_change_percent_buy' => $buyBots->price_change_percent_buy,
                        'sell_amount' => $coinInfo['amount'],
                        'sell_price' => $coinInfo['price'],
                        'sell_total' => $coinInfo['current_total'],
                        'profit_percent' => $coinInfo['profit_percent'],
                        'price_change_percent_sell' => $coinInfo['price_change_percent_sell'],
                    ]);
                    echo "sell\n";
                }
            }
        }
    }

    public function getCoinInfoForNextBuy($symbol, $buyBot)
    {
        $endpoint = "https://api.binance.com/api/v3/ticker/24hr";
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $endpoint, ['query' => [
            'symbol' => $symbol
        ]]);
        $content = json_decode($response->getBody(), true);
        $amount = $buyBot->amount;
        return [
            'amount' => $amount,
            'price' => $content['lastPrice'],
            'current_total' => ($amount * $content['lastPrice']),
            'profit_percent' => ((($amount * $content['lastPrice']) - $buyBot['total']) / $buyBot['total']) * 100,
            'price_change_percent_sell' => $content['priceChangePercent']
        ];
    }
}
