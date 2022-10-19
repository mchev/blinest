<?php

use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomMessageController;
use App\Http\Controllers\RoomModeratorController;
use App\Http\Controllers\RoomPlaylistController;

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

    // Rooms
    Route::put('rooms/{room}/restore', [RoomController::class, 'restore'])
        ->name('rooms.restore');

    Route::post('rooms/{room}/playlists/attach', [RoomPlaylistController::class, 'attach'])
        ->name('rooms.playlists.attach');

    Route::delete('rooms/{room}/playlists/detach', [RoomPlaylistController::class, 'detach'])
        ->name('rooms.playlists.detach');

    // Rooms Messages
    Route::post('rooms/{room}/message', [RoomMessageController::class, 'store'])
        ->name('rooms.message.store');

    Route::post('rooms/{room}/message/{message}/report', [RoomMessageController::class, 'report'])
        ->name('rooms.message.report');

    // Rooms alerts
    Route::post('rooms/{room}/alert', [RoomController::class, 'alert'])
        ->name('rooms.alert');

    Route::post('rooms/{room}/suggestion', [RoomController::class, 'sendSuggestion'])
        ->name('rooms.suggestions');

    Route::post('rooms/{room}/generate/mosaic', [RoomController::class, 'generateMosaic'])
        ->name('rooms.generate.mosaic');

    Route::get('rooms/{room}/joined', [RoomController::class, 'joined'])
        ->name('rooms.joined');

    Route::resource('rooms', RoomController::class);

    // ATTACH DETACH MODERATORS
    Route::post('rooms/{room}/moderators/attach', [RoomModeratorController::class, 'attach'])
        ->name('rooms.moderators.attach');

    Route::delete('rooms/{room}/moderators/detach', [RoomModeratorController::class, 'detach'])
        ->name('rooms.moderators.detach');
});
