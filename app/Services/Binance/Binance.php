<?php

namespace App\Services\Binance;

use Illuminate\Support\Facades\Http;

class Binance
{
    protected $baseUrl;

    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
        $this->baseUrl = $config['urls'][$config['mode'] ?? 'live'];
    }

    public function exchange($symbol = null)
    {
        $response = Http::baseUrl($this->baseUrl)
            ->withHeaders([
                'X-MBX-APIKEY' => $this->config['api']['key']
            ])
            ->get('api/v3/exchangeInfo')
            ->json();

        if ($symbol) {
            return collect($response['symbols'])->where('symbol', '=', $symbol)->first();
        }

        return $response;
    }

    public function ticker($symbol = null)
    {
        $params = [];
        if ($symbol) {
            $params['symbol'] = $symbol; 
        }
        return Http::baseUrl($this->baseUrl)
            ->withHeaders([
                'X-MBX-APIKEY' => $this->config['api']['key']
            ])
            ->get('api/v3/ticker/24hr', $params)
            ->json();
    }

    public function accountSnapshot()
    {
        $params = $this->prepareRequestParameters([
            'type' => 'SPOT',
        ]);

        return Http::baseUrl($this->baseUrl)
            ->withHeaders([
                'X-MBX-APIKEY' => $this->config['api']['key']
            ])
            ->get('sapi/v1/accountSnapshot', $params)
            ->json();
    }

    public function allCoinsInfo()
    {
        $params = $this->prepareRequestParameters([
        ]);

        return Http::baseUrl($this->baseUrl)
            ->withHeaders([
                'X-MBX-APIKEY' => $this->config['api']['key']
            ])
            ->get('sapi/v1/capital/config/getall', $params)
            ->json();
    }

    public function accountInformation()
    {
        $params = $this->prepareRequestParameters([
        ]);

        return Http::baseUrl($this->baseUrl)
            ->withHeaders([
                'X-MBX-APIKEY' => $this->config['api']['key']
            ])
            ->get('api/v3/account', $params)
            ->json();
    }

    public function order($side, $symbol, $type = 'MARKET', $params = [], $test = false)
    {        
        $params = $this->prepareRequestParameters(array_merge([
            'symbol' => $symbol,
            'side' => strtoupper($side),
            'type' => strtoupper($type),
            'recvWindow' => 60000,
        ], $params));
        
        return Http::baseUrl($this->baseUrl)
            ->withHeaders([
                'X-MBX-APIKEY' => $this->config['api']['key']
            ])
            ->asForm()
            ->post('api/v3/order' . ($test? '/test' : ''), $params)
            ->json();
    }

    public function cancelOrder($symbol, $order_id)
    {
        $params = $this->prepareRequestParameters([
            'symbol' => $symbol,
            'orderId' => $order_id,
        ]);
        
        return Http::baseUrl($this->baseUrl)
            ->withHeaders([
                'X-MBX-APIKEY' => $this->config['api']['key']
            ])
            ->asForm()
            ->delete('api/v3/order', $params)
            ->json();
    }

    public function openOrders($symbol = null)
    {
        $params = [];
        if ($symbol) {
            $params = [
                'symbol' => $symbol,
            ];
        }
        $params = $this->prepareRequestParameters($params);

        return Http::baseUrl($this->baseUrl)
            ->withHeaders([
                'X-MBX-APIKEY' => $this->config['api']['key']
            ])
            ->get('api/v3/openOrders', $params)
            ->json();
    }

    public function orders($symbol)
    {
        $params = [
            'symbol' => $symbol,
        ];
        $params = $this->prepareRequestParameters($params);

        return Http::baseUrl($this->baseUrl)
            ->withHeaders([
                'X-MBX-APIKEY' => $this->config['api']['key']
            ])
            ->get('api/v3/allOrders', $params)
            ->json();
    }

    public function balance($asset)
    {
        $coins = $this->allCoinsInfo();
        $coin = collect($coins)->where('coin', $asset)->first();
        return $coin['free'] ?? 0;
    }

    public function myTrades($symbol)
    {
        $params = [
            'symbol' => $symbol,
        ];
        $params = $this->prepareRequestParameters($params);

        return Http::baseUrl($this->baseUrl)
            ->withHeaders([
                'X-MBX-APIKEY' => $this->config['api']['key']
            ])
            ->get('api/v3/myTrades', $params)
            ->json();
    }

    protected function prepareRequestParameters($parameters)
    {
        foreach ($parameters as $key => $value) {
            if ($key == 'price' || $key == 'stopPrice') {
                $parameters[$key] = $this->formatPrice($value);
            } else if ($key == 'quantity') {
                $parameters[$key] = $this->formatQuantity($value);
            }
        }

        $parameters['timestamp'] = number_format((microtime(true) * 1000), 0, '.', '');
        $parameters['signature'] = hash_hmac('sha256', http_build_query($parameters), $this->config['api']['secret']);
        return $parameters;
    }

    protected function formatPrice($price)
    {
        return number_format($price, 8, '.', '');
    }

    protected function formatQuantity($qty)
    {
        return number_format($qty, 8, '.', '');
    }
}