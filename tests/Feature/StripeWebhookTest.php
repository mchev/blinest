<?php

namespace Tests\Feature;

use App\Models\Donation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class StripeWebhookTest extends TestCase
{
    use RefreshDatabase;

    private string $webhookSecret = 'whsec_test_secret';

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('donations.stripe_webhook_secret', $this->webhookSecret);
        Config::set('donations.timezone', 'Europe/Paris');
    }

    public function test_rejects_invalid_signature(): void
    {
        $this->postJson(route('stripe.webhook'), ['type' => 'checkout.session.completed'])
            ->assertStatus(400);
    }

    public function test_records_paid_checkout_session(): void
    {
        $payload = json_encode([
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'cs_webhook_test',
                    'payment_status' => 'paid',
                    'amount_total' => 1500,
                    'currency' => 'eur',
                    'created' => now()->timestamp,
                    'customer_details' => ['email' => 'donor@example.com'],
                ],
            ],
        ], JSON_THROW_ON_ERROR);

        $this->call(
            'POST',
            route('stripe.webhook'),
            [],
            [],
            [],
            $this->signedHeaders($payload),
            $payload,
        )->assertOk();

        $this->assertDatabaseHas('donations', [
            'stripe_checkout_session_id' => 'cs_webhook_test',
            'amount_cents' => 1500,
        ]);
    }

    public function test_duplicate_webhook_does_not_create_second_donation(): void
    {
        Donation::query()->create([
            'stripe_checkout_session_id' => 'cs_existing',
            'amount_cents' => 500,
            'currency' => 'eur',
            'month_key' => now('Europe/Paris')->format('Y-m'),
            'donated_at' => now('Europe/Paris'),
        ]);

        $payload = json_encode([
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'cs_existing',
                    'payment_status' => 'paid',
                    'amount_total' => 500,
                    'currency' => 'eur',
                    'created' => now()->timestamp,
                ],
            ],
        ], JSON_THROW_ON_ERROR);

        $this->call(
            'POST',
            route('stripe.webhook'),
            [],
            [],
            [],
            $this->signedHeaders($payload),
            $payload,
        )->assertOk();

        $this->assertSame(1, Donation::query()->count());
    }

    /**
     * @return array<string, string>
     */
    private function signedHeaders(string $payload): array
    {
        $timestamp = time();
        $signature = hash_hmac('sha256', "{$timestamp}.{$payload}", $this->webhookSecret);

        return [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_STRIPE_SIGNATURE' => "t={$timestamp},v1={$signature}",
        ];
    }
}
