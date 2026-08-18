<?php

namespace App\Services\Donations;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class StripeCheckoutImporter
{
    public function __construct(private DonationGoalService $donationGoal) {}

    /**
     * @return array{imported: int, skipped: int, pages: int}
     */
    public function import(bool $dryRun = false, ?string $paymentLinkId = null, int $maxPages = 50): array
    {
        $secretKey = config('donations.stripe_secret_key');

        if (! is_string($secretKey) || $secretKey === '') {
            throw new \InvalidArgumentException('Stripe API credentials are not configured.');
        }

        $imported = 0;
        $skipped = 0;
        $pages = 0;
        $startingAfter = null;

        do {
            $query = [
                'limit' => 100,
                'status' => 'complete',
            ];

            if ($startingAfter !== null) {
                $query['starting_after'] = $startingAfter;
            }

            if (is_string($paymentLinkId) && $paymentLinkId !== '') {
                $query['payment_link'] = $paymentLinkId;
            }

            $response = $this->client($secretKey)->get('https://api.stripe.com/v1/checkout/sessions', $query);

            if ($response->failed()) {
                throw new \RuntimeException('Stripe API request failed.');
            }

            $sessions = $response->json('data') ?? [];
            $pages++;

            foreach ($sessions as $session) {
                if (! is_array($session)) {
                    continue;
                }

                if (($session['payment_status'] ?? null) !== 'paid') {
                    $skipped++;

                    continue;
                }

                if ($dryRun) {
                    $imported++;

                    continue;
                }

                $donation = $this->donationGoal->recordCheckoutSession($session);

                if ($donation === null) {
                    $skipped++;
                } else {
                    $imported++;
                }
            }

            $hasMore = (bool) $response->json('has_more');
            $startingAfter = $hasMore && $sessions !== []
                ? (string) ($sessions[array_key_last($sessions)]['id'] ?? '')
                : null;

            if ($startingAfter === '') {
                $hasMore = false;
            }
        } while ($hasMore && $pages < $maxPages);

        return [
            'imported' => $imported,
            'skipped' => $skipped,
            'pages' => $pages,
        ];
    }

    protected function client(string $secretKey): PendingRequest
    {
        return Http::withToken($secretKey)
            ->acceptJson()
            ->timeout(30);
    }
}
