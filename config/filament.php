<?php

use App\Filament\Access\FilamentAccess;
use Filament\Http\Middleware\Authenticate;

return [

    /*
    |--------------------------------------------------------------------------
    | Filament Admin Panel URL
    |--------------------------------------------------------------------------
    |
    | This is the prefix for your Filament admin routes.
    | The default is 'admin', so your admin panel is at /admin.
    |
    */

    'path' => env('FILAMENT_PATH', 'admin'),

    /*
    |--------------------------------------------------------------------------
    | Filament Auth Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the authentication settings for Filament.
    | We define the guard and login page Livewire component.
    | The 'access' callable controls access permission using your role system.
    |
    */

    'auth' => [
        'guard' => 'web',

        'pages' => [
            'login' => \Filament\Http\Livewire\Auth\Login::class,
        ],

        // Use the class method instead of closure to allow config caching
        'access' => [FilamentAccess::class, 'check'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Middleware used for Filament routes
    |--------------------------------------------------------------------------
    |
    | By default, Filament uses the 'web' and 'auth' middleware,
    | but you can customize or add others as needed.
    |
    */

    'middleware' => [
        'web',
        Authenticate::class,
    ],

    // Add other Filament config options here as needed...
];

