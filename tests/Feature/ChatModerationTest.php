<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Message;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ChatModerationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_authenticated_user_can_send_chat_message(): void
    {
        [$user, $room] = $this->createUserAndRoom();

        $this->actingAs($user)
            ->post($this->messageStoreUrl($room), [
                'body' => 'Hello everyone',
            ])
            ->assertNoContent();

        $this->assertDatabaseHas('messages', [
            'user_id' => $user->id,
            'body' => 'Hello everyone',
        ]);
    }

    public function test_duplicate_message_in_same_room_is_rejected(): void
    {
        [$user, $room] = $this->createUserAndRoom();

        $this->actingAs($user)
            ->post($this->messageStoreUrl($room), [
                'body' => 'Promo message',
            ])
            ->assertNoContent();

        $this->actingAs($user)
            ->post($this->messageStoreUrl($room), [
                'body' => 'Promo message',
            ])
            ->assertInvalid(['body']);

        $this->assertDatabaseCount('messages', 1);
    }

    public function test_room_flood_limit_rejects_excessive_messages(): void
    {
        config(['chat.moderation.room_flood_per_minute' => 2]);

        [$user, $room] = $this->createUserAndRoom();

        $this->actingAs($user)
            ->post($this->messageStoreUrl($room), ['body' => 'First'])
            ->assertNoContent();

        $this->actingAs($user)
            ->post($this->messageStoreUrl($room), ['body' => 'Second'])
            ->assertNoContent();

        $this->actingAs($user)
            ->post($this->messageStoreUrl($room), ['body' => 'Third'])
            ->assertInvalid(['body']);

        $this->assertDatabaseCount('messages', 2);
    }

    public function test_messages_with_links_are_rejected(): void
    {
        [$user, $room] = $this->createUserAndRoom();

        $this->actingAs($user)
            ->post($this->messageStoreUrl($room), [
                'body' => 'Visit https://spam.example now',
            ])
            ->assertInvalid(['body']);

        $this->assertDatabaseCount('messages', 0);
    }

    public function test_cross_room_identical_message_auto_bans_user(): void
    {
        config([
            'chat.moderation.cross_room_min_rooms' => 3,
            'chat.moderation.min_cross_room_body_length' => 5,
        ]);

        $user = User::factory()->create([
            'email_verified_at' => now(),
            'ip' => '203.0.113.10',
        ]);

        $rooms = collect(range(1, 3))->map(function (int $index) use ($user) {
            return Room::factory()->create([
                'user_id' => $user->id,
                'category_id' => Category::factory()->create()->id,
                'slug' => 'chat-mod-room-'.$index.'-'.$user->id,
                'is_public' => true,
                'is_active' => true,
                'is_featured' => false,
                'deleted_at' => null,
            ]);
        });

        $spamMessage = 'Join my promo channel now';

        $this->actingAs($user)
            ->post($this->messageStoreUrl($rooms[0]), ['body' => $spamMessage])
            ->assertNoContent();

        $this->actingAs($user)
            ->post($this->messageStoreUrl($rooms[1]), ['body' => $spamMessage])
            ->assertNoContent();

        $this->actingAs($user)
            ->post($this->messageStoreUrl($rooms[2]), ['body' => $spamMessage])
            ->assertInvalid(['body']);

        $this->assertTrue($user->fresh()->isBanned());
        $this->assertDatabaseCount('messages', 2);
        $this->assertSame(2, Message::onlyTrashed()->where('user_id', $user->id)->count());
    }

    public function test_moderator_is_not_auto_banned_for_cross_room_messages(): void
    {
        config([
            'chat.moderation.cross_room_min_rooms' => 2,
            'chat.moderation.min_cross_room_body_length' => 5,
        ]);

        $moderator = User::factory()->create(['email_verified_at' => now()]);
        $rooms = collect(range(1, 2))->map(function (int $index) use ($moderator) {
            $room = Room::factory()->create([
                'user_id' => $moderator->id,
                'category_id' => Category::factory()->create()->id,
                'slug' => 'chat-mod-staff-room-'.$index.'-'.$moderator->id,
                'is_public' => true,
                'is_active' => true,
                'is_featured' => false,
                'deleted_at' => null,
            ]);

            $room->moderators()->attach($moderator->id);

            return $room;
        });

        $message = 'Staff announcement for everyone';

        $this->actingAs($moderator)
            ->post($this->messageStoreUrl($rooms[0]), ['body' => $message])
            ->assertNoContent();

        $this->actingAs($moderator)
            ->post($this->messageStoreUrl($rooms[1]), ['body' => $message])
            ->assertNoContent();

        $this->assertFalse($moderator->fresh()->isBanned());
        $this->assertDatabaseCount('messages', 2);
    }

    /**
     * @return array{0: User, 1: Room}
     */
    private function createUserAndRoom(): array
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $room = Room::factory()->create([
            'user_id' => $user->id,
            'category_id' => Category::factory()->create()->id,
            'slug' => 'chat-mod-room-'.$user->id,
            'is_public' => true,
            'is_active' => true,
            'is_featured' => false,
            'deleted_at' => null,
        ]);

        return [$user, $room];
    }

    private function messageStoreUrl(Room $room): string
    {
        return "/rooms/{$room->id}/message";
    }
}
