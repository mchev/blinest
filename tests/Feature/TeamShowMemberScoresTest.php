<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Room;
use App\Models\Round;
use App\Models\RoundStanding;
use App\Models\Team;
use App\Models\TotalScore;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class TeamShowMemberScoresTest extends TestCase
{
    use RefreshDatabase;

    public function test_team_show_includes_member_points_from_round_standings(): void
    {
        $category = Category::create(['name' => 'Test Category']);
        $roomOwner = User::factory()->create();
        $room = Room::create([
            'name' => 'Test Room',
            'user_id' => $roomOwner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $owner = User::factory()->create();
        $member = User::factory()->create();
        $team = Team::create([
            'name' => 'jointeam',
            'user_id' => $owner->id,
        ]);
        $owner->update(['team_id' => $team->id]);
        $member->update(['team_id' => $team->id]);

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
            'team_id' => $team->id,
            'position' => 2,
            'total_score' => 10.5,
            'elo_before' => 1500,
            'elo_after' => 1500,
            'elo_change' => 0,
            'is_elo_counted' => false,
        ]);

        RoundStanding::create([
            'round_id' => $round->id,
            'room_id' => $room->id,
            'user_id' => $member->id,
            'team_id' => $team->id,
            'position' => 1,
            'total_score' => 21,
            'elo_before' => 1500,
            'elo_after' => 1500,
            'elo_change' => 0,
            'is_elo_counted' => false,
        ]);

        TotalScore::create([
            'totalscorable_type' => Team::class,
            'totalscorable_id' => $team->id,
            'room_id' => $room->id,
            'score' => 31.5,
        ]);

        $response = $this->actingAs($owner)->get(route('teams.show', $team));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Teams/Show')
            ->where('score', 31.5)
            ->where('stats.rounds_played', 1)
            ->where('stats.avg_points_per_round', 31.5)
            ->has('members', 2)
            ->where('members.0.score', 21)
            ->where('members.0.rounds_played', 1)
            ->where('members.0.contribution_percent', 66.7)
            ->where('members.1.score', 10.5)
            ->where('members.1.rounds_played', 1)
            ->where('members.1.contribution_percent', 33.3)
        );
    }
}
