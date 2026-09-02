<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Playlist;
use App\Models\Room;
use App\Models\Track;
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
                ->where('catalog_items.data.0.is_playing', true)
                ->where('catalog_items.data.0.current_track_index', 3));
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
                ->where('catalog_items.data', fn ($rooms) => count($rooms) === 15)
                ->where('catalog_items.total', 15)
                ->where('catalog_items.data.0.category.id', $category->id)
                ->where('catalog_items.data.0.category.name', 'Rock'));
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
                ->where('catalog_items.data.0.name', 'Busy Room')
                ->where('catalog_items.data.1.name', 'Quiet Room')
                ->where('catalog_items.data.2.name', 'Popular History Room')
                ->where('catalog_items.data.0.rounds_count', 1)
                ->where('catalog_items.data.2.rounds_count', 5));
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

        $this->get(route('home', ['tab' => 'community']))->assertOk();

        $this->assertTrue(Cache::has('homepage-featured-rooms-v2'));
        $this->assertTrue(Cache::has('homepage-public-rooms-v4'));
        $this->assertTrue(Cache::has('homepage-public-categories-v3'));
        $this->assertTrue(Cache::has('homepage-community-categories-v1'));
        $this->assertTrue(Cache::has('homepage-community-room-ids-v1'));
    }

    public function test_homepage_does_not_load_community_rooms_by_default(): void
    {
        $category = Category::create(['name' => 'Community']);
        $owner = User::factory()->create();

        Room::create([
            'name' => 'Community Room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => false,
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
                ->where('catalog', 'official')
                ->missing('private_rooms')
                ->has('community_categories', 1)
                ->where('community_categories.0.rooms_count', 1)
                ->where('catalog_items.data', fn ($rooms) => collect($rooms)->doesntContain(
                    fn ($room) => ($room['name'] ?? '') === 'Community Room',
                )));
    }

    public function test_community_catalog_returns_paginated_private_rooms(): void
    {
        $category = Category::create(['name' => 'Indie']);
        $owner = User::factory()->create();

        for ($i = 0; $i < 20; $i++) {
            Room::create([
                'name' => "Community Room {$i}",
                'user_id' => $owner->id,
                'category_id' => $category->id,
                'is_public' => false,
                'is_featured' => false,
                'track_duration' => 30,
                'tracks_by_round' => 10,
            ]);
        }

        Room::create([
            'name' => 'Password Room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => false,
            'is_featured' => false,
            'password' => 'secret',
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $this->mock(RoomPresenceService::class, function ($mock): void {
            $mock->shouldReceive('getMemberCountsForRooms')
                ->andReturn([]);
        });

        $this->get(route('home', ['tab' => 'community']))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Home/Index')
                ->where('catalog', 'community')
                ->has('catalog_items.data', 16)
                ->where('catalog_items.total', 20)
                ->where('catalog_items.last_page', 2));

        $this->get(route('home', ['tab' => 'community', 'catalog' => 2]))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->has('catalog_items.data', 4));

        $this->get(route('home', ['tab' => 'community', 'category_id' => $category->id]))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('catalog_items.total', 20));
    }

    public function test_homepage_filters_catalog_by_multiple_category_ids(): void
    {
        $categoryA = Category::create(['name' => 'Rock']);
        $categoryB = Category::create(['name' => 'Pop']);
        $categoryC = Category::create(['name' => 'Jazz']);
        $owner = User::factory()->create();

        for ($i = 0; $i < 3; $i++) {
            Room::create([
                'name' => "Rock Room {$i}",
                'user_id' => $owner->id,
                'category_id' => $categoryA->id,
                'is_public' => true,
                'is_featured' => false,
                'track_duration' => 30,
                'tracks_by_round' => 10,
            ]);
        }

        for ($i = 0; $i < 2; $i++) {
            Room::create([
                'name' => "Pop Room {$i}",
                'user_id' => $owner->id,
                'category_id' => $categoryB->id,
                'is_public' => true,
                'is_featured' => false,
                'track_duration' => 30,
                'tracks_by_round' => 10,
            ]);
        }

        Room::create([
            'name' => 'Jazz Room',
            'user_id' => $owner->id,
            'category_id' => $categoryC->id,
            'is_public' => true,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $this->mock(RoomPresenceService::class, function ($mock): void {
            $mock->shouldReceive('getMemberCountsForRooms')
                ->andReturn([]);
        });

        $this->get(route('home', [
            'tab' => 'official',
            'category_ids' => [$categoryA->id, $categoryB->id],
        ]))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Home/Index')
                ->where('catalog', 'official')
                ->where('catalog_category_ids', [$categoryA->id, $categoryB->id])
                ->where('catalog_items.total', 5));
    }

    public function test_homepage_includes_catalog_tab_player_counts(): void
    {
        $category = Category::create(['name' => 'Pop']);
        $owner = User::factory()->create();

        $publicRoom = Room::create([
            'name' => 'Public Room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $privateRoom = Room::create([
            'name' => 'Private Room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => false,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $this->mock(RoomPresenceService::class, function ($mock) use ($publicRoom, $privateRoom): void {
            $mock->shouldReceive('getMemberCountsForRooms')
                ->andReturnUsing(function ($rooms) use ($publicRoom, $privateRoom) {
                    $counts = [];

                    foreach ($rooms as $room) {
                        $id = is_array($room) ? $room['id'] : $room->id;
                        $counts[$id] = match ($id) {
                            $publicRoom->id => 10,
                            $privateRoom->id => 7,
                            default => 0,
                        };
                    }

                    return $counts;
                });
        });

        $this->get(route('home'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Home/Index')
                ->where('catalog_tab_player_counts.official', 10)
                ->where('catalog_tab_player_counts.community', 7));
    }

    public function test_community_tab_player_count_uses_live_presence_not_cached_index(): void
    {
        $category = Category::create(['name' => 'Pop']);
        $owner = User::factory()->create();

        $room = Room::create([
            'name' => 'Private Room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => false,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        Cache::put('homepage-community-room-ids-v1', [$room->id], now()->addMinute());

        $this->mock(RoomPresenceService::class, function ($mock) use ($room): void {
            $mock->shouldReceive('getMemberCountsForRooms')
                ->andReturnUsing(function ($rooms) use ($room) {
                    $counts = [];

                    foreach ($rooms as $candidate) {
                        $id = is_array($candidate) ? $candidate['id'] : $candidate->id;
                        $counts[$id] = $id === $room->id ? 12 : 0;
                    }

                    return $counts;
                });
        });

        $this->get(route('home'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('catalog_tab_player_counts.community', 12));
    }

    public function test_community_catalog_prioritizes_playing_rooms_with_equal_player_count(): void
    {
        $category = Category::create(['name' => 'Pop']);
        $owner = User::factory()->create();

        $idleRoom = Room::create([
            'name' => 'Idle Room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => false,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $liveRoom = Room::create([
            'name' => 'Live Room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => false,
            'is_featured' => false,
            'is_playing' => true,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        for ($i = 0; $i < 5; $i++) {
            $idleRoom->rounds()->create([
                'is_playing' => false,
                'current' => 0,
                'tracks' => [1, 2, 3],
                'finished_at' => now(),
            ]);
        }

        $liveRoom->rounds()->create([
            'is_playing' => true,
            'current' => 2,
            'tracks' => [1, 2, 3, 4, 5],
        ]);

        $this->mock(RoomPresenceService::class, function ($mock) use ($idleRoom, $liveRoom): void {
            $mock->shouldReceive('getMemberCountsForRooms')
                ->andReturnUsing(function ($rooms) use ($idleRoom, $liveRoom) {
                    $counts = [];

                    foreach ($rooms as $room) {
                        $id = is_array($room) ? $room['id'] : $room->id;
                        $counts[$id] = in_array($id, [$idleRoom->id, $liveRoom->id], true) ? 8 : 0;
                    }

                    return $counts;
                });
        });

        $this->get(route('home', ['tab' => 'community']))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('catalog_items.data.0.id', $liveRoom->id)
                ->where('catalog_items.data.1.id', $idleRoom->id));
    }

    public function test_community_catalog_breaks_ties_by_round_count(): void
    {
        $category = Category::create(['name' => 'Pop']);
        $owner = User::factory()->create();

        $veteranRoom = Room::create([
            'name' => 'Veteran Room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => false,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $newRoom = Room::create([
            'name' => 'New Room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => false,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $veteranRoom->rounds()->create([
            'is_playing' => false,
            'current' => 0,
            'tracks' => [1, 2, 3],
        ]);

        $veteranRoom->rounds()->create([
            'is_playing' => false,
            'current' => 0,
            'tracks' => [1, 2, 3],
        ]);

        $this->mock(RoomPresenceService::class, function ($mock) use ($newRoom, $veteranRoom): void {
            $mock->shouldReceive('getMemberCountsForRooms')
                ->andReturnUsing(function ($rooms) use ($newRoom, $veteranRoom) {
                    $counts = [];

                    foreach ($rooms as $room) {
                        $id = is_array($room) ? $room['id'] : $room->id;
                        $counts[$id] = in_array($id, [$newRoom->id, $veteranRoom->id], true) ? 5 : 0;
                    }

                    return $counts;
                });
        });

        $this->get(route('home', ['tab' => 'community']))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('catalog_items.data.0.id', $veteranRoom->id)
                ->where('catalog_items.data.1.id', $newRoom->id));
    }

    public function test_community_catalog_is_sorted_by_player_count(): void
    {
        $category = Category::create(['name' => 'Pop']);
        $owner = User::factory()->create();

        $quietRoom = Room::create([
            'name' => 'Quiet Room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => false,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $busyRoom = Room::create([
            'name' => 'Busy Room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => false,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $this->mock(RoomPresenceService::class, function ($mock) use ($quietRoom, $busyRoom): void {
            $mock->shouldReceive('getMemberCountsForRooms')
                ->andReturnUsing(function ($rooms) use ($quietRoom, $busyRoom) {
                    $counts = [];

                    foreach ($rooms as $room) {
                        $id = is_array($room) ? $room['id'] : $room->id;
                        $counts[$id] = match ($id) {
                            $busyRoom->id => 42,
                            $quietRoom->id => 3,
                            default => 0,
                        };
                    }

                    return $counts;
                });
        });

        $this->get(route('home', ['tab' => 'community']))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->has('catalog_items.data', 2)
                ->where('catalog_items.data.0.id', $busyRoom->id)
                ->where('catalog_items.data.0.subscriptions', 42)
                ->where('catalog_items.data.0.tracks_count', fn ($count) => is_int($count))
                ->where('catalog_items.data.1.id', $quietRoom->id));
    }

    public function test_community_catalog_includes_playlist_tracks_count(): void
    {
        $category = Category::create(['name' => 'Pop']);
        $owner = User::factory()->create();
        $playlist = Playlist::create([
            'name' => 'Community Playlist',
            'user_id' => $owner->id,
        ]);

        $room = Room::create([
            'name' => 'Community Room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => false,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $room->playlists()->attach($playlist->id);

        Track::create([
            'playlist_id' => $playlist->id,
            'user_id' => $owner->id,
            'provider' => 'youtube',
            'provider_id' => 'track-a',
            'preview_url' => 'https://example.com/a',
            'artwork_url' => 'https://example.com/a.jpg',
        ]);

        Track::create([
            'playlist_id' => $playlist->id,
            'user_id' => $owner->id,
            'provider' => 'youtube',
            'provider_id' => 'track-b',
            'preview_url' => 'https://example.com/b',
            'artwork_url' => 'https://example.com/b.jpg',
        ]);

        $this->mock(RoomPresenceService::class, function ($mock): void {
            $mock->shouldReceive('getMemberCountsForRooms')
                ->andReturn([]);
        });

        $this->get(route('home', ['tab' => 'community']))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('catalog_items.data.0.tracks_count', 2));
    }

    public function test_mine_catalog_is_empty_when_user_has_no_private_rooms(): void
    {
        $user = User::factory()->create();

        $this->mock(RoomPresenceService::class, function ($mock): void {
            $mock->shouldReceive('getMemberCountsForRooms')
                ->andReturn([]);
        });

        $this->actingAs($user)
            ->get(route('home', ['tab' => 'mine']))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Home/Index')
                ->where('catalog', 'mine')
                ->where('catalog_items.data', [])
                ->where('catalog_items.total', 0));
    }

    public function test_favorites_catalog_lists_bookmarked_rooms_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        $owner = User::factory()->create();
        $category = Category::create(['name' => 'Pop']);

        $bookmarkedRoom = Room::create([
            'name' => 'Bookmarked Room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        Room::create([
            'name' => 'Other Room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $user->bookmarkedRooms()->attach($bookmarkedRoom);

        $this->mock(RoomPresenceService::class, function ($mock): void {
            $mock->shouldReceive('getMemberCountsForRooms')
                ->andReturn([]);
        });

        $this->actingAs($user)
            ->get(route('home', ['tab' => 'favorites']))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Home/Index')
                ->where('catalog', 'favorites')
                ->has('catalog_items.data', 1)
                ->where('catalog_items.data.0.name', 'Bookmarked Room'));
    }

    public function test_favorites_tab_falls_back_to_official_for_guests(): void
    {
        $this->mock(RoomPresenceService::class, function ($mock): void {
            $mock->shouldReceive('getMemberCountsForRooms')
                ->andReturn([]);
        });

        $this->get(route('home', ['tab' => 'favorites']))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('catalog', 'official'));
    }

    public function test_catalog_partial_reload_only_fetches_catalog_props(): void
    {
        $category = Category::create(['name' => 'Pop']);
        $owner = User::factory()->create();

        Room::create([
            'name' => 'Official Room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        for ($i = 0; $i < 20; $i++) {
            Room::create([
                'name' => "Community Room {$i}",
                'user_id' => $owner->id,
                'category_id' => $category->id,
                'is_public' => false,
                'is_featured' => false,
                'track_duration' => 30,
                'tracks_by_round' => 10,
            ]);
        }

        $this->mock(RoomPresenceService::class, function ($mock): void {
            $mock->shouldReceive('getMemberCountsForRooms')
                ->andReturn([]);
        });

        $this->get(route('home', ['tab' => 'community']))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('catalog', 'community')
                ->reloadOnly(['catalog', 'catalog_items', 'catalog_category_ids', 'catalog_category_id'], fn (Assert $reload) => $reload
                    ->where('catalog', 'community')
                    ->has('catalog_items.data', 16)
                    ->missing('featured_rooms')
                    ->missing('public_categories')
                    ->missing('community_categories')
                ));
    }
}
