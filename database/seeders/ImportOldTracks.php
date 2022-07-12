<?php

namespace Database\Seeders;

use App\Models\Old\OldTrack;
use App\Models\Track;
use App\Models\TrackAnswer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImportOldTracks extends Seeder
{
    public function run()
    {
        $count = OldTrack::count();
        $bar = $this->command->getOutput()->createProgressBar($count);

        OldTrack::chunk(1000, function ($tracks) use($bar) {

            DB::beginTransaction();

            $newTracks = $tracks->map(fn ($track) => [
                'id' => $track->id,
                'playlist_id' => $track->game_id,
                'user_id' => $track->user_id,
                'provider' => $track->provider,
                'provider_id' => $track->provider_item_id,
                'preview_url' => $track->preview_url,
                'artwork_url' => $track->artwork_url,
                'counter' => $track->hit,
                'created_at' => $track->created_at,
                'updated_at' => $track->updated_at,
            ]);

            $artistAnswers = $tracks->whereNotNull('artist_name')
                ->where('artist_name', '!=', '')
                ->map(fn ($track) => [
                    'track_id' => $track->id,
                    'answer_type_id' => 1, // Artist
                    'value' => $track->artist_name,
                    'score' => 0.5,
                ]);

            $titleAnswers = $tracks->whereNotNull('track_name')
                ->where('track_name', '!=', '')
                ->map(fn ($track) => [
                    'track_id' => $track->id,
                    'answer_type_id' => 2, // Title
                    'value' => $track->track_name,
                    'score' => 0.5,
                ]);

            $customAnswers = $tracks->whereNotNull('custom_answer')
                ->where('custom_answer', '!=', '')
                ->map(fn ($track) => [
                    'track_id' => $track->id,
                    'answer_type_id' => 2, // Title
                    'value' => $track->custom_answer,
                    'score' => 1,
                ]);

            $answers = $artistAnswers->merge($titleAnswers)->merge($customAnswers);

            Track::upsert(
                $newTracks->toArray(),
                ['id'],
                ['playlist_id', 'user_id', 'provider', 'provider_id', 'preview_url', 'artwork_url', 'counter', 'created_at', 'updated_at']
            );

            TrackAnswer::upsert(
                $answers->toArray(),
                ['id'],
                ['track_id', 'answer_type_id', 'value', 'score']
            );

            DB::commit();

            $bar->advance(1000);

        });

        $bar->finish();
        $this->command->line('');
    }
}
