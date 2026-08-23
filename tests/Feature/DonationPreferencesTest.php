<?php

namespace Tests\Feature;

use App\Models\Donation;
use App\Models\User;
use App\Services\Donations\DonationGoalService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class DonationPreferencesTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_hide_donation_history_on_public_profile(): void
    {
        $user = User::factory()->create([
            'is_guest' => false,
            'show_donation_history_on_profile' => true,
        ]);

        Donation::query()->create([
            'stripe_checkout_session_id' => 'cs_pref_hide',
            'amount_cents' => 500,
            'currency' => 'eur',
            'month_key' => app(DonationGoalService::class)->monthKey(),
            'user_id' => $user->id,
            'donated_at' => now('Europe/Paris'),
        ]);

        $this->actingAs($user)
            ->from(route('me'))
            ->patch(route('users.donation-preferences.update', $user), [
                'show_donation_history_on_profile' => false,
            ])
            ->assertRedirect(route('me'))
            ->assertSessionHas('success');

        $this->assertFalse($user->fresh()->show_donation_history_on_profile);
    }

    public function test_me_page_exposes_donation_preference_for_donors(): void
    {
        $user = User::factory()->create([
            'is_guest' => false,
            'show_donation_history_on_profile' => false,
        ]);

        Donation::query()->create([
            'stripe_checkout_session_id' => 'cs_pref_me',
            'amount_cents' => 500,
            'currency' => 'eur',
            'month_key' => app(DonationGoalService::class)->monthKey(),
            'user_id' => $user->id,
            'donated_at' => now('Europe/Paris'),
        ]);

        $this->actingAs($user)
            ->get(route('me'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('account.show_donation_history_on_profile', false)
                ->where('account.donation_summary.donation_count', 1)
            );
    }

    public function test_user_cannot_update_another_users_donation_preferences(): void
    {
        $user = User::factory()->create(['is_guest' => false]);
        $other = User::factory()->create(['is_guest' => false]);

        $this->actingAs($user)
            ->patch(route('users.donation-preferences.update', $other), [
                'show_donation_history_on_profile' => false,
            ])
            ->assertForbidden();
    }
}
