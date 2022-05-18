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
use Throwable;

class TradeController extends Controller
{
    /**
     * @var \App\Services\Binance\Binance
     */
    protected $client;

    /**
     * @var array
     */
    protected $options;


    /**
     * @return \App\Services\Binance\Binance
     */
    protected function getBinanceClient()
    {
        if (!$this->client) {
            $this->client = new Binance(Config::get('binance'));
        }
        return $this->client;
    }

    /**
     * @return array
     */
    protected function getOptions($name = null, $default = null)
    {
        if (!$this->options) {
            $this->options = Option::pluck('value', 'name')->toArray();
        }
        if ($name !== null) {
            return $this->options[$name] ?? $default;
        }
        return $this->options;
    }

    protected function getBalance()
    {
        $balance = $this->getBinanceClient()->balance($this->getOptions('trade.wallet'));
        Option::set('wallet.balance', $balance);
        if ($balance <= 0) {
            //throw new Exception('No balance in wallet.');
        }
        return $balance;
    }

    protected function getTradeSymbol()
    {
        $last_trade_symbol = $this->getOptions('trade.last_symbol');
        $symbols = explode(',', $this->getOptions('trade.symbols'));
        return $this->getBinanceClient()->ticker($symbols[0]);

        $ticker = $this->getBinanceClient()->ticker();

        $low = 100;
        $trade = null;
        foreach ($ticker as $item) {
            if (in_array($item['symbol'], $symbols)
                && floatval($item['priceChangePercent']) < $low
                && $last_trade_symbol != $item['symbol']) {

                $low = floatval($item['priceChangePercent']);
                $trade = $item;
            }
        }
        return $trade;
    }


    public function trade()
    {
        $trade = $this->getTradeSymbol();
        $exchange = $this->getBinanceClient()->exchange($trade['symbol']);

        dd($trade,$exchange);
        $last_price =  (float) $this->getOptions('trade.last_price', 0);
        $price = (float) $trade['lastPrice'];


        $change = 100 * ($price - $last_price) / $last_price;
        if($price < Option::get('trade.min_price',999999999999)){
            Option::set('trade.min_price', $price);
        }
        if($price > Option::get('trade.max_price',-999999999999)){
            Option::set('trade.max_price', $price);
        }

        $change_min= 100 * ($price - Option::get('trade.min_price')) / Option::get('trade.min_price');
        $change_max= 100 * ($price - Option::get('trade.max_price')) / Option::get('trade.max_price');
        $acc_change = (float) $this->getOptions('trade.acc_price_change', 0);
        $acc_change += $change;

        Option::set('trade.last_price', $price);
        Option::set('trade.prev_last_price', $last_price);
        Option::set('trade.acc_change', $acc_change);
        if (!Option::get('trade.status')) {
            return;
        }

        try {
            $exchange = $this->getBinanceClient()->exchange($trade['symbol']);
            $filter = new SymbolFilter($exchange['filters']);
            $asset = str_replace($this->getOptions('trade.wallet'), '', $trade['symbol']);
            $change_percent = (float)( 100 *  $this->getOptions('trade.change_percent', 0.05));
            $change_percent_sell = (float)( 100 *  $this->getOptions('trade.change_percent_sell', 0.005));
            $BaseBalance=$this->getBalance();
            $ExchangeBalance=$this->getBinanceClient()->balance($asset);
            $qty = $BaseBalance / $price;

            $whichSide='Exchange';
            if ($qty >= ($filter->minQty)*5) {
                $whichSide='Base';
            }



            if ($price == $last_price) {
                return;
            }

//
//            if (abs($change) < $change_percent) {
//                return;
//            }
            dd($whichSide,$qty,$filter->minQty,$BaseBalance);
            if($whichSide == 'Exchange'){
                //convert to usdt or nothing   //$change_max
                if (abs($change_max) < $change_percent_sell) {
                    return;
                }
                //$last_price
                if ($price < Option::get('trade.max_price')) {
                    $maxQty = $ExchangeBalance;
                    $qty = 0;
                    while ($qty < $maxQty) {
                        $qty += $filter->stepSize;
                    }
                    while ($qty > $maxQty) {
                        $qty -= $filter->stepSize;
                    }
                    if ($qty < $filter->minQty) {
                        return;
                    }
                    if ($price * $qty < $filter->minNotional) {
                        return;
                    }

                    $this->sell($trade['symbol'], $qty, $price, null, $trade['priceChangePercent'],$last_price,$price);
                    $trade = $this->getTradeSymbol();

                    $last_price =  $price;
                    $price = (float) $trade['lastPrice'];
                    $change = 100 * ($price - $last_price) / $last_price;
                    $acc_change += $change;

                    Option::set('trade.last_price', $price);
                    Option::set('trade.prev_last_price', $last_price);
                    Option::set('trade.acc_change', $acc_change);
                    if($price < $last_price){
                        Option::set('trade.min_price', $price);
                        Option::set('trade.max_price', $last_price);

                    }else{
                        Option::set('trade.min_price', $last_price);
                        Option::set('trade.max_price', $price);
                    }

                    return;
                }

            }else{
                //convert to bnb or nothing  //$change_min

                if (abs($change_min) < $change_percent) {
                    return;
                }
                if ($BaseBalance <= 0) {
                    return;
                }
                if ($price > Option::get('trade.min_price')) {
                    $qty = $BaseBalance / $price;
                    if ($qty < $filter->minQty) {
                        return;
                        $message = sprintf('Trade (%s): Qty (%s) less than the minQty (%s)', $trade['symbol'], $qty, $filter->minQty);
                        throw new Exception($message);
                    }
                    if ($qty > $filter->maxQty) {
                        $qty = $filter->maxQty;
                    } else {
                        $maxQty = $qty - $filter->stepSize;
                        $qty = 0;
                        while ($qty * $price < $BaseBalance) {
                            $qty += $filter->stepSize;
                        }
                        while ($qty * $price > $BaseBalance) {
                            $qty -= $filter->stepSize;
                        }

                    }
                    if ($price * $qty < $filter->minNotional) {
                        return;
                        $message = sprintf('Trade (%s): Total (%s) less than the minNotional (%s)', $trade['symbol'], $price * $qty, $filter->minNotional);
                        throw new Exception($message);
                    }
                    Option::set('trade.min_price', $price);
                    Option::set('trade.max_price', $price);
                    $this->buy($trade['symbol'], $qty, $price, $trade['priceChangePercent'],$last_price,$price);
                    $trade = $this->getTradeSymbol();

                    $last_price =  $price;
                    $price = (float) $trade['lastPrice'];
                    $change = 100 * ($price - $last_price) / $last_price;
                    $acc_change += $change;

                    Option::set('trade.last_price', $price);
                    Option::set('trade.prev_last_price', $last_price);
                    Option::set('trade.acc_change', $acc_change);
                    if($price < $last_price){
                        Option::set('trade.min_price', $price);
                        Option::set('trade.max_price', $last_price);

                    }else{
                        Option::set('trade.min_price', $last_price);
                        Option::set('trade.max_price', $price);
                    }
                }
            }

        } catch (Throwable $e) {
            Log::log('error', $e->getMessage(),$e->getTraceAsString());
        }

    }

