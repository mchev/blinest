<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Room;
use App\Models\TotalScore;
use App\Models\User;
use App\Models\UserLevel;
use App\Services\LevelCalculator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class RankingScoreQueryTest extends TestCase
{
    use RefreshDatabase;

    public function test_score_ranking_only_counts_public_rooms(): void
    {
        $category = Category::create(['name' => 'Test']);
        $owner = User::factory()->create();
        $player = User::factory()->create();

        $publicRoom = Room::create([
            'name' => 'Public Room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $privateRoom = Room::create([
            'name' => 'Private Room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => false,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        TotalScore::create([
            'totalscorable_type' => User::class,
            'totalscorable_id' => $player->id,
            'room_id' => $publicRoom->id,
            'score' => 50,
        ]);

        TotalScore::create([
            'totalscorable_type' => User::class,
            'totalscorable_id' => $player->id,
            'room_id' => $privateRoom->id,
            'score' => 500,
        ]);

        $this->actingAs($player)
            ->get(route('rankings.score'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Rankings/Score')
                ->where('userScore', 50)
                ->where('userPosition', 1)
                ->has('topByScore.data', 1)
                ->where('topByScore.data.0.total_score', 50));
    }

    public function test_level_calculator_persists_minigame_scores_total(): void
    {
        $user = User::factory()->create();

        UserLevel::create([
            'user_id' => $user->id,
            'level' => 1,
            'total_xp' => 0,
            'current_xp' => 0,
            'xp_for_next_level' => 100,
            'score_public_rooms' => 0,
        ]);

        $user->minigameScores()->create([
            'game_type' => 'quiz',
            'score' => 42,
        ]);

        $calculator = new LevelCalculator($user, 'score');
        $calculator->update();

        $this->assertDatabaseHas('user_levels', [
            'user_id' => $user->id,
            'minigame_scores_total' => 42,
        ]);
    }
}
