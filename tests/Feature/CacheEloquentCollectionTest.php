<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CacheEloquentCollectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_cache_only_stores_array_payloads_when_unserialization_is_restricted(): void
    {
        config(['cache.serializable_classes' => false]);

        Cache::put('scalar-cache-test', [
            'rooms' => [
                ['id' => 1, 'name' => 'Room A'],
            ],
        ]);

        $cached = Cache::get('scalar-cache-test');

        $this->assertIsArray($cached);
        $this->assertSame('Room A', $cached['rooms'][0]['name']);
    }
}
