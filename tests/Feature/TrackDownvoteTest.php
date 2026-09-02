<?php

namespace Tests\Feature;

use App\Enums\TrackDownvoteReason;
use App\Models\Category;
use App\Models\Playlist;
use App\Models\Room;
use App\Models\Track;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class TrackDownvoteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Bus::fake();
    }

    public function test_authenticated_user_can_downvote_track_with_reason(): void
    {
        [$user, $room, $track] = $this->createRoomWithTrack();

        $this->actingAs($user)
            ->post($this->downvoteUrl($room, $track), [
                'reason' => TrackDownvoteReason::Difficulty->value,
            ])
            ->assertSuccessful();

        $this->assertDatabaseHas('votes', [
            'user_id' => $user->id,
            'votable_id' => $track->id,
            'votable_type' => $track->getMorphClass(),
            'votes' => -1,
            'downvote_reason' => TrackDownvoteReason::Difficulty->value,
        ]);
    }

    public function test_downvote_requires_reason_when_not_already_downvoted(): void
    {
        [$user, $room, $track] = $this->createRoomWithTrack();

        $this->actingAs($user)
            ->post($this->downvoteUrl($room, $track), [])
            ->assertInvalid(['reason']);
    }

    public function test_user_can_remove_existing_downvote_without_reason(): void
    {
        [$user, $room, $track] = $this->createRoomWithTrack();

        Vote::query()->create([
            'user_id' => $user->id,
            'votable_id' => $track->id,
            'votable_type' => $track->getMorphClass(),
            'votes' => -1,
            'downvote_reason' => TrackDownvoteReason::Other->value,
        ]);

        $this->actingAs($user)
            ->post($this->downvoteUrl($room, $track), [])
            ->assertSuccessful();

        $this->assertDatabaseMissing('votes', [
            'user_id' => $user->id,
            'votable_id' => $track->id,
            'votable_type' => $track->getMorphClass(),
        ]);
    }

    public function test_playlist_edit_shows_downvote_breakdown_and_filters_by_reason(): void
    {
        [$owner, $room, $trackWithReason] = $this->createRoomWithTrack();
        $playlist = $trackWithReason->playlist;

        $otherTrack = Track::query()->create([
            'playlist_id' => $playlist->id,
            'provider' => 'youtube',
            'provider_id' => 'other-track',
            'provider_url' => 'https://youtube.com/watch?v=other-track',
            'preview_url' => 'other-track',
            'artwork_url' => 'https://example.com/art.jpg',
            'user_id' => $owner->id,
        ]);

        Vote::query()->create([
            'user_id' => $owner->id,
            'votable_id' => $trackWithReason->id,
            'votable_type' => $trackWithReason->getMorphClass(),
            'votes' => -1,
            'downvote_reason' => TrackDownvoteReason::SoundQuality->value,
        ]);

        Vote::query()->create([
            'user_id' => User::factory()->create()->id,
            'votable_id' => $trackWithReason->id,
            'votable_type' => $trackWithReason->getMorphClass(),
            'votes' => -1,
            'downvote_reason' => TrackDownvoteReason::SoundQuality->value,
        ]);

        Vote::query()->create([
            'user_id' => User::factory()->create()->id,
            'votable_id' => $otherTrack->id,
            'votable_type' => $otherTrack->getMorphClass(),
            'votes' => -1,
            'downvote_reason' => TrackDownvoteReason::Difficulty->value,
        ]);

        $this->actingAs($owner)
            ->get(route('playlists.edit', $playlist))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('tracks.data', 2)
                ->where('tracks.data', fn ($tracks) => collect($tracks)->contains(
                    fn ($track) => $track['id'] === $trackWithReason->id
                        && ($track['downvote_breakdown']['sound_quality'] ?? 0) === 2
                ))
            );

        $this->actingAs($owner)
            ->get(route('playlists.edit', [
                'playlist' => $playlist,
                'downvoteReason' => TrackDownvoteReason::Difficulty->value,
            ]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('tracks.data', 1)
                ->where('tracks.data.0.id', $otherTrack->id)
            );
    }

    /**
     * @return array{0: User, 1: Room, 2: Track}
     */
    private function createRoomWithTrack(): array
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $playlist = Playlist::query()->create([
            'name' => 'Test playlist',
            'user_id' => $user->id,
        ]);

        $room = Room::factory()->create([
            'user_id' => $user->id,
            'category_id' => Category::factory()->create()->id,
            'slug' => 'downvote-room-'.$user->id,
            'is_public' => true,
            'is_active' => true,
            'is_featured' => false,
            'deleted_at' => null,
        ]);

        $room->playlists()->attach($playlist->id);

        $track = Track::query()->create([
            'playlist_id' => $playlist->id,
            'provider' => 'youtube',
            'provider_id' => 'track-'.$user->id,
            'provider_url' => 'https://youtube.com/watch?v=track',
            'preview_url' => 'track',
            'artwork_url' => 'https://example.com/art.jpg',
            'user_id' => $user->id,
        ]);

        return [$user, $room, $track];
    }

    private function downvoteUrl(Room $room, Track $track): string
    {
        return "/rooms/{$room->id}/tracks/{$track->id}/downvote";
    }
}
