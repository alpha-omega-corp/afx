<?php

namespace App\Models;

use App\Enums\Setting as SettingKey;
use Illuminate\Database\Eloquent\Model;

/**
 * A handful of site-wide switches, read on nearly every request and
 * therefore cached for the lifetime of the request.
 */
class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    private static ?array $cache = null;

    public static function get(SettingKey $key, ?string $default = null): ?string
    {
        self::$cache ??= self::pluck('value', 'key')->all();

        return self::$cache[$key->value] ?? $default;
    }

    public static function bool(SettingKey $key, bool $default = false): bool
    {
        $value = self::get($key);

        return $value === null ? $default : filter_var($value, FILTER_VALIDATE_BOOL);
    }

    public static function put(SettingKey $key, string|bool $value): void
    {
        $value = is_bool($value) ? ($value ? '1' : '0') : $value;

        self::updateOrCreate(['key' => $key->value], ['value' => $value]);

        self::$cache = null;
    }
}
