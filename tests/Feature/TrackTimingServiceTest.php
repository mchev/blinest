<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Room;
use App\Models\Round;
use App\Models\User;
use App\Services\TrackTimingService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrackTimingServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_answer_deadline_is_started_at_plus_room_track_duration(): void
    {
        Carbon::setTestNow('2026-06-09 12:00:00');

        $room = $this->makeRoom(trackDuration: 25);
        $round = Round::create([
            'room_id' => $room->id,
            'finished_at' => null,
            'is_playing' => true,
            'current' => 1,
            'tracks' => [1],
            'current_track_started_at' => now(),
        ]);

        $timing = app(TrackTimingService::class);
        $deadline = $timing->answerDeadlineAt($round);

        $this->assertNotNull($deadline);
        $this->assertTrue($deadline->equalTo(now()->addSeconds(25)));
    }

    public function test_answer_window_stays_open_until_grace_after_deadline(): void
    {
        Carbon::setTestNow('2026-06-09 12:00:00');

        $room = $this->makeRoom(trackDuration: 30);
        $round = Round::create([
            'room_id' => $room->id,
            'finished_at' => null,
            'is_playing' => true,
            'current' => 1,
            'tracks' => [1],
            'current_track_started_at' => now()->subSeconds(30),
        ]);

        $timing = app(TrackTimingService::class);

        $this->assertTrue($timing->isAnswerWindowOpen($round, now()));
        $this->assertFalse($timing->isAnswerWindowOpen($round, now()->addSeconds(1)));
    }

    public function test_speed_bonus_uses_server_elapsed_time(): void
    {
        Carbon::setTestNow('2026-06-09 12:00:00');

        $room = $this->makeRoom(trackDuration: 30);
        $round = Round::create([
            'room_id' => $room->id,
            'finished_at' => null,
            'is_playing' => true,
            'current' => 1,
            'tracks' => [1],
            'current_track_started_at' => now()->subSeconds(4),
        ]);

        $timing = app(TrackTimingService::class);

        $this->assertTrue($timing->hasSpeedBonus($round));
        $this->assertFalse($timing->hasSpeedBonus($round, now()->addSeconds(10)));
    }

    public function test_next_track_at_is_answer_deadline_plus_pause_between_tracks(): void
    {
        Carbon::setTestNow('2026-06-09 12:00:00');

        $room = $this->makeRoom(trackDuration: 30, pauseBetweenTracks: 5);
        $round = Round::create([
            'room_id' => $room->id,
            'finished_at' => null,
            'is_playing' => true,
            'current' => 1,
            'tracks' => [1],
            'current_track_started_at' => now()->subSeconds(31),
        ]);

        $timing = app(TrackTimingService::class);
        $nextTrackAt = $timing->nextTrackAt($round);

        $this->assertNotNull($nextTrackAt);
        $this->assertTrue($nextTrackAt->equalTo(now()->addSeconds(4)));
        $this->assertTrue($timing->isInterTrackPause($round));
    }

    private function makeRoom(int $trackDuration, int $pauseBetweenTracks = 3): Room
    {
        $category = Category::create(['name' => 'Test Category']);
        $owner = User::factory()->create();

        return Room::create([
            'name' => 'Timing Room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'track_duration' => $trackDuration,
            'pause_between_tracks' => $pauseBetweenTracks,
            'tracks_by_round' => 5,
        ]);
    }
}
