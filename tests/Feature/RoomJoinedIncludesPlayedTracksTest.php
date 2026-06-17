<?php

namespace Tests\Feature;

use App\Models\AnswerType;
use App\Models\Category;
use App\Models\Playlist;
use App\Models\Room;
use App\Models\Track;
use App\Models\TrackAnswer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class RoomJoinedIncludesPlayedTracksTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        try {
            Redis::ping();
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

    public function test_joined_includes_finished_tracks_in_playlist_payload_in_order(): void
    {
        $category = Category::create(['name' => 'Cat']);
        $owner = User::factory()->create();
        $player = User::factory()->create();

        $room = Room::create([
            'name' => 'Test Room Joined Playlist',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'is_playing' => true,
            'is_autostart' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $playlist = Playlist::create([
            'name' => 'P',
            'user_id' => $owner->id,
        ]);
        $answerType = AnswerType::create(['name' => 'Artist']);

        $makeTrack = function (string $suffix) use ($playlist, $owner, $answerType): Track {
            $track = Track::create([
                'playlist_id' => $playlist->id,
                'user_id' => $owner->id,
                'provider' => 'youtube',
                'provider_id' => 'id-'.$suffix,
                'preview_url' => 'https://example.com/preview-'.$suffix,
                'artwork_url' => 'https://example.com/art-'.$suffix,
            ]);
            TrackAnswer::forceCreate([
                'track_id' => $track->id,
                'answer_type_id' => $answerType->id,
                'value' => 'Answer '.$track->id,
                'score' => 5.0,
            ]);

            return $track;
        };

        $t1 = $makeTrack('one');
        $t2 = $makeTrack('two');
        $t3 = $makeTrack('three');

        $round = $room->rounds()->create([
            'current' => 3,
            'is_playing' => true,
            'current_track_started_at' => now(),
        ]);
        $round->forceFill(['tracks' => [$t1->id, $t2->id, $t3->id]])->save();

        // Show.vue calls `/rooms/{id}/joined`; resolveRouteBinding defaults to id when no `{room:slug}` hint.
        $response = $this->actingAs($player)->getJson('/rooms/'.$room->id.'/joined');

        $response->assertOk();
        $response->assertJsonPath('round.id', $round->id);
        $response->assertJsonPath('round.current', 3);
        $response->assertJsonPath('track.id', $t3->id);

        $played = $response->json('playedTracks');
        $this->assertCount(2, $played);
        $this->assertSame($t1->id, $played[0]['id']);
        $this->assertSame($t2->id, $played[1]['id']);
        $this->assertArrayNotHasKey('audio', $played[0]);
        $this->assertSame('Answer '.$t1->id, $played[0]['answers'][0]['value']);
        $this->assertSame('Artist', $played[0]['answers'][0]['type']['name']);

        $tracksOrder = $response->json('round.tracks');
        $this->assertSame([$t1->id, $t2->id, $t3->id], array_map('intval', $tracksOrder));
    }

    public function test_joined_returns_empty_played_tracks_when_only_first_extract_has_started(): void
    {
        $category = Category::create(['name' => 'Cat']);
        $owner = User::factory()->create();
        $player = User::factory()->create();

        $room = Room::create([
            'name' => 'Test Room Joined Playlist First',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'is_playing' => true,
            'is_autostart' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $playlist = Playlist::create([
            'name' => 'P',
            'user_id' => $owner->id,
        ]);
        $answerType = AnswerType::create(['name' => 'Artist']);

        $t1 = Track::create([
            'playlist_id' => $playlist->id,
            'user_id' => $owner->id,
            'provider' => 'youtube',
            'provider_id' => 'id-one',
            'preview_url' => 'https://example.com/preview',
            'artwork_url' => 'https://example.com/art',
        ]);
        TrackAnswer::forceCreate([
            'track_id' => $t1->id,
            'answer_type_id' => $answerType->id,
            'value' => 'Only',
            'score' => 5.0,
        ]);

        $round = $room->rounds()->create([
            'current' => 1,
            'is_playing' => true,
            'current_track_started_at' => now(),
        ]);
        $round->forceFill(['tracks' => [$t1->id]])->save();

        $response = $this->actingAs($player)->getJson('/rooms/'.$room->id.'/joined');

        $response->assertOk();
        $response->assertJsonPath('track.id', $t1->id);
        $this->assertSame([], $response->json('playedTracks'));
    }
}
