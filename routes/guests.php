<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SocialController;

// Banned users
Route::get('/user/banned', [PageController::class, 'bannedUser']);

// Auth Social Providers

Route::get('/auth/redirect/{provider}', [SocialController::class, 'redirect'])
    ->name('auth.redirect');

Route::get('/callback/{provider}', [SocialController::class, 'callback'])
    ->name('auth.callback');

// Language

Route::get('language/{language}', function ($language) {
    Session()->put('locale', $language);

    return redirect()->back();
})->name('language');

// Home

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/pages/{slug}', [PageController::class, 'show'])
    ->name('pages.show');

// Contact

Route::get('/contact', [ContactController::class, 'index'])
    ->name('contact');
Route::post('/contact', [ContactController::class, 'send'])
    ->name('contact.send');

// Route::post('/broadcasting/auth', function () {

//     if (Auth::guest()) {
//     	$user = new User;
//     	$user->id = (int) str_replace('.', '', microtime(true));
//     	$user->name = 'anon_'.random_int(100, 999);
//         request()->setUserResolver(fn() => $user);
//         Inertia::share('guest', $user);
//     }

//     return Broadcast::auth(request());
// });
