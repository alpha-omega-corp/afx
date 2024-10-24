<?php

namespace App\Enums;

enum Icon: string
{
    case EMAIL = 'heroicon-o-envelope-open';
    case PASSWORD = 'heroicon-o-lock-closed';
    case EDIT = 'heroicon-o-pencil-square';
    case TITLE = 'heroicon-o-paper-clip';
    case CREATE = 'heroicon-s-plus-circle';
    case DELETE = 'heroicon-s-trash';
    case PRICE = 'heroicon-o-currency-dollar';
    case INFO = 'heroicon-o-information-circle';
    case READ = 'heroicon-o-eye';
    case UPLOAD = 'heroicon-o-cloud-arrow-up';
    case ZOOM = 'heroicon-o-magnifying-glass-circle';
    case PIN = 'heroicon-o-map-pin';
    case PHONE = 'heroicon-o-phone';
}
