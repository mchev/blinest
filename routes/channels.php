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
    $user->loadMissing('userLevel');

    return [
        'id' => $user->id,
        'name' => $user->name,
        'team' => $user->team,
        'photo' => $user->photo,
        'elo' => $user->elo ?? 1500,
        'level' => $user->userLevel?->level ?? 1,
        'current_xp' => $user->userLevel?->current_xp ?? 0,
        'xp_for_next_level' => $user->userLevel?->xp_for_next_level ?? 100,
        'total_xp' => $user->userLevel?->total_xp ?? 0,
        'level_metrics' => $user->userLevel ? [
            'score_public_rooms' => $user->userLevel->score_public_rooms ?? 0,
            'seniority_months' => $user->userLevel->months_seniority ?? 0,
            'consecutive_days_streak' => $user->userLevel->consecutive_days_streak ?? 0,
            'rooms_created_count' => $user->userLevel->rooms_created_count ?? 0,
            'playlists_created_count' => $user->userLevel->playlists_created_count ?? 0,
            'tracks_liked_count' => $user->userLevel->tracks_liked_count ?? 0,
            'has_team' => $user->team !== null,
        ] : null,
        'score' => [
            'answers' => [],
            'points' => 0,
            'total' => $room->currentRound()->exists()
                ? $room->currentRound()->first()->userScore($user)
                : 0,
        ],
    ];
});

Broadcast::channel('chat-room.{id}', function ($user) {
    return [
        'id' => $user->id,
        'name' => $user->name,
        'team' => $user->team,
        'photo' => $user->photo,
    ];
});

Broadcast::channel('room.count.{room}', function ($user, Room $room) {
    return true;
});

// Canal pour les réactions sur un message du chat
Broadcast::channel('chat.message.{messageId}', function ($user, $messageId) {
    return (bool) $user;
});
