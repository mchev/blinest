<?php

namespace App\Support;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Cache;
use Tighten\Ziggy\Ziggy;

class ZiggyRouteCache
{
    public function __construct(private Router $router) {}

    /**
     * @return array<string, mixed>
     */
    public function routes(): array
    {
        $this->forgetLegacyCacheKeys();

        if (! $this->shouldCache()) {
            return (new Ziggy)->toArray();
        }

        return Cache::remember($this->cacheKey(), 3600, fn () => (new Ziggy)->toArray());
    }

    public function bust(): void
    {
        Cache::forget($this->cacheKey());
    }

    public function cacheKey(): string
    {
        $routeCachePath = app()->bootstrapPath('cache/routes-v7.php');

        if (is_file($routeCachePath)) {
            return 'inertia_ziggy_routes:'.filemtime($routeCachePath).':'.filesize($routeCachePath);
        }

        $names = collect($this->router->getRoutes()->getRoutesByName())
            ->keys()
            ->sort()
            ->values()
            ->all();

        return 'inertia_ziggy_routes:'.md5(implode("\n", $names));
    }

    private function shouldCache(): bool
    {
        return app()->isProduction();
    }

    private function forgetLegacyCacheKeys(): void
    {
        Cache::forget('inertia_ziggy_routes_v4');
    }
}
