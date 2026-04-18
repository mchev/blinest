<?php

namespace Tests\Feature;

use App\Models\Playlist;
use App\Models\Track;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PlaylistClearTracksTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_clear_all_tracks_from_playlist(): void
    {
        Notification::fake();

        $owner = User::factory()->create();
        $playlist = Playlist::create([
            'name' => 'Test Playlist',
            'user_id' => $owner->id,
        ]);
        $playlist->moderators()->attach($owner->id);

        $track1 = Track::create([
            'playlist_id' => $playlist->id,
            'user_id' => $owner->id,
            'provider' => 'youtube',
            'provider_id' => 'id-one',
            'preview_url' => 'https://example.com/preview1',
            'artwork_url' => 'https://example.com/art1',
        ]);
        $track2 = Track::create([
            'playlist_id' => $playlist->id,
            'user_id' => $owner->id,
            'provider' => 'youtube',
            'provider_id' => 'id-two',
            'preview_url' => 'https://example.com/preview2',
            'artwork_url' => 'https://example.com/art2',
        ]);

        $this->actingAs($owner)
            ->from(route('playlists.edit', $playlist))
            ->delete(route('playlists.tracks.clear', $playlist))
            ->assertRedirect();

        $this->assertDatabaseMissing('tracks', ['id' => $track1->id]);
        $this->assertDatabaseMissing('tracks', ['id' => $track2->id]);
    }

    public function test_moderator_can_clear_all_tracks_from_playlist(): void
    {
        Notification::fake();

        $owner = User::factory()->create();
        $moderator = User::factory()->create();
        $playlist = Playlist::create([
            'name' => 'Test Playlist',
            'user_id' => $owner->id,
        ]);
        $playlist->moderators()->attach([$owner->id, $moderator->id]);

        $track = Track::create([
            'playlist_id' => $playlist->id,
            'user_id' => $owner->id,
            'provider' => 'youtube',
            'provider_id' => 'id-one',
            'preview_url' => 'https://example.com/preview1',
            'artwork_url' => 'https://example.com/art1',
        ]);

        $this->actingAs($moderator)
            ->from(route('playlists.edit', $playlist))
            ->delete(route('playlists.tracks.clear', $playlist))
            ->assertRedirect();

        $this->assertDatabaseMissing('tracks', ['id' => $track->id]);
    }

    public function test_non_moderator_cannot_clear_tracks(): void
    {
        $owner = User::factory()->create();
        $stranger = User::factory()->create();
        $playlist = Playlist::create([
            'name' => 'Test Playlist',
            'user_id' => $owner->id,
        ]);
        $playlist->moderators()->attach($owner->id);

        $track = Track::create([
            'playlist_id' => $playlist->id,
            'user_id' => $owner->id,
            'provider' => 'youtube',
            'provider_id' => 'id-one',
            'preview_url' => 'https://example.com/preview1',
            'artwork_url' => 'https://example.com/art1',
        ]);

        $this->actingAs($stranger)
            ->delete(route('playlists.tracks.clear', $playlist))
            ->assertForbidden();

        $this->assertDatabaseHas('tracks', ['id' => $track->id]);
    }
}
