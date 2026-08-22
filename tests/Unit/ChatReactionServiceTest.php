<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\Chat\ChatReactionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatReactionServiceTest extends TestCase
{
    use RefreshDatabase;

    private ChatReactionService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(ChatReactionService::class);
    }

    public function test_standard_emojis_are_allowed_for_authenticated_users(): void
    {
        $user = User::factory()->create(['is_guest' => false]);

        $this->assertTrue($this->service->canUserReactWith($user, '👍'));
    }

    public function test_supporter_emojis_require_supporter_perk(): void
    {
        $user = User::factory()->create(['is_guest' => false]);

        $this->assertFalse($this->service->canUserReactWith($user, '☕'));
    }

    public function test_unknown_emojis_are_rejected(): void
    {
        $user = User::factory()->create(['is_guest' => false]);

        $this->assertFalse($this->service->isAllowedEmoji('🦄'));
        $this->assertFalse($this->service->canUserReactWith($user, '🦄'));
    }
}
