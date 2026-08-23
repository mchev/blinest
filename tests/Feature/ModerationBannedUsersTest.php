<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ModerationBannedUsersTest extends TestCase
{
    use RefreshDatabase;

    public function test_moderator_can_list_banned_users_without_sensitive_fields(): void
    {
        [$moderator, $bannedUser] = $this->createModeratorAndBannedUser();

        $response = $this->actingAs($moderator)->get(route('moderation.banned-users.index'));

        $response->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Moderation/BannedUsers')
                ->where('canViewSensitiveData', false)
                ->has('bannedUsers.data', 1)
                ->where('bannedUsers.data.0.id', $bannedUser->id)
                ->missing('bannedUsers.data.0.email')
                ->missing('bannedUsers.data.0.ip')
                ->missing('bannedUsers.data.0.active_ban.ip')
            );
    }

    public function test_admin_can_see_sensitive_fields_on_banned_users(): void
    {
        $admin = User::factory()->create([
            'is_administrator' => true,
            'email_verified_at' => now(),
        ]);

        $bannedUser = User::factory()->create([
            'name' => 'BannedAdminView',
            'email' => 'banned-admin-view@example.com',
            'ip' => '203.0.113.44',
            'email_verified_at' => now(),
        ]);

        $bannedUser->ban([
            'comment' => 'Spam répété',
            'ip' => '203.0.113.44',
        ]);

        $response = $this->actingAs($admin)->get(route('moderation.banned-users.index', [
            'search' => 'BannedAdminView',
        ]));

        $response->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('canViewSensitiveData', true)
                ->where('bannedUsers.data.0.email', 'banned-admin-view@example.com')
                ->where('bannedUsers.data.0.active_ban.ip', '203.0.113.44')
            );
    }

    public function test_moderator_can_unban_user_from_moderation_page(): void
    {
        [$moderator, $bannedUser] = $this->createModeratorAndBannedUser();

        $this->actingAs($moderator)
            ->post(route('moderation.banned-users.unban', $bannedUser))
            ->assertRedirect();

        $this->assertFalse($bannedUser->fresh()->isBanned());
    }

    /**
     * @return array{0: User, 1: User}
     */
    private function createModeratorAndBannedUser(): array
    {
        $moderator = User::factory()->create(['email_verified_at' => now()]);
        $room = Room::factory()->create([
            'user_id' => $moderator->id,
            'category_id' => Category::factory()->create()->id,
            'is_public' => true,
            'is_active' => true,
            'is_featured' => false,
            'deleted_at' => null,
        ]);
        $room->moderators()->attach($moderator->id);

        $bannedUser = User::factory()->create([
            'name' => 'BannedModerationUser',
            'email' => 'banned-mod@example.com',
            'email_verified_at' => now(),
        ]);

        $bannedUser->ban([
            'comment' => 'Langage inapproprié',
        ]);

        return [$moderator, $bannedUser];
    }
}
