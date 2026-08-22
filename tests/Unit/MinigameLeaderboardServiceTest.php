<?php

namespace Tests\Unit;

use App\Models\MinigameScore;
use App\Models\User;
use App\Services\Rankings\MinigameLeaderboardService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MinigameLeaderboardServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_context_returns_position_and_score(): void
    {
        $first = User::factory()->create(['is_guest' => false]);
        $second = User::factory()->create(['is_guest' => false]);

        MinigameScore::query()->create([
            'user_id' => $first->id,
            'game_type' => MinigameScore::TYPE_QUIZ,
            'score' => 500,
        ]);

        MinigameScore::query()->create([
            'user_id' => $second->id,
            'game_type' => MinigameScore::TYPE_QUIZ,
            'score' => 100,
        ]);

        $context = app(MinigameLeaderboardService::class)->userContext($second);

        $this->assertSame(2, $context['position']);
        $this->assertSame(100, $context['score']);
    }

    public function test_paginated_payload_returns_ranked_users(): void
    {
        $user = User::factory()->create(['is_guest' => false, 'name' => 'Minigame Hero']);

        MinigameScore::query()->create([
            'user_id' => $user->id,
            'game_type' => MinigameScore::TYPE_QUIZ,
            'score' => 250,
        ]);

        $payload = app(MinigameLeaderboardService::class)->paginatedPayload();

        $this->assertSame(1, $payload['total']);
        $this->assertSame('Minigame Hero', $payload['data'][0]['user']['name']);
        $this->assertSame(250, $payload['data'][0]['total_score']);
    }
}
