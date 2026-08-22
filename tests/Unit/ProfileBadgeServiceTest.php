<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\UserLevel;
use App\Services\Profiles\ProfileBadgeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileBadgeServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_supporter_badge_is_earned_when_flagged(): void
    {
        $user = User::factory()->create(['is_guest' => false]);

        UserLevel::create([
            'user_id' => $user->id,
            'level' => 1,
            'total_xp' => 0,
            'current_xp' => 0,
            'xp_for_next_level' => 100,
        ]);

        $badges = app(ProfileBadgeService::class)->forUser($user, null, true);

        $supporter = collect($badges)->firstWhere('id', 'supporter');

        $this->assertNotNull($supporter);
        $this->assertTrue($supporter['earned']);
        $this->assertSame('Profile badge supporter desc', $supporter['description_key']);
    }

    public function test_creator_badge_requires_at_least_one_room_created(): void
    {
        $user = User::factory()->create(['is_guest' => false]);

        UserLevel::create([
            'user_id' => $user->id,
            'level' => 1,
            'total_xp' => 0,
            'current_xp' => 0,
            'xp_for_next_level' => 100,
            'rooms_created_count' => 2,
        ]);

        $badges = app(ProfileBadgeService::class)->forUser($user);

        $creator = collect($badges)->firstWhere('id', 'creator');

        $this->assertNotNull($creator);
        $this->assertTrue($creator['earned']);
    }

    public function test_top_elo_badge_requires_rank_within_top_100(): void
    {
        $user = User::factory()->create(['is_guest' => false]);

        UserLevel::create([
            'user_id' => $user->id,
            'level' => 1,
            'total_xp' => 0,
            'current_xp' => 0,
            'xp_for_next_level' => 100,
        ]);

        $badges = app(ProfileBadgeService::class)->forUser($user, 42);

        $topElo = collect($badges)->firstWhere('id', 'top_elo');

        $this->assertNotNull($topElo);
        $this->assertTrue($topElo['earned']);
    }
}
