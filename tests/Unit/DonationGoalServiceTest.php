<?php

namespace Tests\Unit;

use App\Models\Donation;
use App\Models\User;
use App\Services\Donations\DonationGoalService;
use Carbon\Carbon;
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

    public function test_user_donation_summary_distinguishes_months_supported_from_supporter_duration(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-08-22 12:00:00', 'Europe/Paris'));

        $user = User::factory()->create(['is_guest' => false]);

        foreach ([
            ['cs_2023', '2023-03', '2023-03-05 10:00:00'],
            ['cs_2024', '2024-03', '2024-03-03 10:00:00'],
            ['cs_2025', '2025-03', '2025-03-08 10:00:00'],
            ['cs_2026', '2026-08', '2026-08-22 10:00:00'],
        ] as [$sessionId, $monthKey, $donatedAt]) {
            Donation::query()->create([
                'stripe_checkout_session_id' => $sessionId,
                'amount_cents' => 5_000,
                'currency' => 'eur',
                'month_key' => $monthKey,
                'user_id' => $user->id,
                'donated_at' => Carbon::parse($donatedAt, 'Europe/Paris'),
            ]);
        }

        $summary = $this->service->userDonationSummary($user);

        $this->assertSame(4, $summary['months_supported']);
        $this->assertSame(41, $summary['supporter_duration_months']);
        $this->assertSame('2023-03', $summary['first_supported_month_key']);

        Carbon::setTestNow();
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

    public function test_monthly_supporters_only_includes_current_month(): void
    {
        $current = User::factory()->create(['is_guest' => false]);
        $past = User::factory()->create(['is_guest' => false]);

        Donation::query()->create([
            'stripe_checkout_session_id' => 'cs_month_current',
            'amount_cents' => 500,
            'currency' => 'eur',
            'month_key' => $this->service->monthKey(),
            'user_id' => $current->id,
            'donated_at' => now('Europe/Paris'),
        ]);

        Donation::query()->create([
            'stripe_checkout_session_id' => 'cs_month_past',
            'amount_cents' => 500,
            'currency' => 'eur',
            'month_key' => '2020-01',
            'user_id' => $past->id,
            'donated_at' => now('Europe/Paris'),
        ]);

        $supporters = $this->service->monthlySupporters();

        $this->assertCount(1, $supporters);
        $this->assertSame($current->id, $supporters[0]['id']);
        $this->assertTrue($supporters[0]['is_supporter']);
        $this->assertSame(['ad_free', 'avatar_crown', 'supporter_reactions'], $supporters[0]['donor_perks']);
    }

    public function test_post_goal_supporters_includes_donors_after_carryover_goal_is_met(): void
    {
        $timezone = 'Europe/Paris';
        $lastMonth = now($timezone)->subMonth()->format('Y-m');
        $currentMonth = $this->service->monthKey();

        $earlyDonor = User::factory()->create(['is_guest' => false]);
        $postGoalDonor = User::factory()->create(['is_guest' => false]);

        Donation::query()->create([
            'stripe_checkout_session_id' => 'cs_post_goal_last',
            'amount_cents' => 20_000,
            'currency' => 'eur',
            'month_key' => $lastMonth,
            'donated_at' => now($timezone)->subMonth(),
        ]);

        Donation::query()->create([
            'stripe_checkout_session_id' => 'cs_post_goal_early',
            'amount_cents' => 2_000,
            'currency' => 'eur',
            'month_key' => $currentMonth,
            'user_id' => $earlyDonor->id,
            'donated_at' => now($timezone)->subHours(2),
        ]);

        Donation::query()->create([
            'stripe_checkout_session_id' => 'cs_post_goal_late',
            'amount_cents' => 1_000,
            'currency' => 'eur',
            'month_key' => $currentMonth,
            'user_id' => $postGoalDonor->id,
            'donated_at' => now($timezone)->subHour(),
        ]);

        Cache::flush();

        $supporters = $this->service->postGoalSupporters();

        $this->assertCount(2, $supporters);
        $this->assertSame($postGoalDonor->id, $supporters[0]['id']);
        $this->assertSame($earlyDonor->id, $supporters[1]['id']);
    }

    public function test_post_goal_supporters_excludes_donors_before_goal_is_reached_without_carryover(): void
    {
        $timezone = 'Europe/Paris';
        $currentMonth = $this->service->monthKey();

        $beforeGoal = User::factory()->create(['is_guest' => false]);
        $afterGoal = User::factory()->create(['is_guest' => false]);

        Donation::query()->create([
            'stripe_checkout_session_id' => 'cs_before_goal',
            'amount_cents' => 4_000,
            'currency' => 'eur',
            'month_key' => $currentMonth,
            'user_id' => $beforeGoal->id,
            'donated_at' => now($timezone)->subHours(2),
        ]);

        Donation::query()->create([
            'stripe_checkout_session_id' => 'cs_after_goal',
            'amount_cents' => 7_000,
            'currency' => 'eur',
            'month_key' => $currentMonth,
            'user_id' => $afterGoal->id,
            'donated_at' => now($timezone)->subHour(),
        ]);

        $supporters = $this->service->postGoalSupporters();

        $this->assertCount(1, $supporters);
        $this->assertSame($afterGoal->id, $supporters[0]['id']);
    }

    public function test_current_progress_includes_progress_segments_and_post_goal_supporters(): void
    {
        $timezone = 'Europe/Paris';
        $lastMonth = now($timezone)->subMonth()->format('Y-m');
        $currentMonth = $this->service->monthKey();

        Donation::query()->create([
            'stripe_checkout_session_id' => 'cs_segments_last',
            'amount_cents' => 12_000,
            'currency' => 'eur',
            'month_key' => $lastMonth,
            'donated_at' => now($timezone)->subMonth(),
        ]);

        Donation::query()->create([
            'stripe_checkout_session_id' => 'cs_segments_current',
            'amount_cents' => 3_000,
            'currency' => 'eur',
            'month_key' => $currentMonth,
            'donated_at' => now($timezone),
        ]);

        Cache::flush();

        $progress = $this->service->currentProgressForUser();

        $this->assertSame(2_000, $progress['carryover_cents']);
        $this->assertSame(3_000, $progress['raised_cents']);
        $this->assertSame(20, $progress['progress_segments']['carryover_percent']);
        $this->assertSame(30, $progress['progress_segments']['raised_percent']);
        $this->assertArrayHasKey('post_goal_supporters', $progress);
    }
}
