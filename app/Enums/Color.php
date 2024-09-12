<?php

namespace App\Enums;

enum Color: string
{
    case PRIMARY = 'primary';
    case DARK = 'dark';
    case LIGHT = 'light';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

}
