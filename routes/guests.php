<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SocialController;

// Banned users
Route::get('/user/banned', [PageController::class, 'bannedUser']);

// Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index']);

// FAQ
Route::get('/faq', [FAQController::class, 'index'])
    ->name('faq');

// Level System
Route::get('/level-system', [LevelController::class, 'index'])
    ->name('level-system');

// Pages
Route::get('/pages/{slug}', [PageController::class, 'show'])
    ->name('pages.show');

// Contact
Route::get('/contact', [ContactController::class, 'index'])
    ->name('contact');
Route::post('/contact', [ContactController::class, 'send'])
    ->name('contact.send');

// Auth Social Providers

Route::get('/auth/redirect/{provider}', [SocialController::class, 'redirect'])
    ->name('auth.redirect');

Route::get('/callback/{provider}', [SocialController::class, 'callback'])
    ->name('auth.callback');

// Language

Route::get('language/{language}', function ($language) {
    $availableLocales = config('app.available_locales', ['fr', 'en']);
    
    // Valider que la langue demandée est disponible
    if (!in_array($language, $availableLocales)) {
        abort(404, 'Language not available');
    }
    
    Session()->put('locale', $language);

    return redirect()->back();
})->name('language');

Route::middleware(['auth.banned', 'ip.banned'])->group(function () {

    // Home
    Route::get('/', [HomeController::class, 'index'])
        ->name('home');

    // Room
    Route::get('rooms/{room:slug}', [RoomController::class, 'show'])
        ->name('rooms.show');
});
