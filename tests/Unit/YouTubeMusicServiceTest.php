<?php

namespace Tests\Unit;

use App\Services\MusicProviders\YouTubeMusicService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class YouTubeMusicServiceTest extends TestCase
{
    public function test_quota_exceeded_reads_iso_string_reset_time_from_cache(): void
    {
        $resetTime = now()->addHour();

        Cache::put('youtube_api_quota_exceeded', true, $resetTime);
        Cache::put('youtube_api_quota_reset_time', $resetTime->toIso8601String(), $resetTime);

        Http::fake();

        request()->merge(['term' => 'g-dragon']);

        $results = app(YouTubeMusicService::class)->searchTrack();

        $this->assertTrue($results->first()['quota_exceeded'] ?? false);
    }

    public function test_corrupt_reset_time_clears_quota_cache_without_error(): void
    {
        $serialized = serialize(now()->addHour());
        $serialized = preg_replace('/O:\d+:"[^"]+"/', 'O:22:"__PHP_Incomplete_Class"', $serialized);
        $brokenResetTime = unserialize($serialized);

        Cache::put('youtube_api_quota_exceeded', true, now()->addHour());
        Cache::put('youtube_api_quota_reset_time', $brokenResetTime, now()->addHour());

        Http::fake([
            'www.googleapis.com/youtube/v3/search*' => Http::response([
                'items' => [],
            ]),
        ]);

        request()->merge(['term' => 'g-dragon']);

        $results = app(YouTubeMusicService::class)->searchTrack();

        $this->assertFalse(Cache::has('youtube_api_quota_exceeded'));
        $this->assertFalse(Cache::has('youtube_api_quota_reset_time'));
        $this->assertFalse($results->first()['quota_exceeded'] ?? false);
    }
}
