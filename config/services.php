<?php

return [

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'brevo' => [
        'key' => env('BREVO_KEY'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_APP_ID'),
        'client_secret' => env('FACEBOOK_APP_SECRET'),
        'redirect' => env('FACEBOOK_APP_REDIRECT'),
    ],

    'spotify' => [
        'client_id' => env('SPOTIFY_KEY'),
        'client_secret' => env('SPOTIFY_SECRET'),
        'redirect' => env('SPOTIFY_REDIRECT_URI'),
    ],

    'deezer' => [
        'client_id' => env('DEEZER_CLIENT_ID'),
        'client_secret' => env('DEEZER_CLIENT_SECRET'),
        'redirect' => env('DEEZER_REDIRECT_URI'),
    ],

    'discord' => [
        'client_id' => env('DISCORD_APP_ID'),
        'client_secret' => env('DISCORD_APP_SECRET'),
        'redirect' => env('DISCORD_REDIRECT_URI'),
        'token' => env('DISCORD_BOT_TOKEN'),
        'webhook' => env('DISCORD_WEBHOOK_URL'),
    ],

];
