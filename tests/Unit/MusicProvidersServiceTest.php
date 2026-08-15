<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\MusicProvidersService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class MusicProvidersServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_returns_collections_when_reading_cached_results(): void
    {
        Cache::put('music_search:hello:itunes', [
            'results' => [[
                'provider' => 'itunes',
                'provider_id' => '123',
                'preview_url' => 'https://example.com/preview.mp3',
            ]],
            'errors' => [],
        ], 60);

        $user = User::factory()->create();

        $response = app(MusicProvidersService::class)->search($user, 'hello', 'itunes');

        $this->assertInstanceOf(Collection::class, $response['results']);
        $this->assertInstanceOf(Collection::class, $response['errors']);
        $this->assertCount(1, $response['results']);
    }
}
