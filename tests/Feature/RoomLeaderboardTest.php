<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Room;
use App\Models\Round;
use App\Models\RoundStanding;
use App\Models\TotalScore;
use App\Models\User;
use App\Models\UserLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomLeaderboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_room_scores_returns_enriched_leaderboard(): void
    {
        $category = Category::create(['name' => 'Test Category']);
        $owner = User::factory()->create(['elo' => 1620]);
        UserLevel::create([
            'user_id' => $owner->id,
            'level' => 5,
            'total_xp' => 500,
            'current_xp' => 50,
            'xp_for_next_level' => 100,
            'score_public_rooms' => 0,
        ]);

        $challenger = User::factory()->create(['elo' => 1480]);

        $room = Room::create([
            'name' => 'Leaderboard Room',
            'slug' => 'leaderboard-room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        TotalScore::create([
            'totalscorable_type' => User::class,
            'totalscorable_id' => $owner->id,
            'room_id' => $room->id,
            'score' => 120,
        ]);

        TotalScore::create([
            'totalscorable_type' => User::class,
            'totalscorable_id' => $challenger->id,
            'room_id' => $room->id,
            'score' => 80,
        ]);

        $round = Round::create([
            'room_id' => $room->id,
            'finished_at' => now(),
            'is_playing' => false,
            'current' => 0,
            'tracks' => [1],
        ]);

        RoundStanding::create([
            'round_id' => $round->id,
            'room_id' => $room->id,
            'user_id' => $owner->id,
            'position' => 1,
            'total_score' => 60,
            'elo_before' => 1600,
            'elo_after' => 1620,
            'elo_change' => 20,
            'is_elo_counted' => true,
            'average_response_time' => 2.5,
            'win_streak' => 3,
        ]);

        RoundStanding::create([
            'round_id' => $round->id,
            'room_id' => $room->id,
            'user_id' => $challenger->id,
            'position' => 2,
            'total_score' => 40,
            'elo_before' => 1500,
            'elo_after' => 1480,
            'elo_change' => -20,
            'is_elo_counted' => true,
            'average_response_time' => 4.0,
            'win_streak' => 1,
        ]);

        $response = $this->actingAs($owner)->getJson(route('rooms.scores.index', ['room' => $room->id]));

        $response->assertOk();
        $response->assertJsonPath('lifetime.0.user_id', $owner->id);
        $response->assertJsonPath('lifetime.0.rank', 1);
        $response->assertJsonPath('lifetime.0.total', 120);
        $response->assertJsonPath('lifetime.0.user.elo', 1620);
        $response->assertJsonPath('lifetime.0.user.user_level.level', 5);
        $response->assertJsonPath('lifetime.0.stats.level', 5);
        $response->assertJsonPath('lifetime.0.stats.elo', 1620);
        $response->assertJsonPath('lifetime.0.stats.score', 120);
        $response->assertJsonPath('lifetime.0.stats.avg_score_per_round', 60);
        $response->assertJsonPath('lifetime.0.stats.avg_response_time', 2.5);
        $response->assertJsonPath('lifetime.0.stats.best_round_score', 60);
        $response->assertJsonPath('lifetime.0.stats.best_win_streak', 3);
        $response->assertJsonPath('lifetime.0.stats.rounds_played', 1);
        $response->assertJsonPath('lifetime.1.user_id', $challenger->id);
        $response->assertJsonPath('user.lifetime.rank', 1);
        $response->assertJsonPath('user.lifetime.total', 120);
    }

    public function test_room_scores_week_tab_uses_recent_round_standings(): void
    {
        $category = Category::create(['name' => 'Test Category']);
        $owner = User::factory()->create();
        $room = Room::create([
            'name' => 'Weekly Room',
            'slug' => 'weekly-room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $round = Round::create([
            'room_id' => $room->id,
            'finished_at' => now(),
            'is_playing' => false,
            'current' => 0,
            'tracks' => [1],
        ]);

        RoundStanding::create([
            'round_id' => $round->id,
            'room_id' => $room->id,
            'user_id' => $owner->id,
            'position' => 1,
            'total_score' => 42,
            'elo_before' => 1500,
            'elo_after' => 1510,
            'elo_change' => 10,
            'is_elo_counted' => true,
            'average_response_time' => 1.8,
        ]);

        $response = $this->actingAs($owner)->getJson(route('rooms.scores.index', ['room' => $room->id]));

        $response->assertOk();
        $response->assertJsonPath('week.0.user_id', $owner->id);
        $response->assertJsonPath('week.0.total', 42);
        $response->assertJsonPath('week.0.stats.avg_response_time', 1.8);
        $response->assertJsonPath('user.week.rank', 1);
    }
}
