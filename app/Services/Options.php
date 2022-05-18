<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\ParameterBag;

class Options
{
    protected static $cache = 'app_options_parameters';

    /**
     * @var Symfony\Component\HttpFoundation\ParameterBag
     */
    protected static $bag;

    public static function init()
    {
        self::$bag = Cache::get(self::$cache);
        if (!self::$bag) {
            Cache::put(self::$cache, new ParameterBag());
        }
    }

    public static function get(string $key, $default = null)
    {
        return self::$bag->get($key, $default);
    }
    
    public static function set(string $key, $value)
    {
        self::$bag->set($key, $value);
        Cache::put(self::$cache, self::$bag);
    }
}