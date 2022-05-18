<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Trade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class OptionsController extends Controller
{
    public function index()
    {
        
        $account = app()->make('binance.client')->accountInformation();
        $balances = [];
        foreach ($account['balances'] as $symbol) {
            if ($symbol['free'] == 0) {
                continue;
            }
            $balances[$symbol['asset']] = $symbol['asset'] . " ({$symbol['free']})";
        }

        return View::make('options', [
            'options' => Option::pluck('value', 'name')->toArray(),
            'balances' => $balances,
        ]);
    }

    public function store(Request $request)
    {
        foreach ($request->post('options') as $name => $value) {
            Option::set($name, $value);
        }
        Cache::forget('app-options');

        return Redirect::route('options')->with('success', 'Options saved!');
    }
}
