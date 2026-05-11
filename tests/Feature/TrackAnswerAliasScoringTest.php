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
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class TrackAnswerAliasScoringTest extends TestCase
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

    public function test_player_gets_credit_when_submitting_an_alias(): void
    {
        $category = Category::create(['name' => 'Test Category']);
        $owner = User::create([
            'name' => 'Owner',
            'email' => 'owner@test.com',
            'password' => bcrypt('password'),
            'elo' => 1500,
        ]);
        $room = Room::create([
            'name' => 'Test Room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $player = User::factory()->create(['elo' => 1500]);

        $playlist = Playlist::create([
            'name' => 'Test Playlist',
            'user_id' => $owner->id,
        ]);
        $answerType = AnswerType::create(['name' => 'Artist']);

        $track = Track::create([
            'playlist_id' => $playlist->id,
            'user_id' => $owner->id,
            'provider' => 'youtube',
            'provider_id' => 'test123',
            'preview_url' => 'https://example.com/preview',
            'artwork_url' => 'https://example.com/artwork',
        ]);

        $trackAnswer = TrackAnswer::create([
            'track_id' => $track->id,
            'answer_type_id' => $answerType->id,
            'value' => 'Canonical Artist Name',
            'score' => 5.0,
            'aliases' => ['Alias Artist'],
        ]);

        TrackAnswer::create([
            'track_id' => $track->id,
            'answer_type_id' => $answerType->id,
            'value' => 'Second Answer Only',
            'score' => 3.0,
        ]);

        $round = Round::create([
            'room_id' => $room->id,
            'finished_at' => null,
            'is_playing' => true,
            'current' => 1,
            'tracks' => [$track->id],
        ]);

        Cache::forget('track-'.$track->id.'-answers');

        $response = $this->actingAs($player)->postJson(route('rounds.track.check', [$round, $track]), [
            'text' => 'Alias Artist',
            'words' => [],
            'currentTime' => 5.0,
        ]);

        $response->assertOk();
        $this->assertNotEmpty($response->json('good_answers'));
        $this->assertEquals($trackAnswer->id, $response->json('good_answers.0.id'));
        $this->assertSame('good', $response->json('message.type'));

        $response2 = $this->actingAs($player)->postJson(route('rounds.track.check', [$round, $track]), [
            'text' => 'Alias Artist',
            'words' => [],
            'currentTime' => 8.0,
        ]);

        $response2->assertOk();
        $this->assertEmpty($response2->json('good_answers'));
        $this->assertSame('bad', $response2->json('message.type'));
    }

    public function test_single_letter_validates_answer_whose_value_is_entirely_parentheses(): void
    {
        $category = Category::create(['name' => 'Test Category']);
        $owner = User::create([
            'name' => 'Owner',
            'email' => 'owner2@test.com',
            'password' => bcrypt('password'),
            'elo' => 1500,
        ]);
        $room = Room::create([
            'name' => 'Test Room 2',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $player = User::factory()->create(['elo' => 1500]);

        $playlist = Playlist::create([
            'name' => 'Test Playlist 2',
            'user_id' => $owner->id,
        ]);
        $typeFeaturing = AnswerType::create(['name' => 'Featuring']);
        $typeTitle = AnswerType::create(['name' => 'Title']);

        $track = Track::create([
            'playlist_id' => $playlist->id,
            'user_id' => $owner->id,
            'provider' => 'youtube',
            'provider_id' => 'test456',
            'preview_url' => 'https://example.com/preview',
            'artwork_url' => 'https://example.com/artwork',
        ]);

        $hintAnswer = TrackAnswer::create([
            'track_id' => $track->id,
            'answer_type_id' => $typeFeaturing->id,
            'value' => '(How i wish, how i wish you were here)',
            'score' => 0.0,
        ]);

        TrackAnswer::create([
            'track_id' => $track->id,
            'answer_type_id' => $typeTitle->id,
            'value' => 'Real Song Title',
            'score' => 1.0,
        ]);

        $round = Round::create([
            'room_id' => $room->id,
            'finished_at' => null,
            'is_playing' => true,
            'current' => 1,
            'tracks' => [$track->id],
        ]);

        Cache::forget('track-'.$track->id.'-answers');

        $response = $this->actingAs($player)->postJson(route('rounds.track.check', [$round, $track]), [
            'text' => 'a',
            'words' => [],
            'currentTime' => 2.0,
        ]);

        $response->assertOk();
        $this->assertNotEmpty($response->json('good_answers'));
        $ids = collect($response->json('good_answers'))->pluck('id')->all();
        $this->assertContains($hintAnswer->id, $ids);
        $this->assertSame('good', $response->json('message.type'));
    }
}
