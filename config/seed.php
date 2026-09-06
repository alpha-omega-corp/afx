<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Seeded administrator
    |--------------------------------------------------------------------------
    |
    | The single account UserSeeder creates. The credentials come from the
    | environment so a real password can be used where it matters and never
    | lives in the repository. Outside production the seeder falls back to a
    | literal, so `migrate:fresh --seed` still works with no setup; in
    | production it refuses to run without SEED_ADMIN_PASSWORD.
    |
    */

    'admin' => [
        'email' => env('SEED_ADMIN_EMAIL', 'admin@afx.ch'),
        'password' => env('SEED_ADMIN_PASSWORD'),
    ],

];
