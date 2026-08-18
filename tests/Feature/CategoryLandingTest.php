<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Room;
use App\Models\User;
use App\Services\HomeCatalogService;
use App\Services\RoomPresenceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class CategoryLandingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_category_landing_page_renders_official_rooms(): void
    {
        $category = Category::create([
            'name' => 'Rap FR',
            'slug' => 'rap-fr',
        ]);

        $owner = User::factory()->create();

        Room::create([
            'name' => 'Rap FR Room',
            'slug' => 'rap-fr-room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $this->mock(HomeCatalogService::class, function ($mock) use ($category): void {
            $mock->shouldReceive('officialRoomsForCategory')
                ->once()
                ->with($category->id)
                ->andReturn([
                    [
                        'id' => 1,
                        'slug' => 'rap-fr-room',
                        'name' => 'Rap FR Room',
                        'category' => ['id' => $category->id, 'name' => $category->name],
                    ],
                ]);
        });

        $response = $this->get(route('categories.show', $category->slug));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Categories/Show')
            ->where('category.slug', 'rap-fr')
            ->has('content.heading')
            ->has('content.faq', 3)
            ->has('rooms', 1)
            ->where('roomsCount', 1)
        );
    }

    public function test_category_landing_page_returns_404_when_no_official_rooms(): void
    {
        $category = Category::create([
            'name' => 'Empty',
            'slug' => 'empty',
        ]);

        $this->mock(HomeCatalogService::class, function ($mock) use ($category): void {
            $mock->shouldReceive('officialRoomsForCategory')
                ->once()
                ->with($category->id)
                ->andReturn([]);
        });

        $this->get(route('categories.show', $category->slug))->assertNotFound();
    }

    public function test_category_landing_url_uses_blind_test_prefix(): void
    {
        $category = Category::create([
            'name' => 'Disney',
            'slug' => 'disney',
        ]);

        $owner = User::factory()->create();

        Room::create([
            'name' => 'Disney Room',
            'slug' => 'disney-room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $this->mock(RoomPresenceService::class, function ($mock): void {
            $mock->shouldReceive('getMemberCountsForRooms')->andReturn([]);
            $mock->shouldReceive('getMemberCount')->andReturn(0);
        });

        $response = $this->get('/blind-test-disney');

        $response->assertOk();
    }

    public function test_category_landing_page_has_seo_meta(): void
    {
        $category = Category::create([
            'name' => 'Les décennies',
            'slug' => 'les-decennies',
        ]);

        $owner = User::factory()->create();

        Room::create([
            'name' => 'Années 80',
            'slug' => 'annees-80',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $this->mock(RoomPresenceService::class, function ($mock): void {
            $mock->shouldReceive('getMemberCountsForRooms')->andReturn([]);
            $mock->shouldReceive('getMemberCount')->andReturn(0);
        });

        $response = $this->get(route('categories.show', $category->slug));

        $response->assertOk();
        $response->assertSee('name="description"', false);
        $response->assertSee('blind test', false);
    }

    public function test_genre_musical_category_page_uses_specific_content(): void
    {
        $category = Category::create([
            'name' => 'Genre musical',
            'slug' => 'genre-musical',
        ]);

        $owner = User::factory()->create();

        Room::create([
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
            $mock->shouldReceive('getMemberCountsForRooms')->andReturn([]);
            $mock->shouldReceive('getMemberCount')->andReturn(0);
        });

        $response = $this->get(route('categories.show', $category->slug));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->where('content.heading', 'Blind test par genre musical')
            ->where('content.rooms_heading', 'Salles officielles par genre musical')
        );
        $response->assertSee('Rap FR', false);
        $response->assertSee('Blind test par genre musical', false);
    }

    public function test_category_slug_is_generated_on_create(): void
    {
        $category = Category::create(['name' => 'Genre musical']);

        $this->assertSame('genre-musical', $category->slug);
    }
}
