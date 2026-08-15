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

    public function test_homepage_room_payload_includes_photo_without_reverb_lookup(): void
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
            'photo_path' => 'rooms/test.webp',
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
                ->has('featured_rooms', 1)
                ->where('featured_rooms.0.photo', fn ($photo) => is_string($photo) && $photo !== ''));
    }

    public function test_to_homepage_array_includes_cached_track_index_when_playing(): void
    {
        $category = Category::create(['name' => 'Pop']);
        $owner = User::factory()->create();

        $room = Room::create([
            'name' => 'Live Room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'is_playing' => true,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $room->rounds()->create([
            'is_playing' => true,
            'current' => 4,
            'tracks' => [1, 2, 3, 4, 5],
        ]);

        $payload = $room->fresh()->toHomepageArray();

        $this->assertSame(4, $payload['current_track_index']);
        $this->assertArrayHasKey('photo', $payload);
    }

    public function test_homepage_overlays_live_room_state_on_cached_catalog(): void
    {
        $category = Category::create(['name' => 'Pop']);
        $owner = User::factory()->create();

        $room = Room::create([
            'name' => 'Cached Live Room',
            'slug' => 'cached-live-room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        Cache::put('homepage-public-rooms-v4', [[
            'id' => $room->id,
            'slug' => $room->slug,
            'name' => $room->name,
            'is_playing' => false,
            'current_track_index' => 0,
            'tracks_by_round' => 10,
            'is_public' => true,
            'is_autostart' => true,
            'photo' => '',
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
            ],
        ]], now()->addMinute());

        $room->update(['is_playing' => true]);
        $room->rounds()->create([
            'is_playing' => true,
            'current' => 3,
            'tracks' => [1, 2, 3, 4, 5],
        ]);

        $this->mock(RoomPresenceService::class, function ($mock): void {
            $mock->shouldReceive('getMemberCountsForRooms')
                ->andReturn([]);
        });

        $this->get(route('home'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Home/Index')
                ->where('public_rooms.0.is_playing', true)
                ->where('public_rooms.0.current_track_index', 3));
    }

    public function test_room_public_state_endpoint_returns_live_progress(): void
    {
        $category = Category::create(['name' => 'Pop']);
        $owner = User::factory()->create();

        $room = Room::create([
            'name' => 'Public State Room',
            'slug' => 'public-state-room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'is_playing' => true,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $room->rounds()->create([
            'is_playing' => true,
            'current' => 2,
            'tracks' => [1, 2, 3, 4, 5],
        ]);

        $this->mock(RoomPresenceService::class, function ($mock) use ($room): void {
            $mock->shouldReceive('getMemberCount')
                ->with(Mockery::on(fn ($arg) => $arg->id === $room->id))
                ->andReturn(3);
        });

        $this->getJson(route('rooms.public-state', ['room' => $room->slug]))
            ->assertOk()
            ->assertJson([
                'roomId' => $room->id,
                'memberCount' => 3,
                'currentTrackIndex' => 2,
                'tracksByRound' => 10,
                'isPlaying' => true,
            ]);
    }

    public function test_homepage_includes_all_public_rooms(): void
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
                ->has('public_categories', 1)
                ->where('public_categories.0.rooms_count', 15)
                ->where('public_rooms', fn ($rooms) => count($rooms) === 15)
                ->where('public_rooms.0.category.id', $category->id)
                ->where('public_rooms.0.category.name', 'Rock'));
    }

    public function test_homepage_public_rooms_are_sorted_by_popularity(): void
    {
        $category = Category::create(['name' => 'Pop']);
        $owner = User::factory()->create();

        $quietRoom = Room::create([
            'name' => 'Quiet Room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $busyRoom = Room::create([
            'name' => 'Busy Room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $popularButEmptyRoom = Room::create([
            'name' => 'Popular History Room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $quietRoom->rounds()->createMany([
            ['is_playing' => false, 'current' => 0, 'tracks' => [1], 'finished_at' => now()],
        ]);

        $busyRoom->rounds()->createMany([
            ['is_playing' => false, 'current' => 0, 'tracks' => [1], 'finished_at' => now()],
        ]);

        for ($i = 0; $i < 5; $i++) {
            $popularButEmptyRoom->rounds()->create([
                'is_playing' => false,
                'current' => 0,
                'tracks' => [1],
                'finished_at' => now(),
            ]);
        }

        $this->mock(RoomPresenceService::class, function ($mock) use ($quietRoom, $busyRoom, $popularButEmptyRoom): void {
            $mock->shouldReceive('getMemberCountsForRooms')
                ->andReturn([
                    $quietRoom->id => 1,
                    $busyRoom->id => 12,
                    $popularButEmptyRoom->id => 0,
                ]);
        });

        $this->get(route('home'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Home/Index')
                ->where('public_rooms.0.name', 'Busy Room')
                ->where('public_rooms.1.name', 'Quiet Room')
                ->where('public_rooms.2.name', 'Popular History Room')
                ->where('public_rooms.0.rounds_count', 1)
                ->where('public_rooms.2.rounds_count', 5));
    }

    public function test_homepage_exposes_hidden_category_ids_for_seasonal_rooms(): void
    {
        config(['blinest.homepage_hidden_category_ids' => [5]]);

        $category = Category::create(['name' => 'Pop']);
        $owner = User::factory()->create();

        Room::create([
            'name' => 'Regular Room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
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
                ->where('homepage_hidden_category_ids', [5]));
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

        $this->assertTrue(Cache::has('homepage-featured-rooms-v2'));
        $this->assertTrue(Cache::has('homepage-public-rooms-v4'));
        $this->assertTrue(Cache::has('homepage-public-categories-v3'));
        $this->assertTrue(Cache::has('homepage-private-rooms-v2'));
    }
}
