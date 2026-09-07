<?php

return [
    // Shell
    'title' => 'Administration',
    'nav_label' => 'Manageable sections',
    'nav_toggle' => 'Open the admin menu',
    'view_site' => 'View site',

    // Panels — one per group of actions
    'pages_title' => 'Pages',
    'pages_description' => 'The image, title and text shown at the top of each public page.',
    'opening_title' => 'Opening',
    'status_title' => 'Site status',
    'status_description' => 'Closes the auberge right now, outside of any planned holiday.',
    'hours_title' => 'Opening hours',
    'hours_description' => 'The service hours printed in the footer and on the contact page. The closed days are also what the daily special steps over when it picks its date.',
    'holidays_title' => 'Closing calendar',
    'holidays_description' => 'Periods when the auberge is closed. The banner appears on its own.',
    'gallery_title' => 'Gallery',
    'gallery_description' => 'Select photos to remove them, or upload new ones.',
    'menu_title' => 'Menu sections',
    'menu_description' => 'Drag a section to reorder it. Each section holds its own dishes.',

    'auto' => 'auto',

    'hint' => [
        'translation' => 'Write in French. An English field left blank is translated for you and keeps following the French; the moment you type in it, your words are the ones that stay.',
        'daily_language' => 'The card of the day is written in French. The English is translated for you and can be corrected in Administration → Menu.',
        'auto' => 'Machine translation, not yet read over.',
    ],

    'pending_migrations' => 'This section is waiting on a database migration. Run it before changing the opening state or the closures — nothing is saved until it has.',

    // Actions
    'action' => [
        'edit' => 'Edit',
        'create' => 'Add',
        'delete' => 'Delete',
        'read' => 'Read',
        'cancel' => 'Cancel',
        'confirm' => 'Confirm',
        'save' => 'Save',
        'add_images' => 'Add photos',
        'delete_images' => 'Delete selection',
        'add_section' => 'New section',
        'add_holiday' => 'New period',
        'edit_holiday' => 'Edit period',
        'close' => 'Close the site',
        'reopen' => 'Reopen the site',
        'reorder' => 'Reorder',
        'edit_daily' => 'Edit the daily special',
        'edit_hours' => 'Edit the hours',
    ],

    // Menu editor fields
    'field' => [
        'items' => 'Dishes in this section',
        'dish' => 'Dish',
        'description' => 'Description',
        'section_title_fr' => 'Section heading (French)',
        'section_title_en' => 'Section heading (English)',
        'dish_fr' => 'Dish (French)',
        'dish_en' => 'Dish (English)',
        'description_fr' => 'Description (French)',
        'description_en' => 'Description (English)',
        'price' => 'Price',
        'daily_on' => 'Date shown on the card',
        'daily_dishes' => 'Dishes on the card of the day',
        'closed_all_day' => 'Closed all day',
        'lunch_from' => 'Lunch — from',
        'lunch_to' => 'Lunch — to',
        'dinner_from' => 'Dinner — from',
        'dinner_to' => 'Dinner — to',
        'section' => 'Menu section',
        'show_on_home' => 'Show on the home page',
        'starts_on' => 'From',
        'ends_on' => 'To',
        'reason' => 'Reason shown to visitors (optional)',
    ],

    // Confirmations
    'confirm' => [
        'delete_section' => 'Delete the section :name and all of its dishes?',
        'delete_images' => 'Delete the selected photos?',
        'delete_holiday' => 'Delete the closing from :from to :to?',
        'close' => 'The auberge will be announced as closed across the site, until reopened by hand.',
        'reopen' => 'The closing banner will disappear, unless a holiday period is already running.',
    ],

    // Empty states
    'empty' => [
        'menu' => 'No sections yet. Create one to start building the menu.',
        'gallery' => 'No photos in this gallery.',
        'holidays' => 'No closing planned.',
        'daily' => 'No daily special on show. The button above writes one.',
        'hours' => 'No hours saved yet.',
        'daily_sections' => 'Create a menu section first: the daily special is filed under one, like any other dish.'
    ],

    'gallery' => [
        'delicacies' => 'Home',
        'restaurant' => 'Restaurant',
        'hotel' => 'Hotel',
    ],

    'status' => [
        'open' => 'Open',
        'closed' => 'Closed',
        'open_hint' => 'The site announces no closing.',
        'closed_manually' => 'Closed by hand, until reopened.',
        'closed_by_calendar' => 'Holiday running, from :from to :to.',
    ],

    'holiday' => [
        'current' => 'Running',
        'past' => 'Past',
        'upcoming' => 'In :days days',
        'no_reason' => 'No reason given',
    ],

    'selected' => 'selected',
];
