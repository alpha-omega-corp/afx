<?php

namespace App\Enums;

enum Locale: string
{
    case FR = 'fr';
    case EN = 'en';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
