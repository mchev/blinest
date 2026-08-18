<?php

namespace Tests\Feature;

use App\Models\AnswerType;
use App\Models\Category;
use App\Models\Playlist;
use App\Models\Room;
use App\Models\Round;
use App\Models\RoundStanding;
use App\Models\Track;
use App\Models\TrackAnswer;
use App\Models\User;
use App\Services\RoundPerformanceMetricsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BackfillRoundStandingPerformanceMetricsTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_uses_excerpt_completion_time_not_per_answer_average(): void
    {
        $metrics = app(RoundPerformanceMetricsService::class)->fromTracksHistory(
            [
                ['track_id' => 42, 'answer_id' => 10, 'response_time' => 5.0],
                ['track_id' => 42, 'answer_id' => 11, 'response_time' => 12.0],
            ],
            [42 => 2],
            5.4,
        );

        $this->assertEquals(12.0, $metrics['average_response_time']);
        $this->assertEquals(2, $metrics['total_answers_count']);
    }

    public function test_backfill_command_updates_missing_performance_metrics(): void
    {
        $fixture = $this->createStandingFixture(requiredAnswers: 2);
        $standing = $this->createStandingWithHistory($fixture, [
            ['response_time' => 5.0, 'answer_index' => 0],
            ['response_time' => 12.0, 'answer_index' => 1],
        ]);

        $this->assertNull($standing->average_response_time);

        $this->artisan('standings:backfill-performance-metrics')
            ->assertSuccessful();

        $standing->refresh();

        $this->assertEquals(12.0, (float) $standing->average_response_time);
        $this->assertEquals(2, $standing->total_answers_count);
    }

    public function test_backfill_command_skips_standings_that_already_have_metrics(): void
    {
        $fixture = $this->createStandingFixture(requiredAnswers: 2);
        $standing = $this->createStandingWithHistory($fixture, [
            ['response_time' => 5.0, 'answer_index' => 0],
            ['response_time' => 12.0, 'answer_index' => 1],
        ]);

        $standing->update([
            'average_response_time' => 99.0,
            'fast_answers_count' => 0,
            'total_answers_count' => 2,
        ]);

        $this->artisan('standings:backfill-performance-metrics')
            ->assertSuccessful();

        $standing->refresh();

        $this->assertEquals(99.0, (float) $standing->average_response_time);
    }

    public function test_backfill_command_force_option_recalculates_existing_metrics(): void
    {
        $fixture = $this->createStandingFixture(requiredAnswers: 2);
        $standing = $this->createStandingWithHistory($fixture, [
            ['response_time' => 5.0, 'answer_index' => 0],
            ['response_time' => 12.0, 'answer_index' => 1],
        ]);

        $standing->update([
            'average_response_time' => 99.0,
            'fast_answers_count' => 0,
            'total_answers_count' => 2,
        ]);

        $this->artisan('standings:backfill-performance-metrics --force')
            ->assertSuccessful();

        $standing->refresh();

        $this->assertEquals(12.0, (float) $standing->average_response_time);
    }

    public function test_backfill_command_dry_run_does_not_persist_changes(): void
    {
        $fixture = $this->createStandingFixture(requiredAnswers: 2);
        $standing = $this->createStandingWithHistory($fixture, [
            ['response_time' => 5.0, 'answer_index' => 0],
            ['response_time' => 12.0, 'answer_index' => 1],
        ]);

        $this->artisan('standings:backfill-performance-metrics --dry-run')
            ->assertSuccessful();

        $standing->refresh();

        $this->assertNull($standing->average_response_time);
    }

    /**
     * @return array{
     *     owner: User,
     *     track: Track,
     *     answers: list<TrackAnswer>
     * }
     */
    private function createStandingFixture(int $requiredAnswers): array
    {
        $category = Category::create(['name' => 'Backfill Category']);
        $owner = User::factory()->create();
        Room::create([
            'name' => 'Backfill Room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $playlist = Playlist::create([
            'name' => 'Backfill Playlist',
            'user_id' => $owner->id,
        ]);

        $track = Track::create([
            'playlist_id' => $playlist->id,
            'user_id' => $owner->id,
            'provider' => 'youtube',
            'provider_id' => 'backfill-track',
            'preview_url' => 'https://example.com/preview',
            'artwork_url' => 'https://example.com/artwork',
        ]);

        $answerType = AnswerType::create(['name' => 'Artist']);
        $answers = [];

        for ($index = 0; $index < $requiredAnswers; $index++) {
            $answers[] = TrackAnswer::create([
                'track_id' => $track->id,
                'answer_type_id' => $answerType->id,
                'value' => "Answer {$index}",
                'score' => 1.0,
            ]);
        }

        return [
            'owner' => $owner,
            'track' => $track,
            'answers' => $answers,
        ];
    }

    /**
     * @param  array{
     *     owner: User,
     *     track: Track,
     *     answers: list<TrackAnswer>
     * }  $fixture
     * @param  list<array{response_time: float, answer_index: int}>  $entries
     */
    private function createStandingWithHistory(array $fixture, array $entries): RoundStanding
    {
        $owner = $fixture['owner'];
        $track = $fixture['track'];

        $history = collect($entries)->map(function (array $entry) use ($fixture, $track) {
            return [
                'track_id' => $track->id,
                'answer_id' => $fixture['answers'][$entry['answer_index']]->id,
                'response_time' => $entry['response_time'],
            ];
        })->all();

        $room = Room::query()->where('user_id', $owner->id)->firstOrFail();

        $round = Round::create([
            'room_id' => $room->id,
            'finished_at' => now(),
            'is_playing' => false,
            'current' => 0,
            'tracks' => [$track->id],
        ]);

        return RoundStanding::create([
            'round_id' => $round->id,
            'room_id' => $room->id,
            'user_id' => $owner->id,
            'position' => 1,
            'total_score' => 10,
            'elo_before' => 1500,
            'elo_after' => 1500,
            'elo_change' => 0,
            'is_elo_counted' => false,
            'tracks_history' => $history,
        ]);
    }
}
