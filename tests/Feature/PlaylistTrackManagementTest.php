<?php

namespace Tests\Feature;

use App\Models\Playlist;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlaylistTrackManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthorized_user_cannot_add_track_to_playlist(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();

        $playlist = Playlist::create([
            'name' => 'Private playlist',
            'user_id' => $owner->id,
        ]);

        $response = $this->actingAs($intruder)->post(route('playlists.tracks.store', $playlist), [
            'provider' => 'youtube',
            'provider_id' => 'abc123',
            'provider_url' => 'https://youtube.com/watch?v=abc123',
            'artist_name' => 'Artist',
            'track_name' => 'Track',
            'preview_url' => 'abc123',
            'artwork_url' => 'https://example.com/art.jpg',
        ]);

        $response->assertForbidden();
    }

    public function test_owner_add_track_redirects_back_to_playlist_edit(): void
    {
        $owner = User::factory()->create();

        $playlist = Playlist::create([
            'name' => 'Test playlist',
            'user_id' => $owner->id,
        ]);

        $response = $this->actingAs($owner)
            ->from(route('playlists.edit', $playlist))
            ->post(route('playlists.tracks.store', $playlist), [
                'provider' => 'youtube',
                'provider_id' => 'abc123',
                'provider_url' => 'https://youtube.com/watch?v=abc123',
                'artist_name' => 'Artist',
                'track_name' => 'Track',
                'preview_url' => 'abc123',
                'artwork_url' => 'https://example.com/art.jpg',
            ]);

        $response->assertRedirect(route('playlists.edit', $playlist));
        $this->assertDatabaseHas('tracks', [
            'playlist_id' => $playlist->id,
            'provider_id' => 'abc123',
        ]);
    }
}
