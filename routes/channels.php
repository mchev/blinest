<?php

use App\Models\Room;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('rooms.{room}', function ($user, Room $room) {
    return [
        'id' => $user->id,
        'name' => $user->name,
        'team' => $user->team,
        'score' => [
            'answers' => [],
            'points' => 0,
            'total' => $room->currentRound()->exists()
                ? $room->currentRound()->first()->userScore($user)
                : 0,
        ],
    ];
});