    protected function buy($symbol, $qty, $price, $change_percent = null,$last_price,$new_price)
    {
        dd('BUY');
        $data = [
            'symbol' => $symbol,
            'side' => 'buy',
            'type' => 'MARKET',
            'price' => $price,
            'quantity' => $qty,
            'price_change_percent' => $change_percent,
            'last_price' => $last_price,
            'new_price' => $new_price,
        ];

        $response = $this->getBinanceClient()->order('BUY', $symbol, 'MARKET', [
            'quantity' => $qty,
        ]);
        if (!isset($response['orderId'])) {
            throw new Exception($response['msg'] . ' ' . json_encode($data), $response['code']);
        }

        $data['order_id'] = (string) ($response['orderId'] ?? null);
        $data['order_status'] = ($response['status'] ?? 'FILLED');
        $data['response'] = $response;
        /*if (isset($response['fills'])) {
            $data['price'] = 0;
            foreach ($response['fills'] as $fill) {
                $data['price'] += (float) $fill['price'];
            }
        }*/

        $trade = Trade::create($data);

        Option::set('trade.last_symbol', $symbol);
        return $response;
    }

    protected function sell($symbol, $qty, $price, $sellStopPrice = null, $change_percent = null,$last_price,$new_price)
    {
        dd('SELL');
        if ($sellStopPrice === null) {
            $sellStopPrice = $price;
        }

        /*$data = [
            'symbol' => $symbol,
            'side' => 'sell',
            'type' => 'STOP_LOSS_LIMIT',
            'price' => $price,
            'quantity' => $qty,
            'stop_price' => $sellStopPrice,
        ];*/
        $data = [
            'symbol' => $symbol,
            'side' => 'sell',
            'type' => 'MARKET',
            'price' => $price,
            'quantity' => $qty,
            'price_change_percent' => $change_percent,
            'last_price' => $last_price,
            'new_price' => $new_price,
        ];

        $response = $this->getBinanceClient()->order('SELL', $symbol, 'MARKET', [
            //'price' => $price,
            //'timeInForce' => 'GTC',
            'quantity' => $qty,
            //'stopPrice' => $sellStopPrice,
        ]);
        if (isset($response['code']) && isset($response['msg'])) {
            throw new Exception($response['msg'] . ' ' . json_encode($data), $response['code']);
        }
        if (!isset($response['orderId'])) {
            return;
        }

        $data['order_id'] = (string) ($response['orderId'] ?? '');
        $data['order_status'] = ($response['status'] ?? 'FILLED');
        $data['response'] = $response;

        $trade = Trade::create($data);
    }


}
