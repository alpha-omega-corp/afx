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
    'holidays_title' => 'Closing calendar',
    'holidays_description' => 'Periods when the auberge is closed. The banner appears on its own.',
    'gallery_title' => 'Gallery',
    'gallery_description' => 'Select photos to remove them, or upload new ones.',
    'menu_title' => 'Menu sections',
    'menu_description' => 'Drag a section to reorder it. Each section holds its own dishes.',
    'messages_title' => 'Received messages',
    'messages_description' => 'Messages sent through the contact form.',

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
    ],

    // Menu editor fields
    'field' => [
        'items' => 'Dishes in this section',
        'dish' => 'Dish',
        'description' => 'Description',
        'price' => 'Price',
        'starts_on' => 'From',
        'ends_on' => 'To',
        'reason' => 'Reason shown to visitors (optional)',
    ],

    // Confirmations
    'confirm' => [
        'delete_section' => 'Delete the section :name and all of its dishes?',
        'delete_images' => 'Delete the selected photos?',
        'delete_message' => 'Permanently delete the message from :name?',
        'delete_holiday' => 'Delete the closing from :from to :to?',
        'close' => 'The auberge will be announced as closed across the site, until reopened by hand.',
        'reopen' => 'The closing banner will disappear, unless a holiday period is already running.',
    ],

    // Empty states
    'empty' => [
        'menu' => 'No sections yet. Create one to start building the menu.',
        'gallery' => 'No photos in this gallery.',
        'messages' => 'No messages received yet.',
        'holidays' => 'No closing planned.',
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
