<?php

namespace App\Http\Controllers;

use App\Services\Donations\DonationGoalService;
use App\Services\Donations\StripeWebhookVerifier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;

class StripeWebhookController extends Controller
{
    public function __invoke(
        Request $request,
        StripeWebhookVerifier $verifier,
        DonationGoalService $donationGoal,
    ): JsonResponse {
        try {
            $event = $verifier->verify($request);
        } catch (InvalidArgumentException) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if (($event['type'] ?? '') === 'checkout.session.completed') {
            $session = $event['data']['object'] ?? [];

            if (is_array($session)) {
                $donationGoal->recordCheckoutSession($session);
            }
        }

        return response()->json(['received' => true]);
    }
}
