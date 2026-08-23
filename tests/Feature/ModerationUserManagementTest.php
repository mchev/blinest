<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Message;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ModerationUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_moderator_can_search_users_without_sensitive_fields(): void
    {
        [$moderator, $target] = $this->createModeratorAndTarget();

        $response = $this->actingAs($moderator)->get(route('moderation.users.index', [
            'search' => $target->name,
        ]));

        $response->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Moderation/UsersManagement')
                ->where('canViewSensitiveData', false)
                ->has('users.data', 1)
                ->where('users.data.0.id', $target->id)
                ->missing('users.data.0.email')
                ->missing('users.data.0.ip')
            );
    }

    public function test_admin_can_see_sensitive_fields_in_user_list(): void
    {
        $admin = User::factory()->create([
            'is_administrator' => true,
            'email_verified_at' => now(),
        ]);

        $target = User::factory()->create([
            'name' => 'SensitiveTarget',
            'email' => 'target@example.com',
            'ip' => '203.0.113.10',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('moderation.users.index', [
            'search' => 'SensitiveTarget',
        ]));

        $response->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('canViewSensitiveData', true)
                ->where('users.data.0.email', 'target@example.com')
                ->where('users.data.0.ip', '203.0.113.10')
            );
    }

    public function test_admin_user_sheet_includes_related_accounts_and_message_context(): void
    {
        [$admin, $room, $target, $related] = $this->createAdminReviewScenario();

        $response = $this->actingAs($admin)->get(route('moderation.users.show', $target));

        $response->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Moderation/UsersManagement')
                ->where('canViewSensitiveData', true)
                ->where('user.id', $target->id)
                ->has('user.conversation_rooms', 1)
                ->where('user.conversation_rooms.0.threads.0.body', 'Message cible')
                ->has('user.conversation_rooms.0.threads.0.context', 3)
                ->where('user.admin.email', $target->email)
                ->has('user.related_accounts.users', 1)
                ->where('user.related_accounts.users.0.id', $related->id)
                ->where('user.related_accounts.users.0.ip', '198.51.100.20')
            );
    }

    public function test_moderator_user_sheet_hides_admin_insights(): void
    {
        [, , $target, $related] = $this->createAdminReviewScenario();
        [$moderator] = $this->createModeratorAndTarget();

        $response = $this->actingAs($moderator)->get(route('moderation.users.show', $target));

        $response->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('canViewSensitiveData', false)
                ->missing('user.admin')
                ->missing('user.email')
                ->has('user.related_accounts.users', 1)
                ->where('user.related_accounts.users.0.id', $related->id)
                ->missing('user.related_accounts.users.0.ip')
            );
    }

    public function test_administrator_can_access_moderation_users(): void
    {
        $admin = User::factory()->create([
            'is_administrator' => true,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(route('moderation.users.index'))
            ->assertOk();
    }

    /**
     * @return array{0: User, 1: User}
     */
    private function createModeratorAndTarget(): array
    {
        $moderator = User::factory()->create(['email_verified_at' => now()]);
        $target = User::factory()->create([
            'name' => 'ModerationTarget',
            'email' => 'moderation-target@example.com',
            'ip' => '198.51.100.4',
            'email_verified_at' => now(),
        ]);

        $room = $this->createPublicRoom($moderator);
        $room->moderators()->attach($moderator->id);

        return [$moderator, $target];
    }

    /**
     * @return array{0: User, 1: Room, 2: User, 3: User}
     */
    private function createAdminReviewScenario(): array
    {
        $admin = User::factory()->create([
            'is_administrator' => true,
            'email_verified_at' => now(),
        ]);

        $moderator = User::factory()->create(['email_verified_at' => now()]);
        $room = $this->createPublicRoom($admin);
        $room->moderators()->attach($moderator->id);

        $target = User::factory()->create([
            'name' => 'ReviewTarget',
            'email' => 'review-target@example.com',
            'ip' => '198.51.100.20',
            'email_verified_at' => now(),
        ]);

        $related = User::factory()->create([
            'name' => 'RelatedAccount',
            'email' => 'related@example.com',
            'ip' => '198.51.100.20',
            'email_verified_at' => now(),
        ]);

        $otherUser = User::factory()->create(['email_verified_at' => now()]);

        Message::query()->create([
            'messagable_type' => Room::class,
            'messagable_id' => $room->id,
            'user_id' => $otherUser->id,
            'user_ip' => '198.51.100.99',
            'body' => 'Message avant 1',
            'created_at' => now()->subMinutes(3),
        ]);

        Message::query()->create([
            'messagable_type' => Room::class,
            'messagable_id' => $room->id,
            'user_id' => $otherUser->id,
            'user_ip' => '198.51.100.99',
            'body' => 'Message avant 2',
            'created_at' => now()->subMinutes(2),
        ]);

        Message::query()->create([
            'messagable_type' => Room::class,
            'messagable_id' => $room->id,
            'user_id' => $target->id,
            'user_ip' => '198.51.100.55',
            'body' => 'Message cible',
            'created_at' => now()->subMinute(),
        ]);

        return [$admin, $room, $target, $related];
    }

    private function createPublicRoom(User $owner): Room
    {
        $category = Category::factory()->create();

        return Room::factory()->create([
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_active' => true,
            'is_featured' => false,
            'deleted_at' => null,
        ]);
    }
}
