<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Playlist;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ModerationModeratorsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::forget('moderation.moderators.snapshot');
    }

    public function test_admin_can_list_moderators(): void
    {
        [$admin, $moderator, $room] = $this->createAdminAndPublicRoomModerator();

        $response = $this->actingAs($admin)->get(route('moderation.moderators.index', [
            'search' => $moderator->name,
        ]));

        $response->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Moderation/Moderators')
                ->has('moderators.data', 1)
                ->where('moderators.data.0.id', $moderator->id)
                ->where('moderators.data.0.rooms.0.id', $room->id)
                ->has('stats.total_moderators')
                ->has('coverage.rooms_without_moderators')
                ->has('moderators.data.0.last_activity_at')
            );
    }

    public function test_public_moderator_cannot_access_moderators_page(): void
    {
        [$moderator] = $this->createPublicRoomModerator();

        $this->actingAs($moderator)
            ->get(route('moderation.moderators.index'))
            ->assertForbidden();
    }

    public function test_admin_can_revoke_room_moderation_access(): void
    {
        [$admin, $moderator, $room] = $this->createAdminAndPublicRoomModerator();
        $owner = User::factory()->create(['email_verified_at' => now()]);
        $room->update(['user_id' => $owner->id]);
        $room->moderators()->syncWithoutDetaching([$moderator->id, $owner->id]);

        Cache::put('public-moderators', ['cached'], 3600);
        Cache::put('moderation.moderators.snapshot', ['cached' => true], 3600);

        $this->actingAs($admin)
            ->delete(route('moderation.moderators.rooms.detach', [
                'room' => $room,
                'user' => $moderator,
            ]))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertFalse($room->fresh()->moderators()->where('users.id', $moderator->id)->exists());
        $this->assertTrue($room->fresh()->moderators()->where('users.id', $owner->id)->exists());
        $this->assertNull(Cache::get('public-moderators'));
        $this->assertNull(Cache::get('moderation.moderators.snapshot'));
    }

    public function test_admin_cannot_revoke_room_owner_moderation_access(): void
    {
        [$admin, $moderator, $room] = $this->createAdminAndPublicRoomModerator();

        $this->actingAs($admin)
            ->from(route('moderation.moderators.index'))
            ->delete(route('moderation.moderators.rooms.detach', [
                'room' => $room,
                'user' => $moderator,
            ]))
            ->assertRedirect()
            ->assertSessionHasErrors('user');

        $this->assertTrue($room->fresh()->moderators()->where('users.id', $moderator->id)->exists());
    }

    public function test_admin_can_revoke_playlist_moderation_access(): void
    {
        $admin = User::factory()->create([
            'is_administrator' => true,
            'email_verified_at' => now(),
        ]);

        $owner = User::factory()->create(['email_verified_at' => now()]);
        $moderator = User::factory()->create([
            'name' => 'PlaylistModerator',
            'email_verified_at' => now(),
        ]);

        $playlist = Playlist::query()->create([
            'name' => 'Public Playlist',
            'user_id' => $owner->id,
            'is_public' => true,
        ]);

        $playlist->moderators()->attach($moderator->id);

        $this->actingAs($admin)
            ->delete(route('moderation.moderators.playlists.detach', [
                'playlist' => $playlist,
                'user' => $moderator,
            ]))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertFalse($playlist->fresh()->moderators()->where('users.id', $moderator->id)->exists());
    }

    /**
     * @return array{0: User, 1: User, 2: Room}
     */
    private function createAdminAndPublicRoomModerator(): array
    {
        $admin = User::factory()->create([
            'is_administrator' => true,
            'email_verified_at' => now(),
        ]);

        [$moderator, $room] = $this->createPublicRoomModerator();

        return [$admin, $moderator, $room];
    }

    /**
     * @return array{0: User, 1: Room}
     */
    private function createPublicRoomModerator(): array
    {
        $moderator = User::factory()->create([
            'name' => 'PublicRoomModerator',
            'email_verified_at' => now(),
        ]);

        $room = Room::factory()->create([
            'user_id' => $moderator->id,
            'category_id' => Category::factory()->create()->id,
            'slug' => 'moderator-room-'.$moderator->id,
            'is_public' => true,
            'is_active' => true,
            'is_featured' => false,
            'deleted_at' => null,
        ]);

        $room->moderators()->attach($moderator->id);

        return [$moderator, $room];
    }
}
