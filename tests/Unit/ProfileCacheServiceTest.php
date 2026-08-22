<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\Profiles\ProfileCacheService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ProfileCacheServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_forget_clears_profile_cache_keys(): void
    {
        $user = User::factory()->create(['is_guest' => false]);

        foreach ([
            "user_profile_totals_{$user->id}",
            "user_profile_stats_{$user->id}",
            "user_profile_highlights_v2_{$user->id}",
            "user_score_evolution_{$user->id}",
        ] as $key) {
            Cache::put($key, ['cached' => true], 3600);
        }

        app(ProfileCacheService::class)->forget($user);

        foreach ([
            "user_profile_totals_{$user->id}",
            "user_profile_stats_{$user->id}",
            "user_profile_highlights_v2_{$user->id}",
            "user_score_evolution_{$user->id}",
        ] as $key) {
            $this->assertNull(Cache::get($key));
        }
    }
}
