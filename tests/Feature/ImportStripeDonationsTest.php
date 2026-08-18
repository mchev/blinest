<?php

namespace Tests\Feature;

use App\Models\Donation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ImportStripeDonationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_command_requires_stripe_secret(): void
    {
        Config::set('donations.stripe_secret_key', null);

        $this->artisan('donations:import-stripe')
            ->assertFailed();
    }

    public function test_import_command_imports_paid_sessions(): void
    {
        Config::set('donations.stripe_secret_key', 'sk_test_import');
        Config::set('donations.timezone', 'Europe/Paris');

        Http::fake([
            'api.stripe.com/v1/checkout/sessions*' => Http::response([
                'object' => 'list',
                'has_more' => false,
                'data' => [
                    [
                        'id' => 'cs_import_1',
                        'payment_status' => 'paid',
                        'amount_total' => 2000,
                        'currency' => 'eur',
                        'created' => now()->timestamp,
                        'customer_details' => ['email' => 'donor@example.com'],
                    ],
                ],
            ]),
        ]);

        $this->artisan('donations:import-stripe')
            ->assertSuccessful();

        $this->assertDatabaseHas('donations', [
            'stripe_checkout_session_id' => 'cs_import_1',
            'amount_cents' => 2000,
        ]);
    }

    public function test_dry_run_does_not_write_to_database(): void
    {
        Config::set('donations.stripe_secret_key', 'sk_test_import');

        Http::fake([
            'api.stripe.com/v1/checkout/sessions*' => Http::response([
                'object' => 'list',
                'has_more' => false,
                'data' => [
                    [
                        'id' => 'cs_dry_run',
                        'payment_status' => 'paid',
                        'amount_total' => 500,
                        'currency' => 'eur',
                        'created' => now()->timestamp,
                    ],
                ],
            ]),
        ]);

        $this->artisan('donations:import-stripe --dry-run')
            ->assertSuccessful();

        $this->assertSame(0, Donation::query()->count());
    }
}
