<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Bootstrap admin (from .env)
    |--------------------------------------------------------------------------
    |
    | Set ADMIN_EMAIL and ADMIN_PASSWORD to create or update the admin user on
    | deploy (php artisan admin:sync). Change these in Portainer/.env anytime;
    | restart the app container to apply a new password.
    |
    */

    'name' => env('ADMIN_NAME', 'Admin'),
    'email' => env('ADMIN_EMAIL'),
    'password' => env('ADMIN_PASSWORD'),
];
