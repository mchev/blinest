<?php

namespace Tests\Feature;

use App\Events\TrackEnded;
use App\Models\AnswerType;
use App\Models\Category;
use App\Models\Playlist;
use App\Models\Room;
use App\Models\Track;
use App\Models\TrackAnswer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrackEndedPlaylistPayloadTest extends TestCase
{
    use RefreshDatabase;

    public function test_track_ended_broadcast_includes_playlist_track_payload(): void
    {
        $category = Category::create(['name' => 'Cat']);
        $owner = User::factory()->create();

        $room = Room::create([
            'name' => 'Track Ended Payload Room',
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

        $track = Track::create([
            'playlist_id' => $playlist->id,
            'user_id' => $owner->id,
            'provider' => 'youtube',
            'provider_id' => 'ended-track',
            'preview_url' => 'https://example.com/preview',
            'artwork_url' => 'https://example.com/art',
        ]);
        TrackAnswer::forceCreate([
            'track_id' => $track->id,
            'answer_type_id' => $answerType->id,
            'value' => 'Daft Punk',
            'score' => 5.0,
        ]);

        $round = $room->rounds()->create([
            'current' => 1,
            'is_playing' => true,
            'current_track_started_at' => now()->subSeconds(30),
        ]);
        $round->forceFill(['tracks' => [$track->id]])->save();

        $payload = (new TrackEnded($round->fresh()))->broadcastWith();

        $this->assertSame($track->id, $payload['track']['id']);
        $this->assertSame('https://example.com/art', $payload['track']['artwork_url']);
        $this->assertArrayNotHasKey('audio', $payload['track']);
        $this->assertSame('Daft Punk', $payload['track']['answers'][0]['value']);
        $this->assertSame('Artist', $payload['track']['answers'][0]['type']['name']);
    }
}
