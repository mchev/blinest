<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\OrganizationsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\TrackAnswerController;
use Illuminate\Support\Facades\Route;

// Music Providers Services
use App\Services\MusicProviders\AppleMusicService;
use App\Services\MusicProviders\DeezerService;
use App\Services\MusicProviders\SpotifyService;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Auth

Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.store')
    ->middleware('guest');

Route::delete('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');


// Language

Route::get('language/{language}', function ($language) {
    Session()->put('locale', $language);
 
    return redirect()->back();
})->name('language');


// Dashboard

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');

// Users

Route::get('users', [UsersController::class, 'index'])
    ->name('users')
    ->middleware('auth');

Route::get('users/create', [UsersController::class, 'create'])
    ->name('users.create')
    ->middleware('auth');

Route::post('users', [UsersController::class, 'store'])
    ->name('users.store')
    ->middleware('auth');

Route::get('users/{user}/edit', [UsersController::class, 'edit'])
    ->name('users.edit')
    ->middleware('auth');

Route::put('users/{user}', [UsersController::class, 'update'])
    ->name('users.update')
    ->middleware('auth');

Route::delete('users/{user}', [UsersController::class, 'destroy'])
    ->name('users.destroy')
    ->middleware('auth');

Route::put('users/{user}/restore', [UsersController::class, 'restore'])
    ->name('users.restore')
    ->middleware('auth');


// Reports

Route::get('reports', [ReportsController::class, 'index'])
    ->name('reports')
    ->middleware('auth');

// Tracks
Route::get('playlists/{playlist}/tracks', [TrackController::class, 'index'])
    ->name('playlists.tracks')
    ->middleware('auth');

Route::post('playlists/{playlist}/tracks', [TrackController::class, 'store'])
    ->name('playlists.tracks.store')
    ->middleware('auth');

Route::delete('tracks/{track}', [TrackController::class, 'destroy'])
    ->name('tracks.delete')
    ->middleware('auth');

Route::put('tracks/{track}', [TrackController::class, 'update'])
    ->name('tracks.update')
    ->middleware('auth');

Route::get('playlists/{playlist}/tracks/search', [TrackController::class, 'search'])
    ->name('tracks.search')
    ->middleware('auth');

// Tracks Answers
Route::post('tracks/{track}/answers', [TrackAnswerController::class, 'store'])
    ->name('tracks.answers.store')
    ->middleware('auth');

Route::put('tracks/{track}/answers', [TrackAnswerController::class, 'update'])
    ->name('tracks.answers.update')
    ->middleware('auth');

Route::delete('answers/{answer}', [TrackAnswerController::class, 'destroy'])
    ->name('answers.delete')
    ->middleware('auth');

// Images

Route::get('/img/{path}', [ImagesController::class, 'show'])
    ->where('path', '.*')
    ->name('image');


// Music providers
Route::get('providers/deezer/search', [DeezerService::class, 'search'])
    ->name('providers.deezer.search');

Route::get('providers/itunes/search', [AppleMusicService::class, 'search'])
    ->name('providers.itunes.search');

Route::get('providers/spotify/search', [SpotifyService::class, 'search'])
    ->name('providers.spotify.search');
