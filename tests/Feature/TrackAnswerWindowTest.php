<?php

namespace Tests\Feature;

use App\Models\AnswerType;
use App\Models\Category;
use App\Models\Playlist;
use App\Models\Room;
use App\Models\Round;
use App\Models\Track;
use App\Models\TrackAnswer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class TrackAnswerWindowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        try {
            Redis::ping();
            Redis::flushall();
        } catch (\Exception $e) {
            $this->markTestSkipped('Redis is not available: '.$e->getMessage());
        }
    }

    protected function tearDown(): void
    {
        try {
            Redis::flushall();
        } catch (\Exception $e) {
            // ignore
        }

        parent::tearDown();
    }

    public function test_check_rejects_answer_after_server_answer_window(): void
    {
        Carbon::setTestNow('2026-06-09 12:00:00');

        [$round, $track, $player] = $this->makePlayingRound(startedSecondsAgo: 31);

        $response = $this->actingAs($player)->postJson(route('rounds.track.check', [$round, $track]), [
            'text' => 'Test Artist',
            'words' => [],
            'currentTime' => 10.0,
        ]);

        $response->assertStatus(409);
        $response->assertJson(['error' => 'answer_window_closed']);
    }

    public function test_check_accepts_answer_inside_server_answer_window(): void
    {
        Carbon::setTestNow('2026-06-09 12:00:00');

        [$round, $track, $player] = $this->makePlayingRound(startedSecondsAgo: 10);

        $response = $this->actingAs($player)->postJson(route('rounds.track.check', [$round, $track]), [
            'text' => 'Test Artist',
            'words' => [],
            'currentTime' => 10.0,
        ]);

        $response->assertOk();
        $response->assertJsonPath('message.type', 'good');
    }

    public function test_room_time_endpoint_returns_server_time(): void
    {
        Carbon::setTestNow('2026-06-09 12:00:00');

        $room = $this->makeRoom();
        $player = User::factory()->create();

        $response = $this->actingAs($player)->getJson(route('rooms.time', $room));

        $response->assertOk();
        $response->assertJsonPath('server_time', now()->toIso8601String());
    }

    /**
     * @return array{0: Round, 1: Track, 2: User}
     */
    private function makePlayingRound(int $startedSecondsAgo): array
    {
        $room = $this->makeRoom();
        $player = User::factory()->create(['elo' => 1500]);
        $playlist = Playlist::create([
            'name' => 'Test Playlist',
            'user_id' => $room->user_id,
        ]);
        $answerType = AnswerType::create(['name' => 'Artist']);

        $track = Track::create([
            'playlist_id' => $playlist->id,
            'user_id' => $room->user_id,
            'provider' => 'youtube',
            'provider_id' => 'test123',
            'preview_url' => 'https://example.com/preview',
            'artwork_url' => 'https://example.com/artwork',
        ]);

        TrackAnswer::create([
            'track_id' => $track->id,
            'answer_type_id' => $answerType->id,
            'value' => 'Test Artist',
            'score' => 5.0,
        ]);

        TrackAnswer::create([
            'track_id' => $track->id,
            'answer_type_id' => $answerType->id,
            'value' => 'Second Answer',
            'score' => 3.0,
        ]);

        $round = Round::create([
            'room_id' => $room->id,
            'finished_at' => null,
            'is_playing' => true,
            'current' => 1,
            'tracks' => [$track->id],
            'current_track_started_at' => now()->subSeconds($startedSecondsAgo),
        ]);

        Cache::forget('track-'.$track->id.'-answers');

        return [$round, $track, $player];
    }

    private function makeRoom(): Room
    {
        $category = Category::create(['name' => 'Test Category']);
        $owner = User::factory()->create();

        return Room::create([
            'name' => 'Answer Window Room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 5,
        ]);
    }
}
