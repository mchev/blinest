<?php

return [

    'admin_email' => env('ADMIN_EMAIL', null),
    'timezone' => env('APP_TIMEZONE', 'Europe/Paris'),
    
    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */
    'locale' => env('APP_LOCALE', 'fr'),
    
    /*
    |--------------------------------------------------------------------------
    | Available Locales
    |--------------------------------------------------------------------------
    |
    | List of all locales that are available in the application.
    | These locales must have corresponding translation files in the lang/ directory.
    |
    */
    'available_locales' => ['fr', 'en', 'es'],
    
    /*
    |--------------------------------------------------------------------------
    | Locale Names
    |--------------------------------------------------------------------------
    |
    | Display names for each locale in their native language.
    |
    */
    'locale_names' => [
        'fr' => 'Français',
        'en' => 'English',
        'es' => 'Español',
    ],
];
