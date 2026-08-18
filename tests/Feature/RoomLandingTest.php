<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Room;
use App\Models\User;
use App\Services\RoomPresenceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class RoomLandingTest extends TestCase
{
    use RefreshDatabase;

    public function test_official_room_page_includes_seo_payload(): void
    {
        $category = Category::create([
            'name' => 'Genre musical',
            'slug' => 'genre-musical',
        ]);

        $owner = User::factory()->create();

        $room = Room::create([
            'name' => 'Rap FR',
            'slug' => 'rap-fr',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        Room::create([
            'name' => 'Rock',
            'slug' => 'rock',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $this->mock(RoomPresenceService::class, function ($mock): void {
            $mock->shouldReceive('getMemberCount')->andReturn(3);
        });

        $response = $this->get(route('rooms.show', $room->slug));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Rooms/Show')
            ->has('seo.content.intro')
            ->has('seo.breadcrumbs', 3)
            ->has('seo.similar_rooms', 1)
            ->where('seo.stats.players_online', 3)
        );

        $response->assertSee('Blind test Rap FR', false);
        $response->assertSee('name="description"', false);
        $response->assertSee('id="seo-landing-server"', false);
        $response->assertSee('FAQPage', false);
        $response->assertSee('BreadcrumbList', false);
    }

    public function test_official_room_server_seo_is_visible_to_search_bots(): void
    {
        $category = Category::create([
            'name' => 'Genre musical',
            'slug' => 'genre-musical',
        ]);

        $owner = User::factory()->create();

        $room = Room::create([
            'name' => 'Rap FR',
            'slug' => 'rap-fr',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $this->mock(RoomPresenceService::class, function ($mock): void {
            $mock->shouldReceive('getMemberCount')->andReturn(3);
        });

        $response = $this->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
        ])->get(route('rooms.show', $room->slug));

        $response->assertOk();
        $response->assertSee('id="seo-landing-server"', false);
        $response->assertSee(__('Room page FAQ q1', ['room' => $room->name]), false);
        $this->assertGuest();
    }

    public function test_non_official_public_room_has_no_seo_payload(): void
    {
        $category = Category::create([
            'name' => 'C',
            'slug' => 'c',
        ]);

        $owner = User::factory()->create();

        $room = Room::create([
            'name' => 'Test Room',
            'slug' => 'test-room-title-0',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $this->mock(RoomPresenceService::class, function ($mock): void {
            $mock->shouldReceive('getMemberCount')->andReturn(0);
        });

        $response = $this->get(route('rooms.show', $room->slug));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Rooms/Show')
            ->where('seo', null)
        );
    }
}
