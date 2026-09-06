<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Playlist;
use App\Models\Room;
use App\Models\Round;
use App\Models\Track;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;
use Tests\Concerns\AddsRoomPresence;
use Tests\TestCase;

class RoundParticipationAuthorizationTest extends TestCase
{
    use AddsRoomPresence;
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

    public function test_outsider_cannot_read_round_scores(): void
    {
        [$round, $track, $participant, $outsider] = $this->createActiveRound();

        $this->actingAs($outsider)
            ->getJson(route('rounds.scores', $round))
            ->assertForbidden();
    }

    public function test_outsider_cannot_submit_answers(): void
    {
        [$round, $track, $participant, $outsider] = $this->createActiveRound();

        $this->actingAs($outsider)
            ->postJson(route('rounds.track.check', [$round, $track]), [
                'text' => 'Daft Punk',
                'words' => [],
                'currentTime' => 3.0,
            ])
            ->assertForbidden();
    }

    public function test_outsider_cannot_mark_track_as_listened(): void
    {
        [$round, $track, $participant, $outsider] = $this->createActiveRound();

        $this->actingAs($outsider)
            ->postJson(route('rounds.tracks.listened', [$round, $track]))
            ->assertForbidden();
    }

    public function test_room_participant_can_read_round_scores(): void
    {
        [$round, $track, $participant] = $this->createActiveRound();

        $this->actingAs($participant)
            ->getJson(route('rounds.scores', $round))
            ->assertOk()
            ->assertJsonStructure(['scores']);
    }

    /**
     * @return array{0: Round, 1: Track, 2: User, 3?: User}
     */
    private function createActiveRound(bool $withOutsider = true): array
    {
        $category = Category::factory()->create();
        $owner = User::factory()->create();
        $participant = User::factory()->create();
        $outsider = $withOutsider ? User::factory()->create() : null;

        $room = Room::factory()->create([
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => false,
            'is_featured' => false,
            'password' => 'secret-room-password',
            'track_duration' => 30,
            'deleted_at' => null,
        ]);

        $playlist = Playlist::query()->create([
            'name' => 'Private playlist',
            'user_id' => $owner->id,
        ]);

        $track = Track::query()->create([
            'playlist_id' => $playlist->id,
            'user_id' => $owner->id,
            'provider' => 'youtube',
            'provider_id' => 'private-track',
            'preview_url' => 'private-track',
            'artwork_url' => 'https://example.com/artwork.jpg',
        ]);

        $round = Round::query()->create([
            'room_id' => $room->id,
            'finished_at' => null,
            'is_playing' => true,
            'current' => 1,
            'tracks' => [$track->id],
        ]);

        $this->addUserToRoomPresence($room, $participant);

        if ($withOutsider) {
            return [$round, $track, $participant, $outsider];
        }

        return [$round, $track, $participant];
    }
}
