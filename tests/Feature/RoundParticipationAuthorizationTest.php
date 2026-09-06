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
use Tests\Concerns\GrantsRoomParticipation;
use Tests\TestCase;

class RoundParticipationAuthorizationTest extends TestCase
{
    use GrantsRoomParticipation;
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
        [$round, $track, $participant, $outsider] = $this->createActiveRound(grantParticipant: true);

        $this->actingAs($outsider)
            ->getJson(route('rounds.scores', $round))
            ->assertForbidden();
    }

    public function test_outsider_cannot_submit_answers(): void
    {
        [$round, $track, $participant, $outsider] = $this->createActiveRound(grantParticipant: true);

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
        [$round, $track, $participant, $outsider] = $this->createActiveRound(grantParticipant: true);

        $this->actingAs($outsider)
            ->postJson(route('rounds.tracks.listened', [$round, $track]))
            ->assertForbidden();
    }

    public function test_room_participant_can_read_round_scores(): void
    {
        [$round, , $participant] = $this->createActiveRound(grantParticipant: true);

        $this->actingAs($participant)
            ->getJson(route('rounds.scores', $round))
            ->assertOk()
            ->assertJsonStructure(['scores']);
    }

    public function test_joined_requires_room_access_session(): void
    {
        [$round, $track, $participant, $outsider, $room] = $this->createActiveRound(grantParticipant: false);

        $this->actingAs($participant)
            ->getJson("/rooms/{$room->id}/joined")
            ->assertForbidden();

        $this->grantRoomParticipation($room, $participant);

        $this->actingAs($participant)
            ->getJson("/rooms/{$room->id}/joined")
            ->assertOk();
    }

    public function test_show_grants_room_access_for_gameplay(): void
    {
        [$round, $track, $participant, $outsider, $room] = $this->createActiveRound(
            grantParticipant: false,
            passwordProtected: false,
        );

        $this->actingAs($participant)
            ->get(route('rooms.show', $room->slug))
            ->assertOk();

        $this->actingAs($participant)
            ->postJson(route('rounds.track.check', [$round, $track]), [
                'text' => 'test',
                'words' => [],
                'currentTime' => 3.0,
            ])
            ->assertOk();

        $this->actingAs($outsider)
            ->postJson(route('rounds.track.check', [$round, $track]), [
                'text' => 'test',
                'words' => [],
                'currentTime' => 3.0,
            ])
            ->assertForbidden();
    }

    public function test_password_protected_room_requires_password_before_gameplay(): void
    {
        [$round, $track, $participant, $outsider, $room] = $this->createActiveRound(grantParticipant: false);

        $this->actingAs($participant)
            ->get(route('rooms.show', $room->slug))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('Rooms/Password'));

        $this->actingAs($participant)
            ->get(route('rooms.show', ['room' => $room->slug, 'password' => 'secret-room-password']))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('Rooms/Show'));

        $this->actingAs($participant)
            ->postJson(route('rounds.track.check', [$round, $track]), [
                'text' => 'test',
                'words' => [],
                'currentTime' => 3.0,
            ])
            ->assertOk();
    }

    /**
     * @return array{0: Round, 1: Track, 2: User, 3: User, 4: Room}
     */
    private function createActiveRound(bool $grantParticipant = true, bool $passwordProtected = true): array
    {
        $category = Category::factory()->create();
        $owner = User::factory()->create();
        $participant = User::factory()->create();
        $outsider = User::factory()->create();

        $room = Room::factory()->create([
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => ! $passwordProtected,
            'is_featured' => false,
            'password' => $passwordProtected ? 'secret-room-password' : null,
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

        if ($grantParticipant) {
            $this->grantRoomParticipation($room, $participant);
        }

        return [$round, $track, $participant, $outsider, $room];
    }
}
