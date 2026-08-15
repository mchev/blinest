<?php

namespace Tests\Feature;

use App\Jobs\UpdateUserLevel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class UpdateUserLevelUniqueTest extends TestCase
{
    use RefreshDatabase;

    public function test_duplicate_level_updates_for_same_user_are_deduplicated(): void
    {
        Queue::fake();

        $user = User::factory()->create();

        UpdateUserLevel::dispatch($user, 'score');
        UpdateUserLevel::dispatch($user, 'score');
        UpdateUserLevel::dispatch($user, 'login');

        Queue::assertPushed(UpdateUserLevel::class, 1);
    }
}
