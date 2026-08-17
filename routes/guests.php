<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\GuestJoinController;
use App\Http\Controllers\LlmsTxtController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SocialController;
use App\Seo\LocaleUrl;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

$registerLocalizableRoutes = require __DIR__.'/localizable.php';

// Banned users
Route::get('/user/banned', [PageController::class, 'bannedUser']);

// Crawlers & GEO
Route::get('/robots.txt', RobotsController::class)->name('robots');
Route::get('/llms.txt', LlmsTxtController::class)->name('llms');

// Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index']);

// Contact form (POST stays at root for all locales)
Route::post('/contact', [ContactController::class, 'send'])
    ->name('contact.send');

// Auth Social Providers
Route::get('/auth/redirect/{provider}', [SocialController::class, 'redirect'])
    ->name('auth.redirect');

Route::get('/callback/{provider}', [SocialController::class, 'callback'])
    ->name('auth.callback');

// Language switcher endpoint (prefixed URL for SEO pages, session fallback elsewhere)
Route::get('language/{language}', function (string $language) {
    $availableLocales = LocaleUrl::availableLocales();

    if (! in_array($language, $availableLocales, true)) {
        abort(404, 'Language not available');
    }

    session()->put('locale', $language);

    $referer = request()->headers->get('referer');
    $path = '/';

    if (is_string($referer) && $referer !== '') {
        $parsedPath = parse_url($referer, PHP_URL_PATH);
        $path = is_string($parsedPath) && $parsedPath !== '' ? $parsedPath : '/';
    }

    $stripped = LocaleUrl::stripLocalePrefix($path);

    if (LocaleUrl::isLocalizablePath($stripped)) {
        return redirect()->to(
            LocaleUrl::localizedPath(ltrim($stripped, '/'), $language),
        );
    }

    return redirect()->back();
})->name('language');

// Room pages — /rooms/{slug}, locale via session
Route::middleware(['auth.banned', 'ip.banned'])->group(function (): void {
    Route::get('rooms/{room:slug}/public-state', [RoomController::class, 'publicState'])
        ->name('rooms.public-state')
        ->middleware('throttle:120,1');

    Route::get('rooms/{room:slug}', [RoomController::class, 'show'])
        ->name('rooms.show');
});

// Guest join (no auth, no banned check)
Route::get('rooms/{room:slug}/guest-join', GuestJoinController::class)
    ->name('rooms.guest-join');

// Guest conversion to login/register — logout the guest then redirect
Route::middleware('auth')->group(function () {
    Route::get('guest/to-login', function () {
        if (Auth::user()?->isGuest()) {
            Auth::logout();
        }

        return redirect()->route('login');
    })->name('guest.to-login');

    Route::get('guest/to-register', function () {
        if (Auth::user()?->isGuest()) {
            Auth::logout();
        }

        return redirect()->route('register');
    })->name('guest.to-register');
});

// French (default) — unchanged URLs at root
$registerLocalizableRoutes('');

// English & Spanish prefixed surfaces
Route::prefix('en')->group(function () use ($registerLocalizableRoutes): void {
    $registerLocalizableRoutes('en.');
});

Route::prefix('es')->group(function () use ($registerLocalizableRoutes): void {
    $registerLocalizableRoutes('es.');
});
