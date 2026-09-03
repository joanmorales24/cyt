<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class AppSetting extends Model
{
    protected $fillable = ['key', 'value'];

    protected function value(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? decrypt($value) : null,
            set: fn($value) => $value ? encrypt($value) : null,
        );
    }

    public static function get($key, $default = null)
    {
        return self::where('key', $key)->first()?->value ?? $default;
    }

    public static function set($key, $value)
    {
        return self::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}

