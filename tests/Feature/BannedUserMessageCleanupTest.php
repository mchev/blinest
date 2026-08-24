<?php

namespace Tests\Feature;

use App\Events\MessageDeleted;
use App\Models\Category;
use App\Models\Message;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class BannedUserMessageCleanupTest extends TestCase
{
    use RefreshDatabase;

    public function test_banning_user_soft_deletes_all_messages_and_broadcasts_deletion_events(): void
    {
        Event::fake([MessageDeleted::class]);

        $user = User::factory()->create(['email_verified_at' => now()]);
        $rooms = collect(range(1, 2))->map(function (int $index) use ($user) {
            return Room::factory()->create([
                'user_id' => $user->id,
                'category_id' => Category::factory()->create()->id,
                'slug' => 'ban-cleanup-room-'.$index.'-'.$user->id,
                'is_public' => true,
                'is_active' => true,
                'is_featured' => false,
                'deleted_at' => null,
            ]);
        });

        foreach ($rooms as $room) {
            $this->actingAs($user)
                ->post("/rooms/{$room->id}/message", ['body' => 'Spam message '.$room->id])
                ->assertNoContent();
        }

        $this->assertDatabaseCount('messages', 2);

        $user->ban([
            'comment' => 'Spam',
            'ip' => '203.0.113.10',
        ]);

        $this->assertDatabaseCount('messages', 2);
        $this->assertSame(2, Message::onlyTrashed()->where('user_id', $user->id)->count());
        Event::assertDispatchedTimes(MessageDeleted::class, 2);
    }

    public function test_cross_room_auto_ban_soft_deletes_existing_messages(): void
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
                'slug' => 'ban-cleanup-cross-room-'.$index.'-'.$user->id,
                'is_public' => true,
                'is_active' => true,
                'is_featured' => false,
                'deleted_at' => null,
            ]);
        });

        $spamMessage = 'Join my promo channel now';

        $this->actingAs($user)
            ->post("/rooms/{$rooms[0]->id}/message", ['body' => $spamMessage])
            ->assertNoContent();

        $this->actingAs($user)
            ->post("/rooms/{$rooms[1]->id}/message", ['body' => $spamMessage])
            ->assertNoContent();

        $this->actingAs($user)
            ->post("/rooms/{$rooms[2]->id}/message", ['body' => $spamMessage])
            ->assertInvalid(['body']);

        $this->assertTrue($user->fresh()->isBanned());
        $this->assertDatabaseCount('messages', 2);
        $this->assertSame(2, Message::onlyTrashed()->where('user_id', $user->id)->count());
    }
}
