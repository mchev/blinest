<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomBookmarkTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_bookmark_and_unbookmark_a_room(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Test']);
        $room = Room::create([
            'name' => 'Bookmark Room',
            'user_id' => $user->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
        ]);

        $this->actingAs($user)
            ->post("/rooms/{$room->id}/bookmark")
            ->assertOk()
            ->assertJson(['is_bookmarked' => true]);

        $this->assertTrue(
            $user->bookmarkedRooms()->whereKey($room->id)->exists()
        );

        $this->actingAs($user)
            ->delete("/rooms/{$room->id}/bookmark")
            ->assertOk()
            ->assertJson(['is_bookmarked' => false]);

        $this->assertFalse(
            $user->bookmarkedRooms()->whereKey($room->id)->exists()
        );
    }

    public function test_bookmarking_an_already_bookmarked_room_is_idempotent(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Test']);
        $room = Room::create([
            'name' => 'Bookmark Room',
            'user_id' => $user->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
        ]);

        $user->bookmarkedRooms()->attach($room);

        $this->actingAs($user)
            ->post("/rooms/{$room->id}/bookmark")
            ->assertOk()
            ->assertJson(['is_bookmarked' => true]);

        $this->assertSame(
            1,
            $user->bookmarkedRooms()->whereKey($room->id)->count()
        );
    }
}
