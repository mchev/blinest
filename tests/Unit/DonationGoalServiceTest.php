<?php

namespace Tests\Unit;

use App\Models\Donation;
use App\Models\User;
use App\Services\Donations\DonationGoalService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class DonationGoalServiceTest extends TestCase
{
    use RefreshDatabase;

    private DonationGoalService $service;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('donations.monthly_goal_cents', 10_000);
        Config::set('donations.timezone', 'Europe/Paris');
        Config::set('donations.cache_ttl_seconds', 300);

        $this->service = app(DonationGoalService::class);
    }

    public function test_current_progress_reflects_raised_amount(): void
    {
        Donation::query()->create([
            'stripe_checkout_session_id' => 'cs_test_1',
            'amount_cents' => 2_500,
            'currency' => 'eur',
            'month_key' => $this->service->monthKey(),
            'donated_at' => now('Europe/Paris'),
        ]);

        Cache::flush();

        $progress = $this->service->currentProgress();

        $this->assertSame(2_500, $progress['raised_cents']);
        $this->assertSame(10_000, $progress['goal_cents']);
        $this->assertSame(25, $progress['percent']);
        $this->assertFalse($progress['goal_reached']);
        $this->assertFalse($progress['ads_disabled']);
    }

    public function test_goal_reached_disables_ads(): void
    {
        Donation::query()->create([
            'stripe_checkout_session_id' => 'cs_test_2',
            'amount_cents' => 10_000,
            'currency' => 'eur',
            'month_key' => $this->service->monthKey(),
            'donated_at' => now('Europe/Paris'),
        ]);

        Cache::flush();

        $this->assertTrue($this->service->isGoalReached());
        $this->assertTrue($this->service->shouldDisableAds());
        $this->assertTrue($this->service->currentProgress()['ads_disabled']);
    }

    public function test_record_checkout_session_is_idempotent(): void
    {
        Notification::fake();

        $user = User::factory()->create(['is_guest' => false]);

        $session = [
            'id' => 'cs_test_duplicate',
            'payment_status' => 'paid',
            'amount_total' => 500,
            'currency' => 'eur',
            'created' => now()->timestamp,
            'client_reference_id' => (string) $user->id,
            'customer_details' => ['email' => $user->email],
        ];

        $first = $this->service->recordCheckoutSession($session);
        $second = $this->service->recordCheckoutSession($session);

        $this->assertNotNull($first);
        $this->assertSame($first?->id, $second?->id);
        $this->assertSame(1, Donation::query()->count());
        $this->assertTrue($this->service->userIsSupporter($user));
    }

    public function test_monthly_history_includes_empty_months(): void
    {
        $history = $this->service->monthlyHistory(3);

        $this->assertCount(3, $history);
        $this->assertArrayHasKey('month_key', $history[0]);
        $this->assertArrayHasKey('goal_reached', $history[0]);
        $this->assertArrayHasKey('carryover_cents', $history[0]);
    }

    public function test_payment_url_for_guest_includes_locale_only(): void
    {
        Config::set('donations.stripe_payment_url', 'https://donate.stripe.com/test');

        $url = $this->service->paymentUrlForUser(null, 'fr');

        $this->assertSame('https://donate.stripe.com/test?locale=fr', $url);
    }

    public function test_payment_url_for_member_includes_checkout_prefill_params(): void
    {
        Config::set('donations.stripe_payment_url', 'https://donate.stripe.com/test');

        $user = User::factory()->create([
            'is_guest' => false,
            'email' => 'donor@example.com',
        ]);

        $url = $this->service->paymentUrlForUser($user, 'fr');

        $this->assertStringContainsString('client_reference_id='.$user->id, $url);
        $this->assertStringContainsString('locked_prefilled_email=donor%40example.com', $url);
        $this->assertStringContainsString('locale=fr', $url);
    }

    public function test_current_progress_for_user_overrides_payment_url(): void
    {
        Config::set('donations.stripe_payment_url', 'https://donate.stripe.com/test');

        $user = User::factory()->create([
            'is_guest' => false,
            'email' => 'donor@example.com',
        ]);

        $progress = $this->service->currentProgressForUser($user, 'en');

        $this->assertStringContainsString('client_reference_id='.$user->id, $progress['payment_url']);
        $this->assertStringContainsString('locale=en', $progress['payment_url']);
    }

    public function test_surplus_carries_over_to_next_month(): void
    {
        $timezone = 'Europe/Paris';
        $lastMonth = now($timezone)->subMonth()->format('Y-m');
        $currentMonth = $this->service->monthKey();

        Donation::query()->create([
            'stripe_checkout_session_id' => 'cs_carryover_last',
            'amount_cents' => 15_000,
            'currency' => 'eur',
            'month_key' => $lastMonth,
            'donated_at' => now($timezone)->subMonth(),
        ]);

        Donation::query()->create([
            'stripe_checkout_session_id' => 'cs_carryover_current',
            'amount_cents' => 5_000,
            'currency' => 'eur',
            'month_key' => $currentMonth,
            'donated_at' => now($timezone),
        ]);

        Cache::flush();

        $progress = $this->service->currentProgress();

        $this->assertSame(5_000, $progress['carryover_cents']);
        $this->assertSame(5_000, $progress['raised_cents']);
        $this->assertSame(10_000, $progress['effective_cents']);
        $this->assertTrue($progress['goal_reached']);
        $this->assertTrue($progress['ads_disabled']);
    }

    public function test_user_donation_history_returns_linked_donations(): void
    {
        $user = User::factory()->create(['is_guest' => false]);

        Donation::query()->create([
            'stripe_checkout_session_id' => 'cs_user_history',
            'amount_cents' => 1_000,
            'currency' => 'eur',
            'month_key' => $this->service->monthKey(),
            'user_id' => $user->id,
            'donated_at' => now('Europe/Paris'),
        ]);

        $history = $this->service->userDonationHistory($user);

        $this->assertCount(1, $history);
        $this->assertSame(1_000, $history[0]['amount_cents']);
        $this->assertSame(1_000, $this->service->userDonationSummary($user)['total_cents']);
    }

    public function test_recent_supporters_returns_unique_identified_donors(): void
    {
        $first = User::factory()->create(['is_guest' => false]);
        $second = User::factory()->create(['is_guest' => false]);

        Donation::query()->create([
            'stripe_checkout_session_id' => 'cs_recent_1',
            'amount_cents' => 500,
            'currency' => 'eur',
            'month_key' => $this->service->monthKey(),
            'user_id' => $first->id,
            'donated_at' => now('Europe/Paris')->subHour(),
        ]);

        Donation::query()->create([
            'stripe_checkout_session_id' => 'cs_recent_2',
            'amount_cents' => 500,
            'currency' => 'eur',
            'month_key' => $this->service->monthKey(),
            'user_id' => $first->id,
            'donated_at' => now('Europe/Paris'),
        ]);

        Donation::query()->create([
            'stripe_checkout_session_id' => 'cs_recent_3',
            'amount_cents' => 500,
            'currency' => 'eur',
            'month_key' => $this->service->monthKey(),
            'user_id' => $second->id,
            'donated_at' => now('Europe/Paris')->subMinutes(30),
        ]);

        Donation::query()->create([
            'stripe_checkout_session_id' => 'cs_recent_anon',
            'amount_cents' => 500,
            'currency' => 'eur',
            'month_key' => $this->service->monthKey(),
            'donated_at' => now('Europe/Paris')->subMinutes(10),
        ]);

        $supporters = $this->service->recentSupporters(3);

        $this->assertCount(2, $supporters);
        $this->assertSame($first->id, $supporters[0]['id']);
        $this->assertSame($second->id, $supporters[1]['id']);
    }
}
