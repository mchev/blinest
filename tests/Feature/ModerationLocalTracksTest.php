<?php

namespace Tests\Feature;

use App\Jobs\ProcessDeletedTrack;
use App\Models\Category;
use App\Models\LocalTrack;
use App\Models\Playlist;
use App\Models\Room;
use App\Models\Track;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ModerationLocalTracksTest extends TestCase
{
    use RefreshDatabase;

    public function test_moderator_can_list_local_tracks(): void
    {
        [$moderator, $uploader, $localTrack] = $this->createModeratorAndLocalTrack();

        $response = $this->actingAs($moderator)->get(route('moderation.tracks.index', [
            'search' => 'Moderation Track',
        ]));

        $response->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Moderation/LocalTracks')
                ->has('tracks.data', 1)
                ->where('tracks.data.0.id', $localTrack->id)
                ->where('tracks.data.0.track_name', 'Moderation Track')
                ->where('tracks.data.0.artist_name', 'Moderation Artist')
                ->where('tracks.data.0.playlists_usage_count', 0)
                ->where('tracks.data.0.uploader.id', $uploader->id)
            );
    }

    public function test_moderator_can_update_local_track(): void
    {
        [$moderator, , $localTrack] = $this->createModeratorAndLocalTrack();

        $this->actingAs($moderator)
            ->put(route('moderation.tracks.update', $localTrack), [
                'track_name' => 'Updated Track',
                'artist_name' => 'Updated Artist',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('local_tracks', [
            'id' => $localTrack->id,
            'track_name' => 'Updated Track',
            'artist_name' => 'Updated Artist',
        ]);
    }

    public function test_moderator_cannot_create_duplicate_local_track(): void
    {
        [$moderator, , $localTrack] = $this->createModeratorAndLocalTrack();

        LocalTrack::query()->create([
            'track_name' => 'Duplicate Track',
            'artist_name' => 'Duplicate Artist',
            'audio_path' => 'local-tracks/other.mp3',
            'artwork_path' => 'local-tracks/other.jpg',
            'user_id' => $localTrack->user_id,
        ]);

        $this->actingAs($moderator)
            ->from(route('moderation.tracks.index'))
            ->put(route('moderation.tracks.update', $localTrack), [
                'track_name' => 'Duplicate Track',
                'artist_name' => 'Duplicate Artist',
            ])
            ->assertRedirect(route('moderation.tracks.index'))
            ->assertSessionHasErrors('track_name');
    }

    public function test_moderator_can_delete_local_track_and_dispatch_playlist_cleanup(): void
    {
        Queue::fake();
        Storage::fake();

        [$moderator, $uploader, $localTrack] = $this->createModeratorAndLocalTrack();

        Storage::put($localTrack->audio_path, 'audio');
        Storage::put($localTrack->artwork_path, 'artwork');

        $playlist = Playlist::query()->create([
            'name' => 'Moderation Playlist',
            'user_id' => $uploader->id,
        ]);

        $linkedTrack = Track::query()->create([
            'playlist_id' => $playlist->id,
            'user_id' => $uploader->id,
            'provider' => 'local',
            'provider_id' => (string) $localTrack->id,
            'preview_url' => 'https://example.com/preview',
            'artwork_url' => 'https://example.com/artwork',
        ]);

        $this->actingAs($moderator)
            ->delete(route('moderation.tracks.destroy', $localTrack))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('local_tracks', ['id' => $localTrack->id]);
        Storage::assertMissing($localTrack->audio_path);
        Storage::assertMissing($localTrack->artwork_path);

        Queue::assertPushed(ProcessDeletedTrack::class, fn (ProcessDeletedTrack $job) => $job->track->is($linkedTrack));
    }

    public function test_guest_cannot_access_local_tracks_moderation(): void
    {
        $this->get(route('moderation.tracks.index'))->assertRedirect(route('login'));
    }

    /**
     * @return array{0: User, 1: User, 2: LocalTrack}
     */
    private function createModeratorAndLocalTrack(): array
    {
        $moderator = User::factory()->create(['email_verified_at' => now()]);
        $room = Room::factory()->create([
            'user_id' => $moderator->id,
            'category_id' => Category::factory()->create()->id,
            'is_public' => true,
            'is_active' => true,
            'is_featured' => false,
            'deleted_at' => null,
        ]);
        $room->moderators()->attach($moderator->id);

        $uploader = User::factory()->create([
            'name' => 'LocalTrackUploader',
            'email_verified_at' => now(),
        ]);

        $localTrack = LocalTrack::query()->create([
            'track_name' => 'Moderation Track',
            'artist_name' => 'Moderation Artist',
            'audio_path' => 'local-tracks/moderation.mp3',
            'artwork_path' => 'local-tracks/moderation.jpg',
            'user_id' => $uploader->id,
        ]);

        return [$moderator, $uploader, $localTrack];
    }
}
