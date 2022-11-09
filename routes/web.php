<?php

use App\Http\Controllers\ImagesController;
use App\Http\Controllers\ModerationController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\PlaylistModeratorController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\RoundController;
use App\Http\Controllers\TeamController;
// Moderation
use App\Http\Controllers\TeamRequestController;
// Tracks
use App\Http\Controllers\TrackAnswerController;
use App\Http\Controllers\TrackController;
// Socialite
use App\Http\Controllers\UserController;
// Music Providers Services
use App\Services\MusicProviders\AppleMusicService;
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

// Check user answer
Route::post('rounds/{round}/tracks/{track}/check', [RoundController::class, 'check'])
    ->name('rounds.track.check');

Route::middleware('auth')->group(function () {

    // Me
    Route::get('me', [UserController::class, 'show'])
        ->name('me');

    // Users
    Route::get('users/{user}', [UserController::class, 'show'])
        ->name('users.show');
    Route::post('users/{user}', [UserController::class, 'update'])
        ->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])
        ->name('users.destroy');

});

Route::middleware('auth', 'forbid-banned-user')->group(function () {

    // Public moderation group
    Route::middleware('auth.moderator')->group(function () {
        Route::get('/moderation', [ModerationController::class, 'index'])
            ->name('moderation.index');
        Route::put('moderation/messages/{message}/restore', [ModerationController::class, 'restoreMessage'])
            ->name('moderation.messages.restore');
        Route::get('moderation/users/{user}/informations', [ModerationController::class, 'fetchUserInformations'])
            ->name('moderation.users.informations');
    });

    // Teams
    Route::post('teams/{team}/request', [TeamRequestController::class, 'store']);
    Route::post('teams/{team}/request/cancel', [TeamRequestController::class, 'cancel']);
    Route::post('teams/requests/{teamRequest}/accept', [TeamRequestController::class, 'accept']);
    Route::post('teams/requests/{teamRequest}/decline', [TeamRequestController::class, 'decline']);
    Route::post('teams/{team}/leave', [TeamController::class, 'leave']);
    Route::post('teams/{team}/owner/{user}', [TeamController::class, 'switchOwner']);

    Route::resource('teams', TeamController::class);

    // Notifications
    Route::post('/users/notifications/{notification}/read', [UserController::class, 'markNotificationAsRead']);
    Route::post('/users/notifications/{notification}/done', [UserController::class, 'markNotificationAsDone']);

    // Ranking
    Route::get('rankings', [RankingController::class, 'index'])
        ->name('rankings.index');

    Route::get('rooms/{room}/scores', [RankingController::class, 'roomScores'])
        ->name('rooms.scores.index');

    // Controls
    Route::post('rounds/{round}/stop', [RoundController::class, 'stop'])
        ->name('rounds.stop');
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

    Route::get('playlists/{playlist}/export', [PlaylistController::class, 'export'])
        ->name('playlists.export');

    // Moderation

    Route::post('playlists/{playlist}/moderators/attach', [PlaylistModeratorController::class, 'attach'])
        ->name('playlists.moderators.attach');
    Route::delete('playlists/{playlist}/moderators/detach', [PlaylistModeratorController::class, 'detach'])
        ->name('playlists.moderators.detach');

    Route::delete('moderation/messages/{message}', [ModerationController::class, 'destroyMessage'])
        ->name('moderation.message.destroy');
    Route::post('moderation/users/{user}/ban', [ModerationController::class, 'banUser'])
        ->name('moderation.user.ban');
    Route::post('moderation/users/{user}/unban', [ModerationController::class, 'unbanUser'])
        ->name('moderation.user.unban');

    // Tracks
    Route::get('playlists/{playlist}/tracks', [TrackController::class, 'index'])
        ->name('playlists.tracks');

    Route::post('playlists/{playlist}/tracks', [TrackController::class, 'store'])
        ->name('playlists.tracks.store');

    Route::put('playlists/{playlist}/tracks/{track}', [TrackController::class, 'update'])
        ->name('playlists.tracks.update');

    Route::delete('playlists/{playlist}/tracks/{track}', [TrackController::class, 'destroy'])
        ->name('playlists.tracks.delete');

    Route::put('tracks/{track}', [TrackController::class, 'update'])
        ->name('tracks.update');

    Route::get('playlists/{playlist}/tracks/search', [TrackController::class, 'search'])
        ->name('tracks.search');

    // Tracks Answers
    Route::post('tracks/{track}/answers', [TrackAnswerController::class, 'store'])
        ->name('tracks.answers.store');

    Route::put('tracks/{track}/answers/{answer}', [TrackAnswerController::class, 'update'])
        ->name('tracks.answers.update');

    Route::delete('tracks/{track}/answers/{answer}', [TrackAnswerController::class, 'destroy'])
        ->name('tracks.answers.delete');

    // Tracks Votes
    Route::post('rooms/{room}/tracks/{track}/downvote', [TrackController::class, 'downvote'])
        ->name('tracks.downvote');
    Route::post('rooms/{room}/tracks/{track}/upvote', [TrackController::class, 'upvote'])
        ->name('tracks.upvote');
}); // End Auth middleware

// Music providers
Route::get('providers/deezer/search/track', [DeezerService::class, 'searchTrack'])
    ->name('providers.deezer.search.track');

Route::get('providers/itunes/search/track', [AppleMusicService::class, 'searchTrack'])
    ->name('providers.itunes.search.track');

Route::get('providers/spotify/search/track', [SpotifyService::class, 'searchTrack'])
    ->name('providers.spotify.search.track');

// Images

Route::get('/img/{path}', [ImagesController::class, 'show'])
    ->where('path', '.*')
    ->name('image');

require __DIR__.'/auth.php';
