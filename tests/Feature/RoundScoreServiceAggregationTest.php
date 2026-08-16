<?php

namespace Tests\Feature;

use App\Services\RoundScoreService;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class RoundScoreServiceAggregationTest extends TestCase
{
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
            //
        }

        parent::tearDown();
    }

    public function test_get_all_scores_and_track_history_use_player_set_without_keys_command(): void
    {
        $service = app(RoundScoreService::class);
        $roundId = 4242;

        $service->addScore($roundId, 10, 12.5);
        $service->recordTrackDetails($roundId, 10, 101, 4.5, null, 12.5, 501);
        $service->recordTrackDetails($roundId, 10, 102, 6.0, null, 0.0, null);

        $service->addScore($roundId, 20, 8.0);
        $service->recordTrackDetails($roundId, 20, 101, 8.0, null, 8.0, 502);

        $this->assertSame([
            10 => 12.5,
            20 => 8.0,
        ], $service->getAllScores($roundId));

        $histories = $service->getAllTracksHistory($roundId);
        $this->assertCount(2, $histories);
        $this->assertCount(2, $histories[10]);
        $this->assertCount(1, $histories[20]);

        $service->cleanup($roundId);

        $this->assertSame([], $service->getAllScores($roundId));
        $this->assertSame([], $service->getAllTracksHistory($roundId));
    }
}
