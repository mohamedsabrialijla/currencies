<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Option;
use App\Models\Trade;
use App\Services\Binance\Binance;
use App\Services\Binance\SymbolFilter;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;

class DashboardController extends Controller
{
    public function index()
    {
 

        $options = Option::pluck('value', 'name')->toArray();


        $wallet = $options['trade.wallet'];
        $asset = str_replace($wallet, '', $options['trade.last_symbol']);

        $client = new Binance(Config::get('binance'));
        $coins = $client->allCoinsInfo();
        $balance = collect($coins)->where('coin', $wallet)->first();
        $symbol = collect($coins)->where('coin', $asset)->first();
       
        return View::make('dashboard', [
            //'balance' => $this->getWalletBalance($options['trade.wallet']),
            'balance' => $balance['free'] ?? 0,
            'symbol' => $symbol['free'] ?? 0,
            'wallet' => $options['trade.wallet'],
            'last_symbol' => $options['trade.last_symbol'] ?? 'N/A',
            'last_price' => $options['trade.last_price'] ?? 'N/A',
            'prev_last_price' => $options['trade.prev_last_price'] ?? 'N/A',
            'trades' => Trade::latest('id')->limit(10)->get(),
            'logs' => Log::latest('id')->limit(10)->get(),
        ]);
    }

    protected function getWalletBalance($wallet)
    {
        $client = new Binance(Config::get('binance'));
        $balance = $client->balance($wallet);
        Option::set('wallet.balance', $balance);
        return $balance;
    }

    protected function sell($symbol, $qty, $sellLimitPrice, $sellStopPrice)
    {
               
        $data = [
            'symbol' => $symbol,
            'side' => 'sell',
            'type' => 'STOP_LOSS_LIMIT',
            'price' => $sellLimitPrice,
            'quantity' => $qty,
            'stop_price' => $sellStopPrice,
        ];
            //   return data;

        $response = app()->make('binance.client')->order('SELL', $symbol, 'STOP_LOSS_LIMIT', [
            'price' => $sellLimitPrice,
            'timeInForce' => 'GTC',
            'quantity' => $qty,
            'stopPrice' => $sellStopPrice,
        ]);
        if (isset($response['code']) || isset($response['msg'])) {
            throw new Exception($response['msg'] . '|Payload: ' . json_encode($data), $response['code']);
        }

        Trade::create($data);
    } 
}
