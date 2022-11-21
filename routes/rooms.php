<?php

use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomMessageController;
use App\Http\Controllers\RoomModeratorController;
use App\Http\Controllers\RoomPlaylistController;

Route::middleware('auth', 'forbid-banned-user')->group(function () {

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

    Route::get('rooms/{room}/joined', [RoomController::class, 'joined'])
        ->name('rooms.joined');

    Route::resource('rooms', RoomController::class);

    // ATTACH DETACH MODERATORS
    Route::post('rooms/{room}/moderators/attach', [RoomModeratorController::class, 'attach'])
        ->name('rooms.moderators.attach');

    Route::delete('rooms/{room}/moderators/detach', [RoomModeratorController::class, 'detach'])
        ->name('rooms.moderators.detach');

    // Controls
    Route::post('rooms/{room}/start', [RoomController::class, 'start'])
        ->name('rounds.start');
});
