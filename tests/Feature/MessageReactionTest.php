<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Donation;
use App\Models\Message;
use App\Models\Room;
use App\Models\User;
use App\Services\Donations\DonationGoalService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageReactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_react_with_standard_emoji(): void
    {
        $user = User::factory()->create(['is_guest' => false]);
        $message = $this->createMessage();

        $this->actingAs($user)
            ->postJson("/api/messages/{$message->id}/reactions", ['emoji' => '👍'])
            ->assertOk()
            ->assertJson(['added' => true]);

        $this->assertDatabaseHas('message_reactions', [
            'message_id' => $message->id,
            'user_id' => $user->id,
            'emoji' => '👍',
        ]);
    }

    public function test_non_supporter_cannot_use_supporter_emoji(): void
    {
        $user = User::factory()->create(['is_guest' => false]);
        $message = $this->createMessage();

        $this->actingAs($user)
            ->postJson("/api/messages/{$message->id}/reactions", ['emoji' => '☕'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['emoji']);
    }

    public function test_monthly_supporter_can_use_supporter_emoji(): void
    {
        $user = User::factory()->create(['is_guest' => false]);
        $message = $this->createMessage();

        Donation::query()->create([
            'stripe_checkout_session_id' => 'cs_reaction_test',
            'amount_cents' => 100,
            'currency' => 'eur',
            'month_key' => app(DonationGoalService::class)->monthKey(),
            'user_id' => $user->id,
            'donated_at' => now('Europe/Paris'),
        ]);

        $this->actingAs($user)
            ->postJson("/api/messages/{$message->id}/reactions", ['emoji' => '☕'])
            ->assertOk()
            ->assertJson(['added' => true]);
    }

    private function createMessage(): Message
    {
        $owner = User::factory()->create(['is_guest' => false]);
        $category = Category::query()->create(['name' => 'Test']);

        $room = Room::query()->create([
            'name' => 'Reaction Room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        return Message::query()->create([
            'messagable_type' => Room::class,
            'messagable_id' => $room->id,
            'user_id' => $owner->id,
            'user_ip' => '127.0.0.1',
            'body' => 'Test message',
        ]);
    }
}
