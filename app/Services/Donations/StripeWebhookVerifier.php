<?php

namespace App\Services\Donations;

use Illuminate\Http\Request;
use InvalidArgumentException;

class StripeWebhookVerifier
{
    public function __construct(
        protected ?string $webhookSecret = null,
        protected int $toleranceSeconds = 300,
    ) {
        $this->webhookSecret ??= config('donations.stripe_webhook_secret');
    }

    /**
     * @return array<string, mixed>
     */
    public function verify(Request $request): array
    {
        $secret = $this->webhookSecret;

        if (! is_string($secret) || $secret === '') {
            throw new InvalidArgumentException('Stripe webhook is not configured.');
        }

        $payload = $request->getContent();
        $signatureHeader = $request->header('Stripe-Signature');

        if (! is_string($signatureHeader) || $signatureHeader === '') {
            throw new InvalidArgumentException('Missing Stripe-Signature header.');
        }

        $timestamp = null;
        $signatures = [];

        foreach (explode(',', $signatureHeader) as $part) {
            [$key, $value] = array_map('trim', explode('=', $part, 2) + [null, null]);

            if ($key === 't') {
                $timestamp = (int) $value;
            }

            if ($key === 'v1' && is_string($value)) {
                $signatures[] = $value;
            }
        }

        if ($timestamp === null || $signatures === []) {
            throw new InvalidArgumentException('Invalid Stripe-Signature header.');
        }

        if (abs(time() - $timestamp) > $this->toleranceSeconds) {
            throw new InvalidArgumentException('Stripe webhook timestamp outside tolerance.');
        }

        $signedPayload = $timestamp.'.'.$payload;
        $expected = hash_hmac('sha256', $signedPayload, $secret);

        foreach ($signatures as $signature) {
            if (hash_equals($expected, $signature)) {
                $decoded = json_decode($payload, true);

                if (! is_array($decoded)) {
                    throw new InvalidArgumentException('Invalid Stripe webhook payload.');
                }

                return $decoded;
            }
        }

        throw new InvalidArgumentException('Stripe webhook signature mismatch.');
    }
}
