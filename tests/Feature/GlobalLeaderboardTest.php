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
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class GlobalLeaderboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_rankings_index_renders_unified_players_page(): void
    {
        $category = Category::create(['name' => 'Test']);
        $owner = User::factory()->create(['elo' => 1600]);
        UserLevel::create([
            'user_id' => $owner->id,
            'level' => 8,
            'total_xp' => 800,
            'current_xp' => 50,
            'xp_for_next_level' => 100,
            'score_public_rooms' => 120,
        ]);

        $room = Room::create([
            'name' => 'Public Room',
            'slug' => 'public-room',
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
            'elo_before' => 1580,
            'elo_after' => 1600,
            'elo_change' => 20,
            'is_elo_counted' => true,
            'average_response_time' => 2.1,
            'win_streak' => 2,
        ]);

        $response = $this->actingAs($owner)->get(route('rankings.index', ['sort' => 'score']));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Rankings/Players')
            ->where('sort', 'score')
            ->has('leaderboard.data', 1)
            ->where('leaderboard.data.0.stats.score', 120)
            ->where('leaderboard.data.0.stats.level', 8)
            ->where('leaderboard.data.0.stats.elo', 1600)
            ->where('leaderboard.data.0.stats.avg_response_time', 2.1)
            ->has('officialRooms', 1)
        );
    }

    public function test_room_filter_scopes_leaderboard_to_selected_official_room(): void
    {
        $category = Category::create(['name' => 'Test']);
        $owner = User::factory()->create();

        $roomA = Room::create([
            'name' => 'Room A',
            'slug' => 'room-a',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $roomB = Room::create([
            'name' => 'Room B',
            'slug' => 'room-b',
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
            'room_id' => $roomA->id,
            'score' => 200,
        ]);

        TotalScore::create([
            'totalscorable_type' => User::class,
            'totalscorable_id' => $owner->id,
            'room_id' => $roomB->id,
            'score' => 50,
        ]);

        $response = $this->actingAs($owner)->get(route('rankings.index', ['sort' => 'score', 'room' => $roomA->id]));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Rankings/Players')
            ->where('roomId', $roomA->id)
            ->where('sort', 'score')
            ->has('leaderboard.data', 1)
            ->where('leaderboard.data.0.stats.score', 200)
        );
    }

    public function test_legacy_ranking_routes_redirect_to_unified_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('rankings.level'))
            ->assertRedirect(route('rankings.index', ['sort' => 'level']));

        $this->actingAs($user)->get(route('rankings.score'))
            ->assertRedirect(route('rankings.index', ['sort' => 'score']));

        $this->actingAs($user)->get(route('rankings.elo'))
            ->assertRedirect(route('rankings.index', ['sort' => 'elo']));

        $this->actingAs($user)->get(route('rankings.week'))
            ->assertRedirect(route('rankings.index', ['sort' => 'week']));
    }

    public function test_rankings_page_is_accessible_to_guests(): void
    {
        $response = $this->get(route('rankings.index'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Rankings/Players')
            ->where('userContext', null)
        );
    }

    public function test_rankings_page_has_public_seo_meta_for_guests(): void
    {
        $response = $this->get(route('rankings.index'));

        $response->assertOk();
        $response->assertSee('name="description"', false);
        $response->assertSee('Classement public', false);
    }
}
