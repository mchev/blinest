<?php

use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\UserIsAdministrator;
use App\Http\Middleware\UserIsPublicModerator;
use App\Providers\AppServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Intervention\Image\ImageServiceProvider;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        ImageServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo(fn () => route('login'));
        $middleware->redirectUsersTo(AppServiceProvider::HOME);

        $middleware->web([
            SetLocale::class,
            HandleInertiaRequests::class,
        ]);

        $middleware->statefulApi();
        $middleware->throttleApi();
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'auth.administrator' => UserIsAdministrator::class,
            'auth.moderator' => UserIsPublicModerator::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
