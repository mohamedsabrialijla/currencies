<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

   /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'name';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    protected $fillable = [
        'name', 'value',
    ];

    public static function get($name, $default = null)
    {
        $value = self::query()->where('name', $name)->value('value');
        if ($value === null) {
            $value = $default;
        }
        return $value;
    }

    public static function set($name, $value)
    {
        return self::query()->updateOrCreate([
            'name' => $name,
        ], [
            'value' => $value,
        ]);
    }
}
