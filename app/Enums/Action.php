<?php

namespace App\Enums;

enum Action: string
{
    case CREATE = 'POST';
    case READ = 'GET';
    case UPDATE = 'PUT';
    case DELETE = 'DELETE';
}
