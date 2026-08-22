<?php

namespace Tests\Unit;

use App\Enums\DonorPerk;
use App\Models\Donation;
use App\Models\User;
use App\Services\Donations\DonationGoalService;
use App\Services\Donations\DonorPerkService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class DonorPerkServiceTest extends TestCase
{
    use RefreshDatabase;

    private DonorPerkService $service;

    private DonationGoalService $donationGoal;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('donations.monthly_goal_cents', 10_000);
        Config::set('donations.timezone', 'Europe/Paris');
        Config::set('donations.supporter_perks', ['ad_free', 'avatar_crown', 'supporter_reactions']);

        $this->service = app(DonorPerkService::class);
        $this->donationGoal = app(DonationGoalService::class);
    }

    public function test_guest_and_non_donor_have_no_perks(): void
    {
        $guest = User::factory()->create(['is_guest' => true]);
        $member = User::factory()->create(['is_guest' => false]);

        $this->assertSame([], $this->service->activePerksForUser($guest));
        $this->assertSame([], $this->service->activePerksForUser($member));
        $this->assertFalse($this->service->shouldDisableAdsForUser($member));
    }

    public function test_donor_receives_configured_perks_for_current_month(): void
    {
        $user = User::factory()->create(['is_guest' => false]);

        Donation::query()->create([
            'stripe_checkout_session_id' => 'cs_perk_test',
            'amount_cents' => 500,
            'currency' => 'eur',
            'month_key' => $this->donationGoal->monthKey(),
            'user_id' => $user->id,
            'donated_at' => now('Europe/Paris'),
        ]);

        $perks = $this->service->activePerksForUser($user);

        $this->assertSame(['ad_free', 'avatar_crown', 'supporter_reactions'], $perks);
        $this->assertTrue($this->service->userHasPerk($user, DonorPerk::AdFree));
        $this->assertTrue($this->service->userHasPerk($user, DonorPerk::AvatarCrown));
        $this->assertTrue($this->service->shouldDisableAdsForUser($user));
    }

    public function test_past_month_donation_does_not_grant_current_perks(): void
    {
        $user = User::factory()->create(['is_guest' => false]);

        Donation::query()->create([
            'stripe_checkout_session_id' => 'cs_old_perk',
            'amount_cents' => 500,
            'currency' => 'eur',
            'month_key' => '2020-01',
            'user_id' => $user->id,
            'donated_at' => now('Europe/Paris'),
        ]);

        $this->assertSame([], $this->service->activePerksForUser($user));
    }

    public function test_enrich_user_payload_exposes_perks_and_supporter_flag(): void
    {
        $user = User::factory()->create(['is_guest' => false]);

        Donation::query()->create([
            'stripe_checkout_session_id' => 'cs_enrich',
            'amount_cents' => 500,
            'currency' => 'eur',
            'month_key' => $this->donationGoal->monthKey(),
            'user_id' => $user->id,
            'donated_at' => now('Europe/Paris'),
        ]);

        $payload = $this->service->enrichUserPayload(['id' => $user->id, 'name' => $user->name], $user);

        $this->assertTrue($payload['is_supporter']);
        $this->assertSame(['ad_free', 'avatar_crown', 'supporter_reactions'], $payload['donor_perks']);
    }

    public function test_perk_map_batch_loads_supporters(): void
    {
        $donor = User::factory()->create(['is_guest' => false]);
        $other = User::factory()->create(['is_guest' => false]);

        Donation::query()->create([
            'stripe_checkout_session_id' => 'cs_map',
            'amount_cents' => 500,
            'currency' => 'eur',
            'month_key' => $this->donationGoal->monthKey(),
            'user_id' => $donor->id,
            'donated_at' => now('Europe/Paris'),
        ]);

        $map = $this->service->perkMapForUserIds([$donor->id, $other->id]);

        $this->assertSame(['ad_free', 'avatar_crown', 'supporter_reactions'], $map[$donor->id]);
        $this->assertArrayNotHasKey($other->id, $map);
    }

    public function test_unknown_perk_keys_are_ignored_in_config(): void
    {
        Config::set('donations.supporter_perks', ['ad_free', 'avatar_crown', 'solo_elo', 'unknown_perk']);

        $this->assertSame(['ad_free', 'avatar_crown', 'solo_elo'], $this->service->configuredPerks());
    }
}
