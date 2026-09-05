<?php

namespace App\Enums;

enum Setting: string
{
    /** Closes the auberge regardless of the holiday calendar. */
    case SITE_CLOSED = 'site.closed';
}
