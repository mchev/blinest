<?php

use App\Http\Controllers\RoomBookmarkController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomMessageController;
use App\Http\Controllers\RoomModeratorController;
use App\Http\Controllers\RoomPlaylistController;

Route::middleware(['auth', 'logout.banned'])->group(function () {

    // Bookmarks
    Route::post('rooms/{room}/bookmark', [RoomBookmarkController::class, 'store'])
        ->name('rooms.bookmarks.store');
    Route::delete('rooms/{room}/bookmark', [RoomBookmarkController::class, 'destroy'])
        ->name('rooms.bookmarks.destroy');

    // Rooms
    Route::put('rooms/{room}/restore', [RoomController::class, 'restore'])
        ->name('rooms.restore');

    Route::put('rooms/{room}/options', [RoomController::class, 'updateOptions'])
        ->name('rooms.options');

    Route::post('rooms/{room}/playlists/attach', [RoomPlaylistController::class, 'attach'])
        ->name('rooms.playlists.attach');

    Route::delete('rooms/{room}/playlists/detach', [RoomPlaylistController::class, 'detach'])
        ->name('rooms.playlists.detach');

    Route::delete('rooms/{room}/picture/delete', [RoomController::class, 'deletePicture'])
        ->name('rooms.picture.delete');

    // Update user count
    Route::post('rooms/{room}/update-user-count', [RoomController::class, 'updateUserCount'])
        ->name('rooms.update-user-count');

    // Rooms Messages
    Route::post('rooms/{room}/message', [RoomMessageController::class, 'store'])
        ->name('rooms.message.store');

    Route::post('rooms/{room}/message/{message}/report', [RoomMessageController::class, 'report'])
        ->name('rooms.message.report');

    Route::delete('messages/{message}', [RoomMessageController::class, 'destroy'])
        ->name('messages.destroy');

    Route::put('messages/{message}/restore', [RoomMessageController::class, 'restore'])
        ->name('messages.restore');

    // Rooms alerts
    Route::post('rooms/{room}/alert', [RoomController::class, 'alert'])
        ->name('rooms.alert');

    Route::post('rooms/{room}/suggestion', [RoomController::class, 'sendSuggestion'])
        ->name('rooms.suggestions');

    // Join
    Route::get('rooms/{room}/joined', [RoomController::class, 'joined'])
        ->name('rooms.joined');

    // Changing route order because of room/slug
    Route::get('create/room', [RoomController::class, 'create'])
        ->name('rooms.create');

    // Show is registered in guests.php (slugs)
    Route::resource('rooms', RoomController::class)
        ->except('show', 'create');

    // ATTACH DETACH MODERATORS
    Route::post('rooms/{room}/moderators/attach', [RoomModeratorController::class, 'attach'])
        ->name('rooms.moderators.attach');

    Route::delete('rooms/{room}/moderators/detach', [RoomModeratorController::class, 'detach'])
        ->name('rooms.moderators.detach');

    // Controls
    Route::post('rooms/{room}/start', [RoomController::class, 'start'])
        ->name('rounds.start');

    // Search existing tracks in room playlists
    Route::post('rooms/{room}/search/tracks', [RoomController::class, 'searchTracks'])
        ->name('rooms.search.tracks');
});
