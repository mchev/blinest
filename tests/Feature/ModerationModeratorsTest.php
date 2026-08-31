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

    public function test_revoke_from_room_page_invalidates_moderators_snapshot(): void
    {
        [$admin, $moderator, $room] = $this->createAdminAndPublicRoomModerator();
        $owner = User::factory()->create(['email_verified_at' => now()]);
        $room->update(['user_id' => $owner->id]);
        $room->moderators()->syncWithoutDetaching([$moderator->id, $owner->id]);

        $this->actingAs($admin)->get(route('moderation.moderators.index'));
        $this->assertNotNull(Cache::get('moderation.moderators.snapshot'));

        Cache::put('moderation.moderators.snapshot', ['cached' => true], 3600);

        $this->actingAs($owner)
            ->delete(route('rooms.moderators.detach', ['room' => $room->id]), [
                'user_id' => $moderator->id,
            ])
            ->assertRedirect();

        $this->assertNull(Cache::get('moderation.moderators.snapshot'));
    }

    public function test_revoke_updates_moderators_assignments_after_cache_warmup(): void
    {
        [$admin, $moderator, $room] = $this->createAdminAndPublicRoomModerator();
        $owner = User::factory()->create(['email_verified_at' => now()]);
        $room->update(['user_id' => $owner->id]);

        $secondRoom = Room::factory()->create([
            'user_id' => $owner->id,
            'category_id' => Category::factory()->create()->id,
            'slug' => 'moderator-room-second-'.$moderator->id,
            'is_public' => true,
            'is_active' => true,
            'is_featured' => false,
            'deleted_at' => null,
        ]);

        $room->moderators()->syncWithoutDetaching([$moderator->id, $owner->id]);
        $secondRoom->moderators()->attach($moderator->id);

        $this->actingAs($admin)->get(route('moderation.moderators.index', [
            'search' => $moderator->name,
        ]));

        $this->actingAs($admin)
            ->delete(route('moderation.moderators.rooms.detach', [
                'room' => $room,
                'user' => $moderator,
            ]))
            ->assertRedirect();

        $this->actingAs($admin)
            ->get(route('moderation.moderators.index', [
                'search' => $moderator->name,
            ]))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->has('moderators.data', 1)
                ->where('moderators.data.0.rooms.0.id', $secondRoom->id)
                ->where('moderators.data.0.rooms_count', 1)
            );
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

    public function test_admin_moderator_assignments_do_not_count_for_coverage_gaps(): void
    {
        $admin = User::factory()->create([
            'is_administrator' => true,
            'email_verified_at' => now(),
        ]);

        $owner = User::factory()->create(['email_verified_at' => now()]);

        $roomWithOnlyAdminModerator = Room::factory()->create([
            'user_id' => $owner->id,
            'category_id' => Category::factory()->create()->id,
            'slug' => 'admin-only-mod-room-'.$admin->id,
            'is_public' => true,
            'is_active' => true,
            'is_featured' => false,
            'deleted_at' => null,
        ]);

        $roomWithOnlyAdminModerator->moderators()->attach($admin->id);

        $playlistWithOnlyAdminModerator = Playlist::query()->create([
            'name' => 'Admin Only Mod Playlist',
            'user_id' => $owner->id,
            'is_public' => true,
        ]);

        $playlistWithOnlyAdminModerator->moderators()->attach($admin->id);

        $this->actingAs($admin)
            ->get(route('moderation.moderators.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('stats.rooms_without_moderators', 1)
                ->where('stats.playlists_without_moderators', 1)
                ->where('coverage.rooms_without_moderators.0.id', $roomWithOnlyAdminModerator->id)
                ->where('coverage.playlists_without_moderators.0.id', $playlistWithOnlyAdminModerator->id)
            );
    }

    public function test_public_room_with_non_admin_moderator_is_not_listed_as_without_moderators(): void
    {
        [$admin, $moderator, $room] = $this->createAdminAndPublicRoomModerator();

        $this->actingAs($admin)
            ->get(route('moderation.moderators.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('stats.rooms_without_moderators', 0)
                ->where('coverage.rooms_without_moderators', [])
            );
    }

    public function test_administrator_is_not_listed_as_public_moderator(): void
    {
        $admin = User::factory()->create([
            'is_administrator' => true,
            'email_verified_at' => now(),
        ]);

        $owner = User::factory()->create(['email_verified_at' => now()]);

        $room = Room::factory()->create([
            'user_id' => $owner->id,
            'category_id' => Category::factory()->create()->id,
            'slug' => 'admin-hidden-mod-room-'.$admin->id,
            'is_public' => true,
            'is_active' => true,
            'is_featured' => false,
            'deleted_at' => null,
        ]);

        $room->moderators()->attach($admin->id);

        $this->actingAs($admin)
            ->get(route('moderation.moderators.index', [
                'search' => $admin->name,
            ]))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('moderators.total', 0)
            );
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
