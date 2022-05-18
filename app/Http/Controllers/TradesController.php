<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Trade;
use App\Services\Binance\SymbolFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class TradesController extends Controller
{
    public function index()
    {
        return View::make('trades.index', [
            'trades' => DB::table('trades')->latest()->paginate(),
        ]);
    }

    public function orders()
    {
        //Trade::where('order_id', 2745584856)->delete();
        /*Trade::create([
            'symbol' => 'ETHUSDT',
            'side' => 'buy',
            'type' => 'MARKET',
            'price' => 1318.9,
            'quantity' => 0.03953000,
            'price_change_percent' => 0,
            'order_id' => 2754084131,
            'order_status' => 'FILLED',
        ]);*/
        //Option::set('trade.last_symbol', 'ETHUSDT');
        /*Trade::create([
            'symbol' => 'BNBUSDT',
            'side' => 'sell',
            'type' => 'STOP_LOSS_LIMIT',
            'price' => 41.48120000,
            'stop_price' => 41.48120000,
            'quantity' => 1.29100000,
            'order_id' => 1330674401,
            'order_status' => 'FILLED',
        ]);*/
        //$exchange = app()->make('binance.client')->exchange(Option::get('trade.last_symbol'));
        //dd( $exchange );

        //return app()->make('binance.client')->exchange('BTCUSDT');

        //return app()->make('binance.client')->orders('ETHUSDT');
        return app()->make('binance.client')->orders(Option::get('trade.last_symbol'));
    }
}
