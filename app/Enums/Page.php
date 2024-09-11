<?php

namespace App\Enums;

enum Page: string
{
    case HOME = 'home';
    case ABOUT = 'about';
    case MENU = 'menu';
    case RESTAURANT = 'restaurant';
    case HOTEL = 'hotel';
    case CONTACT = 'contact';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

}
