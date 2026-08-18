<?php

namespace Tests\Unit;

use App\Support\ZiggyRouteCache;
use Tests\TestCase;

class ZiggyRouteCacheTest extends TestCase
{
    public function test_routes_payload_includes_named_routes(): void
    {
        $routes = app(ZiggyRouteCache::class)->routes();

        $this->assertArrayHasKey('routes', $routes);
        $this->assertArrayHasKey('categories.show', $routes['routes']);
        $this->assertArrayHasKey('rankings.index', $routes['routes']);
    }

    public function test_cache_key_is_stable_for_current_route_registry(): void
    {
        $cache = app(ZiggyRouteCache::class);

        $this->assertSame($cache->cacheKey(), $cache->cacheKey());
        $this->assertStringStartsWith('inertia_ziggy_routes:', $cache->cacheKey());
    }
}
