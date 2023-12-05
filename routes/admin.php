<?php

use App\Http\Controllers\Admin\AnswerTypeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FAQController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PlaylistController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StorageMigrationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
*/

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'auth.administrator']], function () {

    // Storage Migration
    Route::get('/migrate', [StorageMigrationController::class, 'migrate']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Users
    Route::get('users', [UserController::class, 'index'])
        ->name('users');

    Route::get('users/create', [UserController::class, 'create'])
        ->name('users.create');

    Route::post('users', [UserController::class, 'store'])
        ->name('users.store');

    Route::get('users/{user}/edit', [UserController::class, 'edit'])
        ->name('users.edit');

    Route::put('users/{user}', [UserController::class, 'update'])
        ->name('users.update');

    Route::delete('users/{user}', [UserController::class, 'destroy'])
        ->name('users.destroy');

    Route::put('users/{user}/restore', [UserController::class, 'restore'])
        ->name('users.restore');

    // Teams
    Route::get('teams', [TeamController::class, 'index'])
        ->name('teams');

    Route::get('teams/create', [TeamController::class, 'create'])
        ->name('teams.create');

    Route::post('teams', [TeamController::class, 'store'])
        ->name('teams.store');

    Route::get('teams/{team}/edit', [TeamController::class, 'edit'])
        ->name('teams.edit');

    Route::put('teams/{team}', [TeamController::class, 'update'])
        ->name('teams.update');

    Route::delete('teams/{team}', [TeamController::class, 'destroy'])
        ->name('teams.destroy');

    Route::put('teams/{team}/restore', [TeamController::class, 'restore'])
        ->name('teams.restore');

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

    // Rooms
    Route::get('rooms', [RoomController::class, 'index'])
        ->name('rooms');

    Route::get('rooms/create', [RoomController::class, 'create'])
        ->name('rooms.create');

    Route::post('rooms', [RoomController::class, 'store'])
        ->name('rooms.store');

    Route::get('rooms/{room}/edit', [RoomController::class, 'edit'])
        ->name('rooms.edit');

    Route::put('rooms/{room}', [RoomController::class, 'update'])
        ->name('rooms.update');

    Route::delete('rooms/{room}', [RoomController::class, 'destroy'])
        ->name('rooms.destroy');

    Route::put('rooms/{room}/restore', [RoomController::class, 'restore'])
        ->name('rooms.restore');

    // Categories
    Route::resource('categories', CategoryController::class)
        ->except('show');

    // Answer Types
    Route::resource('answer_types', AnswerTypeController::class)
        ->except('show');

    // Pages
    Route::resource('pages', PageController::class)
        ->except('show');

    // FAQ
    Route::resource('faqs', FAQController::class)
        ->except('show');
});
