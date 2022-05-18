<?php

namespace App\Providers;

use App\Models\Option;
use App\Services\Binance\Binance;
use App\Services\Options;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->initConfig();
        $this->app->singleton('binance.client', function($app) {
            return new Binance(Config::get('binance'));
        });
    }

    protected function initConfig()
    {
        $options = Cache::get('app-options');
        if (!$options) {
            $options = Option::pluck('value', 'name')->toArray();
            Cache::put('app-options', $options);
        }
        foreach ($options as $key => $value) {
            Config::set($key, $value);
        }
    }
}
