<?php

namespace App\Enums;

enum Gallery: string
{
    case DELICACIES = 'delicacies';
    case RESTAURANT = 'restaurant';
    case HOTEL = 'hotel';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
