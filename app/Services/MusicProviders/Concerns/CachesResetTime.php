<?php

namespace App\Services\MusicProviders\Concerns;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

trait CachesResetTime
{
    protected function isBeforeCachedResetTime(string $flagKey, string $resetTimeKey): bool
    {
        if (! Cache::has($flagKey)) {
            return false;
        }

        $resetTime = $this->parseCachedResetTime($resetTimeKey);

        if ($resetTime && now()->lessThan($resetTime)) {
            return true;
        }

        Cache::forget($flagKey);
        Cache::forget($resetTimeKey);

        return false;
    }

    protected function cacheResetTime(string $flagKey, string $resetTimeKey, Carbon $resetTime): void
    {
        Cache::put($flagKey, true, $resetTime);
        Cache::put($resetTimeKey, $resetTime->toIso8601String(), $resetTime);
    }

    protected function parseCachedResetTime(string $cacheKey): ?Carbon
    {
        $resetTime = Cache::get($cacheKey);

        if ($resetTime === null) {
            return null;
        }

        if ($resetTime instanceof \DateTimeInterface) {
            return Carbon::instance($resetTime);
        }

        if (is_string($resetTime)) {
            return Carbon::parse($resetTime);
        }

        Cache::forget($cacheKey);

        return null;
    }
}
