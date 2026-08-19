<?php

namespace Tests\Feature;

use App\Events\NewScore;
use App\Events\UserHasFoundAllTheAnswers;
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
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class RoundAnswerSubmissionTest extends TestCase
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

    public function test_sequential_submissions_accumulate_words_for_multi_part_answers(): void
    {
        Event::fake([NewScore::class, UserHasFoundAllTheAnswers::class]);

        [$round, $track, $player, $artistAnswer, $titleAnswer] = $this->createRoundWithArtistAndTitleAnswers();

        $artistResponse = $this->actingAs($player)->postJson(route('rounds.track.check', [$round, $track]), [
            'text' => 'Daft Punk',
            'words' => [],
            'currentTime' => 3.0,
        ]);

        $artistResponse->assertOk();
        $this->assertCount(1, $artistResponse->json('good_answers'));
        $this->assertSame($artistAnswer->id, $artistResponse->json('good_answers.0.id'));

        $titleResponse = $this->actingAs($player)->postJson(route('rounds.track.check', [$round, $track]), [
            'text' => 'One More Time',
            'words' => $artistResponse->json('words'),
            'currentTime' => 4.5,
        ]);

        $titleResponse->assertOk();
        $this->assertCount(1, $titleResponse->json('good_answers'));
        $this->assertSame($titleAnswer->id, $titleResponse->json('good_answers.0.id'));
    }

    public function test_answer_is_rejected_when_current_time_exceeds_room_track_duration(): void
    {
        [$round, $track, $player] = $this->createRoundWithArtistAndTitleAnswers();

        $response = $this->actingAs($player)->postJson(route('rounds.track.check', [$round, $track]), [
            'text' => 'Daft Punk',
            'words' => [],
            'currentTime' => 45.0,
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'error' => 'invalid_time',
        ]);
    }

    public function test_answer_uses_current_room_track_duration_even_when_stale_track_cache_exists(): void
    {
        [$round, $track, $player] = $this->createRoundWithArtistAndTitleAnswers(trackDuration: 20);

        Cache::forever('track_'.$track->id.'_duration', 10);

        $response = $this->actingAs($player)->postJson(route('rounds.track.check', [$round, $track]), [
            'text' => 'Daft Punk',
            'words' => [],
            'currentTime' => 15.0,
        ]);

        $response->assertOk();
        $this->assertCount(1, $response->json('good_answers'));
    }

    public function test_check_accepts_track_id_when_round_tracks_store_string_ids(): void
    {
        Event::fake([NewScore::class, UserHasFoundAllTheAnswers::class]);

        [$round, $track, $player] = $this->createRoundWithArtistAndTitleAnswers();
        $round->update(['tracks' => [(string) $track->id]]);

        $response = $this->actingAs($player)->postJson(route('rounds.track.check', [$round, $track]), [
            'text' => 'Daft Punk',
            'words' => [],
            'currentTime' => 3.0,
        ]);

        $response->assertOk();
        $this->assertCount(1, $response->json('good_answers'));
    }

    public function test_check_returns_conflict_when_track_is_not_current(): void
    {
        [$round, $track, $player] = $this->createRoundWithArtistAndTitleAnswers();
        $otherTrack = Track::create([
            'playlist_id' => $track->playlist_id,
            'user_id' => $track->user_id,
            'provider' => 'youtube',
            'provider_id' => 'other-track',
            'preview_url' => 'https://example.com/preview-other',
            'artwork_url' => 'https://example.com/artwork-other',
        ]);

        $response = $this->actingAs($player)->postJson(route('rounds.track.check', [$round, $otherTrack]), [
            'text' => 'Daft Punk',
            'words' => [],
            'currentTime' => 3.0,
        ]);

        $response->assertStatus(409);
        $response->assertJson(['error' => 'track_mismatch']);
    }

    /**
     * @return array{0: Round, 1: Track, 2: User, 3: TrackAnswer, 4: TrackAnswer}
     */
    private function createRoundWithArtistAndTitleAnswers(int $trackDuration = 30): array
    {
        $category = Category::create(['name' => 'Test Category']);
        $owner = User::factory()->create();
        $player = User::factory()->create();

        $room = Room::create([
            'name' => 'Test Room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'track_duration' => $trackDuration,
            'tracks_by_round' => 10,
        ]);

        $playlist = Playlist::create([
            'name' => 'Test Playlist',
            'user_id' => $owner->id,
        ]);

        $artistType = AnswerType::create(['name' => 'Artist']);
        $titleType = AnswerType::create(['name' => 'Title']);

        $track = Track::create([
            'playlist_id' => $playlist->id,
            'user_id' => $owner->id,
            'provider' => 'youtube',
            'provider_id' => 'test-track',
            'preview_url' => 'https://example.com/preview',
            'artwork_url' => 'https://example.com/artwork',
        ]);

        $artistAnswer = TrackAnswer::create([
            'track_id' => $track->id,
            'answer_type_id' => $artistType->id,
            'value' => 'Daft Punk',
            'score' => 5.0,
        ]);

        $titleAnswer = TrackAnswer::create([
            'track_id' => $track->id,
            'answer_type_id' => $titleType->id,
            'value' => 'One More Time',
            'score' => 5.0,
        ]);

        $round = Round::create([
            'room_id' => $room->id,
            'finished_at' => null,
            'is_playing' => true,
            'current' => 1,
            'tracks' => [$track->id],
        ]);

        Cache::forget(Track::answersCacheKey($track->id));

        return [$round, $track, $player, $artistAnswer, $titleAnswer];
    }
}
