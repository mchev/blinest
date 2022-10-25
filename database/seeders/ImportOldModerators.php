<?php

namespace Database\Seeders;

use App\Models\Old\OldRoleUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImportOldModerators extends Seeder
{
    public function run()
    {
        $roomModerators = OldRoleUser::where('role_id', 3)->where('user_id', '!=', 1)->get()->map(fn ($role) => [
            'user_id' => $role->user_id,
            'moderable_type' => 'App\Models\Room',
            'moderable_id' => $role->game_id,
        ]);

        $playlistModerators = OldRoleUser::where('role_id', 3)->where('user_id', '!=', 1)->get()->map(fn ($role) => [
            'user_id' => $role->user_id,
            'moderable_type' => 'App\Models\Playlist',
            'moderable_id' => $role->game_id,
        ]);

        DB::table('moderables')->upsert(
            $roomModerators->toArray(),
            ['id'],
            ['user_id', 'moderable_type', 'moderable_id']
        );

        DB::table('moderables')->upsert(
            $playlistModerators->toArray(),
            ['id'],
            ['user_id', 'moderable_type', 'moderable_id']
        );
    }
}
