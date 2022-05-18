<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'level', 'message','trace'
    ];

    public static function log($level, $message,$trace=null)
    {
        return self::query()->create([
            'level' => $level,
            'message' => $message,
            'trace' => $trace,
        ]);
    }
}
