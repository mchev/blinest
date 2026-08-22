<?php

namespace Tests\Feature;

use App\Models\Donation;
use App\Models\User;
use App\Services\Donations\DonationGoalService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class DonationGoalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('donations.monthly_goal_cents', 10_000);
        Config::set('donations.timezone', 'Europe/Paris');
    }

    public function test_home_page_shares_donation_goal_props(): void
    {
        Donation::query()->create([
            'stripe_checkout_session_id' => 'cs_home_test',
            'amount_cents' => 4_000,
            'currency' => 'eur',
            'month_key' => app(DonationGoalService::class)->monthKey(),
            'donated_at' => now('Europe/Paris'),
        ]);

        Cache::flush();

        $user = User::factory()->create(['is_guest' => false]);

        $this->actingAs($user)
            ->get(route('home'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->has('donation_goal')
                ->where('donation_goal.raised_cents', 4_000)
                ->where('donation_goal.goal_cents', 10_000)
                ->has('donation_goal.monthly_supporters')
                ->where('auth.user.is_supporter', false));
    }

    public function test_donor_receives_perks_in_shared_auth_payload(): void
    {
        $user = User::factory()->create(['is_guest' => false]);

        Donation::query()->create([
            'stripe_checkout_session_id' => 'cs_donor_perks',
            'amount_cents' => 500,
            'currency' => 'eur',
            'month_key' => app(DonationGoalService::class)->monthKey(),
            'user_id' => $user->id,
            'donated_at' => now('Europe/Paris'),
        ]);

        Cache::flush();

        $this->actingAs($user)
            ->get(route('home'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('auth.user.is_supporter', true)
                ->where('auth.user.donor_perks', ['ad_free', 'avatar_crown', 'supporter_reactions']));
    }

    public function test_individual_donor_skips_ezoic_even_when_goal_not_reached(): void
    {
        $this->app->detectEnvironment(fn (): string => 'production');

        $user = User::factory()->create(['is_guest' => false]);

        Donation::query()->create([
            'stripe_checkout_session_id' => 'cs_donor_ads',
            'amount_cents' => 500,
            'currency' => 'eur',
            'month_key' => app(DonationGoalService::class)->monthKey(),
            'user_id' => $user->id,
            'donated_at' => now('Europe/Paris'),
        ]);

        Cache::flush();

        $response = $this->actingAs($user)->get(route('home'));

        $response->assertOk();
        $this->assertStringNotContainsString('ezojs.com/ezoic/sa.min.js', $response->getContent());
    }

    public function test_support_page_is_accessible_and_lists_history(): void
    {
        $this->get(route('docs.support'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('docs/support/Index')
                ->has('history', 12)
                ->has('recent_donations'));
    }

    public function test_blade_skips_ezoic_scripts_when_goal_reached(): void
    {
        $this->app->detectEnvironment(fn (): string => 'production');

        Donation::query()->create([
            'stripe_checkout_session_id' => 'cs_ads_test',
            'amount_cents' => 10_000,
            'currency' => 'eur',
            'month_key' => app(DonationGoalService::class)->monthKey(),
            'donated_at' => now('Europe/Paris'),
        ]);

        Cache::flush();

        $user = User::factory()->create(['is_guest' => false]);

        $response = $this->actingAs($user)->get(route('home'));

        $response->assertOk();
        $this->assertStringNotContainsString('ezojs.com/ezoic/sa.min.js', $response->getContent());
    }
}
