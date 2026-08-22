<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class MePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_me_page_renders_account_settings_without_heavy_profile_data(): void
    {
        $user = User::factory()->create(['is_guest' => false]);

        $this->actingAs($user)
            ->get(route('me'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Me/Show')
                ->has('account', fn (Assert $account) => $account
                    ->where('id', $user->id)
                    ->where('email', $user->email)
                    ->has('stats')
                    ->has('links.profile')
                    ->has('links.profile_scores')
                    ->missing('scores')
                    ->etc()
                )
                ->missing('user')
            );
    }
}
