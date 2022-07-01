<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\PlaylistModeratorController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomModeratorController;
use App\Http\Controllers\RoomPlaylistController;
use App\Http\Controllers\RoundController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\TrackAnswerController;
// Moderation
use App\Http\Controllers\TrackController;
use App\Http\Controllers\UserController;
// Socialite
use App\Services\MusicProviders\AppleMusicService;
// Music Providers Services
use App\Services\MusicProviders\DeezerService;
use App\Services\MusicProviders\SpotifyService;
use Illuminate\Support\Facades\Route;

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

// Me

Route::get('me', [UserController::class, 'show'])
    ->name('me.show')
    ->middleware('auth');

Route::put('me/edit', [UserController::class, 'edit'])
    ->name('me.edit')
    ->middleware('auth');

Route::put('me', [UserController::class, 'update'])
    ->name('me.update')
    ->middleware('auth');

Route::delete('me/destroy', [UserController::class, 'destroy'])
    ->name('me.destroy')
    ->middleware('auth');

// Users
Route::get('users/{user}', [UserController::class, 'show'])
    ->name('users.show')
    ->middleware('auth');

// Rooms
Route::put('rooms/{room}/restore', [RoomController::class, 'restore'])
    ->name('rooms.restore');

Route::get('rooms/{room}/joined', [RoomController::class, 'joined'])
    ->name('rooms.joined');

Route::post('rooms/{room}/playlists/attach', [RoomPlaylistController::class, 'attach'])
    ->name('rooms.playlists.attach');

Route::delete('rooms/{room}/playlists/detach', [RoomPlaylistController::class, 'detach'])
    ->name('rooms.playlists.detach');

Route::resource('rooms', RoomController::class);

// Rounds
Route::post('rounds/{round}/tracks/{track}/check', [RoundController::class, 'check'])
    ->name('rounds.track.check');

// Controls
Route::post('rounds/{round}/stop', [RoundController::class, 'stop'])
    ->name('rounds.stop');
Route::post('rooms/{room}/start', [RoomController::class, 'start'])
    ->name('rounds.start');
Route::post('rounds/{round}/track/resume', [RoundController::class, 'resume'])
    ->name('rounds.track.resume');
Route::post('rounds/{round}/track/pause', [RoundController::class, 'pause'])
    ->name('rounds.track.pause');
Route::post('rounds/{round}/track/prev', [RoundController::class, 'prevTrack'])
    ->name('rounds.track.prev');
Route::post('rounds/{round}/track/next', [RoundController::class, 'nextTrack'])
    ->name('rounds.track.next');

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

// Moderation

Route::post('playlists/{playlist}/moderators/attach', [PlaylistModeratorController::class, 'attach'])
    ->name('playlists.moderators.attach');
Route::delete('playlists/{playlist}/moderators/detach', [PlaylistModeratorController::class, 'detach'])
    ->name('playlists.moderators.detach');

Route::post('rooms/{room}/moderators/attach', [RoomModeratorController::class, 'attach'])
    ->name('rooms.moderators.attach');
Route::delete('rooms/{room}/moderators/detach', [RoomModeratorController::class, 'detach'])
    ->name('rooms.moderators.detach');

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

Route::put('tracks/{track}/answers/{answer}', [TrackAnswerController::class, 'update'])
    ->name('tracks.answers.update')
    ->middleware('auth');

Route::delete('tracks/{track}/answers/{answer}', [TrackAnswerController::class, 'destroy'])
    ->name('tracks.answers.delete')
    ->middleware('auth');

// Images

Route::get('/img/{path}', [ImagesController::class, 'show'])
    ->where('path', '.*')
    ->name('image');

// Music providers
Route::get('providers/deezer/search/track', [DeezerService::class, 'searchTrack'])
    ->name('providers.deezer.search.track');

Route::get('providers/itunes/search/track', [AppleMusicService::class, 'searchTrack'])
    ->name('providers.itunes.search.track');

Route::get('providers/spotify/search/track', [SpotifyService::class, 'searchTrack'])
    ->name('providers.spotify.search.track');
