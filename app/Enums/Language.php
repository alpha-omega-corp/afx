<?php

namespace App\Enums;

enum Language: string
{
    case FR = 'fr';
    case EN = 'en';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
