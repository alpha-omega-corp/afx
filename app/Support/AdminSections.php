<?php

namespace App\Support;

/**
 * The administrable sections of the site, in the order they are shown.
 *
 * One list, read by both the admin rail and the footer, so the two can never
 * drift into disagreeing about what the back office contains.
 */
class AdminSections
{
    /** @return array<int, array{route: string, label: string, icon: string}> */
    public static function all(): array
    {
        return [
            ['route' => 'admin.pages', 'label' => __('nav.pages'), 'icon' => 'heroicon-o-document-text'],
            ['route' => 'admin.gallery', 'label' => __('nav.gallery'), 'icon' => 'heroicon-o-photo'],
            ['route' => 'admin.menu', 'label' => __('nav.menu'), 'icon' => 'heroicon-o-list-bullet'],
            ['route' => 'admin.contact', 'label' => __('nav.contact'), 'icon' => 'heroicon-o-envelope-open'],
            ['route' => 'admin.opening.index', 'label' => __('nav.opening'), 'icon' => 'heroicon-o-calendar-days'],
        ];
    }
}
