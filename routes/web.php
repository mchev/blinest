<?php

use App\Http\Controllers\FAQController;
use App\Http\Controllers\LocalTrackController;
use App\Http\Controllers\ModerationController;
use App\Http\Controllers\PlaylistController;
// Moderation
use App\Http\Controllers\PlaylistModeratorController;
use App\Http\Controllers\ProfileController;
// Teams
use App\Http\Controllers\RankingController;
use App\Http\Controllers\RoundController;
// Tracks
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TeamRequestController;
use App\Http\Controllers\TrackAnswerController;
// Users
use App\Http\Controllers\TrackController;
use App\Http\Controllers\UserBanController;
// Music Providers Services
use App\Http\Controllers\UserController;
use App\Services\MusicProviders\AppleMusicService;
use App\Services\MusicProviders\AudiusService;
use App\Services\MusicProviders\DeezerService;
use App\Services\MusicProviders\LocalTrackService;
use App\Services\MusicProviders\SpotifyService;
use App\Services\MusicProviders\YouTubeMusicService;
use App\Services\MusicProviders\YoutubeWithoutApiService;
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

Route::middleware(['auth'])->group(function () {

    // Me
    Route::get('me', [UserController::class, 'show'])
        ->name('me');

    // Profile
    Route::get('profile/{user}', [ProfileController::class, 'show'])
        ->name('user.profile');
    Route::delete('profile/likes/{track}', [ProfileController::class, 'unlikeTrack'])
        ->name('profiles.likes.delete');

    // FAQ
    Route::post('faq/{faq}/vote/up', [FAQController::class, 'upvote']);
    Route::post('faq/{faq}/vote/down', [FAQController::class, 'downvote']);

    // Users
    Route::get('users/{user}', [UserController::class, 'show'])
        ->name('users.show');
    Route::post('users/{user}', [UserController::class, 'update'])
        ->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])
        ->name('users.destroy');
});

Route::middleware(['auth', 'logout.banned'])->group(function () {

    // Check user answer
    Route::post('rounds/{round}/tracks/{track}/check', [RoundController::class, 'check'])
        ->name('rounds.track.check');

    // Public moderation group
    Route::middleware('auth.moderator')->group(function () {
        Route::get('/moderation', [ModerationController::class, 'index'])
            ->name('moderation.index');
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
    Route::post('teams/{team}/members/{user}/remove', [TeamController::class, 'removeMember']);

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

    Route::post('playlists/{playlist}/providers/find', [PlaylistController::class, 'findPlaylistByProvider'])
        ->name('playlists.providers.find');

    Route::post('playlists/{playlist}/providers/import', [PlaylistController::class, 'importPlaylistFromProvider'])
        ->name('playlists.providers.import');

    Route::post('local-tracks', [LocalTrackController::class, 'store'])
        ->name('local-tracks.store');

    // Moderation

    Route::post('playlists/{playlist}/moderators/attach', [PlaylistModeratorController::class, 'attach'])
        ->name('playlists.moderators.attach');
    Route::delete('playlists/{playlist}/moderators/detach', [PlaylistModeratorController::class, 'detach'])
        ->name('playlists.moderators.detach');

    // User Bans
    Route::post('/users/{user}/ban', [UserBanController::class, 'store'])
        ->name('user.ban');
    Route::delete('/users/{user}/unban', [UserBanController::class, 'destroy'])
        ->name('user.unban');

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

}); // End Auth/Banned middleware

// Music providers
Route::get('providers/deezer/search/track', [DeezerService::class, 'searchTrack'])
    ->name('providers.deezer.search.track');

Route::get('providers/itunes/search/track', [AppleMusicService::class, 'searchTrack'])
    ->name('providers.itunes.search.track');

Route::get('providers/spotify/search/track', [SpotifyService::class, 'searchTrack'])
    ->name('providers.spotify.search.track');

Route::get('providers/audius/search/track', [AudiusService::class, 'searchTrack'])
    ->name('providers.audius.search.track');

Route::get('providers/youtube/search/track', [YoutubeWithoutApiService::class, 'searchTrack'])
    ->name('providers.youtube.search.track')
    ->middleware('throttle:10,1');

Route::get('providers/youtubeapi/search/track', [YouTubeMusicService::class, 'searchTrack'])
    ->name('providers.youtubeapi.search.track')
    ->middleware('throttle:10,1');

Route::get('providers/local/search/track', [LocalTrackService::class, 'searchTrack'])
    ->name('providers.local.search.track');

Route::get('/local-track/{track}/audio', [LocalTrackController::class, 'audio'])->name('local.track.audio');

require __DIR__.'/auth.php';
require __DIR__.'/guests.php';
require __DIR__.'/rooms.php';
require __DIR__.'/admin.php';

Route::middleware(['auth', 'verified', 'auth.moderator'])->prefix('moderation')->name('moderation.')->group(function () {
    // Dashboard
    Route::get('/', [App\Http\Controllers\Moderation\DashboardController::class, 'index'])->name('dashboard');

    // Trashed Messages
    Route::get('/trashed-messages', [App\Http\Controllers\Moderation\TrashedMessageController::class, 'index'])->name('trashed-messages.index');
    Route::post('/trashed-messages/{message}/restore', [App\Http\Controllers\Moderation\TrashedMessageController::class, 'restore'])->name('trashed-messages.restore');
    Route::delete('/trashed-messages/{message}', [App\Http\Controllers\Moderation\TrashedMessageController::class, 'destroy'])->name('trashed-messages.destroy');

    // Banned Users
    Route::get('/banned-users', [App\Http\Controllers\Moderation\BannedUserController::class, 'index'])->name('banned-users.index');
    Route::post('/banned-users/{user}/unban', [App\Http\Controllers\Moderation\BannedUserController::class, 'unban'])->name('banned-users.unban');
    Route::post('/banned-users/{user}/ban', [App\Http\Controllers\Moderation\BannedUserController::class, 'ban'])->name('banned-users.ban');

    // User Management
    Route::get('/users', [App\Http\Controllers\Moderation\UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [App\Http\Controllers\Moderation\UserManagementController::class, 'show'])->name('users.show');
    Route::post('/users/{user}/warn', [App\Http\Controllers\Moderation\UserManagementController::class, 'warn'])->name('users.warn');
    Route::post('/users/{user}/mute', [App\Http\Controllers\Moderation\UserManagementController::class, 'mute'])->name('users.mute');
    Route::post('/users/{user}/unmute', [App\Http\Controllers\Moderation\UserManagementController::class, 'unmute'])->name('users.unmute');
    Route::post('/users/{user}/ban', [App\Http\Controllers\Moderation\UserManagementController::class, 'ban'])->name('users.ban');

    // Moderators
    Route::get('/moderators', [App\Http\Controllers\Moderation\ModeratorController::class, 'index'])->name('moderators.index');
    Route::post('/moderators/{user}', [App\Http\Controllers\Moderation\ModeratorController::class, 'store'])->name('moderators.store');
    Route::delete('/moderators/{user}', [App\Http\Controllers\Moderation\ModeratorController::class, 'destroy'])->name('moderators.destroy');

    // Tracks Manager
    Route::get('/tracks', [\App\Http\Controllers\Moderation\LocalTrackController::class, 'index'])->name('tracks.index');
    Route::delete('/tracks/{localTrack}', [\App\Http\Controllers\Moderation\LocalTrackController::class, 'destroy'])->name('tracks.destroy');
    Route::put('/tracks/{localTrack}', [\App\Http\Controllers\Moderation\LocalTrackController::class, 'update'])->name('tracks.update');

});
