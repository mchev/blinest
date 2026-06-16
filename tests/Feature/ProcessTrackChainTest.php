<?php

namespace Tests\Feature;

use App\Events\TrackEnded;
use App\Events\TrackPlayed;
use App\Jobs\ProcessTrackEnded;
use App\Jobs\ProcessTrackPlayed;
use App\Models\AnswerType;
use App\Models\Category;
use App\Models\Playlist;
use App\Models\Room;
use App\Models\Track;
use App\Models\TrackAnswer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ProcessTrackChainTest extends TestCase
{
    use RefreshDatabase;

    public function test_process_track_played_advances_to_next_track_after_pause(): void
    {
        Carbon::setTestNow('2026-06-09 12:00:00');

        Event::fake();
        Queue::fake();

        [$round, $tracks] = $this->makeRoundWithTracks(2);

        $round->playNextTrack();
        $round->refresh();

        $this->assertSame(1, $round->current);
        $this->assertNotNull($round->current_track_started_at);

        $deadline = $round->current_track_started_at->copy()->addSeconds(30);
        Carbon::setTestNow($deadline);

        (new ProcessTrackPlayed($round))->handle();

        Event::assertDispatched(TrackEnded::class);

        $pauseEndsAt = $deadline->copy()->addSeconds($round->room->pause_between_tracks);
        Carbon::setTestNow($pauseEndsAt);

        (new ProcessTrackEnded($round))->handle();
        $round->refresh();

        $this->assertSame(2, $round->current);
        Event::assertDispatched(TrackPlayed::class, 2);
    }

    /**
     * @return array{0: \App\Models\Round, 1: array<int, Track>}
     */
    private function makeRoundWithTracks(int $count): array
    {
        $category = Category::create(['name' => 'Cat']);
        $owner = User::factory()->create();

        $room = Room::create([
            'name' => 'Chain Room',
            'slug' => 'chain-room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'is_playing' => true,
            'is_autostart' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
            'pause_between_tracks' => 5,
        ]);

        $playlist = Playlist::create([
            'name' => 'P',
            'user_id' => $owner->id,
        ]);
        $answerType = AnswerType::create(['name' => 'Artist']);

        $tracks = [];
        for ($i = 1; $i <= $count; $i++) {
            $track = Track::create([
                'playlist_id' => $playlist->id,
                'user_id' => $owner->id,
                'provider' => 'youtube',
                'provider_id' => 'yt-'.$i,
                'preview_url' => 'https://www.youtube.com/watch?v=abc'.$i,
                'artwork_url' => 'https://example.com/art-'.$i,
            ]);
            TrackAnswer::forceCreate([
                'track_id' => $track->id,
                'answer_type_id' => $answerType->id,
                'value' => 'Answer '.$i,
                'score' => 5.0,
            ]);
            $tracks[] = $track;
        }

        $round = $room->rounds()->create([
            'current' => 0,
            'is_playing' => true,
        ]);
        $round->forceFill(['tracks' => collect($tracks)->pluck('id')->all()])->save();

        return [$round->fresh()->load('room'), $tracks];
    }
}
