<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Support\AutomatedTraffic;
use App\Support\ClientIp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class GuestAuthService
{
    public function __construct(
        private Request $request,
    ) {}

    /**
     * Ensure the visitor has an authenticated session, creating a guest if needed.
     *
     * Returns null for automated clients (crawlers, scrapers) — no DB write.
     */
    public function ensureGuestSession(): ?User
    {
        $user = Auth::user();

        if ($user !== null) {
            return $user;
        }

        if (AutomatedTraffic::shouldSkipGuestSession($this->request)) {
            return null;
        }

        $this->assertCanCreateGuest();

        $user = User::create([
            'name' => 'Guest-'.random_int(10000, 99999),
            'email' => 'guest-'.Str::random(8).'@b.est',
            'password' => null,
            'is_guest' => true,
            'guest_token' => (string) Str::uuid(),
        ]);

        Auth::login($user);

        $this->recordGuestCreation();

        return $user;
    }

    private function assertCanCreateGuest(): void
    {
        $limits = [
            ['suffix' => 'minute', 'max' => config('guests.rate_limit.per_minute', 3), 'decay' => 60],
            ['suffix' => 'hour', 'max' => config('guests.rate_limit.per_hour', 20), 'decay' => 3600],
            ['suffix' => 'day', 'max' => config('guests.rate_limit.per_day', 50), 'decay' => 86400],
        ];

        foreach ($limits as $limit) {
            $key = $this->throttleKey($limit['suffix']);

            if (! RateLimiter::tooManyAttempts($key, $limit['max'])) {
                continue;
            }

            $seconds = RateLimiter::availableIn($key);

            throw new TooManyRequestsHttpException(
                $seconds,
                __('Guest creation throttle', ['seconds' => $seconds]),
            );
        }
    }

    private function recordGuestCreation(): void
    {
        RateLimiter::hit($this->throttleKey('minute'), 60);
        RateLimiter::hit($this->throttleKey('hour'), 3600);
        RateLimiter::hit($this->throttleKey('day'), 86400);
    }

    private function throttleKey(string $window): string
    {
        return 'guest-create:'.$window.':'.ClientIp::from($this->request);
    }
}
