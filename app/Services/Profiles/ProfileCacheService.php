<?php

namespace App\Services\Profiles;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class ProfileCacheService
{
    public function forget(User $user): void
    {
        if ($user->isGuest()) {
            return;
        }

        $userId = $user->id;

        Cache::forget("user_profile_totals_{$userId}");
        Cache::forget("user_profile_stats_{$userId}");
        Cache::forget("user_profile_highlights_v2_{$userId}");
        Cache::forget("user_score_evolution_{$userId}");
    }
}
