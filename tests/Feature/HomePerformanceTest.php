<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Room;
use App\Models\User;
use App\Services\RoomPresenceService;
use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Inertia\Testing\AssertableInertia as Assert;
use Mockery;
use Tests\TestCase;

class HomePerformanceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_homepage_room_payload_skips_reverb_subscription_lookup(): void
    {
        $broadcastManager = Mockery::mock(BroadcastManager::class);
        $broadcastManager->shouldNotReceive('getPusher');
        $this->app->instance(BroadcastManager::class, $broadcastManager);

        $category = Category::create(['name' => 'Pop']);
        $owner = User::factory()->create();

        Room::create([
            'name' => 'Featured Room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => true,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $this->mock(RoomPresenceService::class, function ($mock): void {
            $mock->shouldReceive('getMemberCountsForRooms')
                ->andReturn([]);
        });

        $this->get(route('home'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Home/Index')
                ->has('featured_rooms', 1));
    }

    public function test_homepage_limits_public_rooms_per_category(): void
    {
        $category = Category::create(['name' => 'Rock']);
        $owner = User::factory()->create();

        for ($i = 0; $i < 15; $i++) {
            Room::create([
                'name' => "Room {$i}",
                'user_id' => $owner->id,
                'category_id' => $category->id,
                'is_public' => true,
                'is_featured' => false,
                'track_duration' => 30,
                'tracks_by_round' => 10,
            ]);
        }

        $this->mock(RoomPresenceService::class, function ($mock): void {
            $mock->shouldReceive('getMemberCountsForRooms')
                ->andReturn([]);
        });

        $this->get(route('home'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Home/Index')
                ->has('categories', 1)
                ->where('categories.0.rooms', fn ($rooms) => count($rooms) === 12));
    }

    public function test_homepage_catalog_sections_are_cached(): void
    {
        $category = Category::create(['name' => 'Jazz']);
        $owner = User::factory()->create();

        Room::create([
            'name' => 'Cached Room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => true,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $this->mock(RoomPresenceService::class, function ($mock): void {
            $mock->shouldReceive('getMemberCountsForRooms')
                ->andReturn([]);
        });

        $this->get(route('home'))->assertOk();

        $this->assertTrue(Cache::has('homepage-featured-rooms-v1'));
        $this->assertTrue(Cache::has('homepage-categories-v1'));
        $this->assertTrue(Cache::has('homepage-private-rooms-v1'));
    }
}
