<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\RoomController;


// Banned users
Route::get('/user/banned', [PageController::class, 'bannedUser']);

// Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index']);

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

// Room
Route::get('rooms/{slug}', [RoomController::class, 'show'])
    ->name('rooms.show');
Route::get('rooms/{room}', [RoomController::class, 'show'])
    ->name('rooms.show');

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
