<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\RoomController;
use App\Http\Controllers\PlaylistController;

use App\Http\Controllers\ImagesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\TrackAnswerController;
use Illuminate\Support\Facades\Route;

// Socialite
use App\Http\Controllers\SocialController;

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

Route::get('/broadcast/rooms/{room}', function (App\Models\Room $room) {
    $track = App\Models\Track::inRandomOrder()->first();
    $data = collect([
        'room' => $room,
        'track' => $track,
    ]);
    broadcast(new App\Events\TrackPlayed($data));
    //TrackPlayed::dispach($room);
});


// Auth

Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::get('register', [UserController::class, 'create'])
    ->name('user.create')
    ->middleware('guest');

Route::post('register', [UserController::class, 'store'])
    ->name('user.store')
    ->middleware('guest');

Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.store')
    ->middleware('guest');

Route::delete('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

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
    ->name('home')
    ->middleware('auth');

// Users

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


// Rooms
Route::get('rooms', [RoomController::class, 'index'])
    ->name('rooms');

Route::get('rooms/create', [RoomController::class, 'create'])
    ->name('rooms.create');

Route::post('rooms', [RoomController::class, 'store'])
    ->name('rooms.store');

Route::get('rooms/{room}', [RoomController::class, 'show'])
    ->name('rooms.show');

Route::get('rooms/{room}/edit', [RoomController::class, 'edit'])
    ->name('rooms.edit');

Route::put('rooms/{room}', [RoomController::class, 'update'])
    ->name('rooms.update');

Route::delete('rooms/{room}', [RoomController::class, 'destroy'])
    ->name('rooms.destroy');

Route::put('rooms/{room}/restore', [RoomController::class, 'restore'])
    ->name('rooms.restore');


// Playlists
Route::get('playlists', [PlaylistController::class, 'index'])
    ->name('playlists');

Route::get('playlists/create', [PlaylistController::class, 'create'])
    ->name('playlists.create');

Route::post('playlists', [PlaylistController::class, 'store'])
    ->name('playlists.store');

Route::get('playlists/{playlist}/edit', [PlaylistController::class, 'edit'])
    ->name('playlists.edit');

Route::put('playlists/{playlist}', [PlaylistController::class, 'update'])
    ->name('playlists.update');

Route::delete('playlists/{playlist}', [PlaylistController::class, 'destroy'])
    ->name('playlists.destroy');

Route::put('playlists/{playlist}/restore', [PlaylistController::class, 'restore'])
    ->name('playlists.restore');
        
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
