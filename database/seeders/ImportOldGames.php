<?php

namespace Database\Seeders;

use App\Models\Old\OldGame;
use App\Models\Playlist;
use App\Models\Room;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImportOldGames extends Seeder
{
    public function run()
    {
        OldGame::whereHas('tracks')->chunk(500, function ($games) {
            $rooms = $games->map(fn ($game) => [
                'id' => $game->id,
                'user_id' => $game->user_id,
                'name' => $game->title,
                'description' => $game->description,
                'category_id' => 1,
                'is_public' => $game->public,
                'password' => $game->password,
                'discord_webhook_url' => $game->discord_webhook_url,
                'color' => $game->color,
                'created_at' => $game->created_at,
                'updated_at' => $game->updated_at,
            ]);

            $playlists = $games->map(fn ($game) => [
                'id' => $game->id,
                'user_id' => $game->user_id,
                'name' => $game->title,
                'description' => $game->description,
                'is_public' => $game->public,
                'created_at' => $game->created_at,
                'updated_at' => $game->updated_at,
            ]);

            $pivot = $games->map(fn ($game) => [
                'playlist_id' => $game->id,
                'room_id' => $game->id,
                'created_at' => $game->created_at,
                'updated_at' => $game->updated_at,
            ]);

            $roomModerator = $games->map(fn ($game) => [
                'user_id' => $game->user_id,
                'moderable_type' => 'App\Models\Room',
                'moderable_id' => $game->id,
            ]);

            $playlistModerator = $games->map(fn ($game) => [
                'user_id' => $game->user_id,
                'moderable_type' => 'App\Models\Playlist',
                'moderable_id' => $game->id,
            ]);

            Room::upsert(
                $rooms->toArray(),
                ['id'],
                ['user_id', 'name', 'description', 'category_id', 'is_public', 'password', 'discord_webhook_url', 'color', 'created_at', 'updated_at']
            );

            Playlist::upsert(
                $playlists->toArray(),
                ['id'],
                ['user_id', 'name', 'description', 'is_public', 'created_at', 'updated_at']
            );

            DB::table('playlist_room')->upsert(
                $pivot->toArray(),
                ['id'],
                ['playlist_id', 'room_id', 'created_at', 'updated_at']
            );

            DB::table('moderables')->upsert(
                $roomModerator->toArray(),
                ['id'],
                ['user_id', 'moderable_type', 'moderable_id']
            );

            DB::table('moderables')->upsert(
                $playlistModerator->toArray(),
                ['id'],
                ['user_id', 'moderable_type', 'moderable_id']
            );

        });
    }
}
