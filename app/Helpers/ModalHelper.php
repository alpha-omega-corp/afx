<?php

namespace App\Helpers;

use App\Enums\Action;
use App\Enums\Modal;

class ModalHelper {
    /**
     * Builds the DOM id shared by an action's trigger and its modal.
     *
     * Segments are joined with a hyphen so the id is a valid CSS selector on
     * its own; the index is compared against null so that a record with id 0 —
     * or the first row of a loop — still gets its own id.
     */
    public static function getId(Modal $name, Action $type, mixed $index): string
    {
        $uid = "$name->name-$type->name";

        return $index !== null ? "$uid-$index" : $uid;
    }
}
