<?php

namespace App\Helpers;

use App\Enums\Action;
use App\Enums\Modal;

class ModalHelper {
    public static function getId(Modal $name, Action $type, mixed $index): string
    {
        $uid = "$name->name:$type->name";

        return $index ? $uid . $index : $uid;
    }
}
