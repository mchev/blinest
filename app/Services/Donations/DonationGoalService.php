<?php

namespace App\Services\Donations;

use App\Models\Donation;
use App\Models\User;
use App\Notifications\DonationThankYou;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DonationGoalService
{
    public function timezone(): string
    {
        return config('donations.timezone', 'Europe/Paris');
    }

    public function monthlyGoalCents(): int
    {
        return max(1, (int) config('donations.monthly_goal_cents', 10_000));
    }

    public function paymentUrl(): string
    {
        return (string) config('donations.stripe_payment_url');
    }

    public function paymentUrlForUser(?User $user = null, ?string $locale = null): string
    {
        $baseUrl = $this->paymentUrl();
        $query = [];

        $locale ??= app()->getLocale();

        if (is_string($locale) && in_array($locale, ['fr', 'en', 'es'], true)) {
            $query['locale'] = $locale;
        }

        if ($user !== null && ! $user->isGuest()) {
            $query['client_reference_id'] = (string) $user->id;

            $email = $user->email;

            if (is_string($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $query['locked_prefilled_email'] = $email;
            }
        }

        if ($query === []) {
            return $baseUrl;
        }

        $fragment = '';

        if (($hashPosition = strpos($baseUrl, '#')) !== false) {
            $fragment = substr($baseUrl, $hashPosition);
            $baseUrl = substr($baseUrl, 0, $hashPosition);
        }

        $separator = str_contains($baseUrl, '?') ? '&' : '?';

        return $baseUrl.$separator.http_build_query($query).$fragment;
    }

    /**
     * @return array<string, mixed>
     */
    public function currentProgressForUser(?User $user = null, ?string $locale = null): array
    {
        $progress = $this->currentProgress();
        $progress['payment_url'] = $this->paymentUrlForUser($user, $locale);
        $progress['monthly_supporters'] = $this->monthlySupporters();

        return $progress;
    }

    /**
     * @return list<array{id: int, name: string, photo: string, is_supporter: bool, donor_perks: list<string>}>
     */
    public function monthlySupporters(?string $monthKey = null, ?int $limit = null): array
    {
        $monthKey ??= $this->monthKey();
        $donorPerks = app(DonorPerkService::class);

        $supporters = Donation::query()
            ->with(['user:id,name,photo_path'])
            ->where('month_key', $monthKey)
            ->whereNotNull('user_id')
            ->orderByDesc('donated_at')
            ->get()
            ->unique('user_id')
            ->map(function (Donation $donation) use ($donorPerks): array {
                return $donorPerks->enrichUserPayload([
                    'id' => $donation->user->id,
                    'name' => $donation->user->name,
                    'photo' => $donation->user->photo,
                ], $donation->user);
            })
            ->values();

        if ($limit !== null) {
            $supporters = $supporters->take($limit);
        }

        return $supporters->all();
    }

    /**
     * @return list<array{id: int, name: string, photo: string}>
     */
    public function recentSupporters(int $limit = 3): array
    {
        return $this->monthlySupporters(limit: $limit);
    }

    public function monthKey(?CarbonInterface $date = null): string
    {
        $date ??= now($this->timezone());

        return $date->copy()->timezone($this->timezone())->format('Y-m');
    }

    public function isGoalReached(?string $monthKey = null): bool
    {
        $monthKey ??= $this->monthKey();

        return $this->monthProgress($monthKey)['goal_reached'];
    }

    public function shouldDisableAds(): bool
    {
        return $this->isGoalReached();
    }

    /**
     * @return array<string, mixed>
     */
    public function currentProgress(): array
    {
        $monthKey = $this->monthKey();

        return Cache::remember(
            $this->cacheKey($monthKey),
            config('donations.cache_ttl_seconds', 300),
            fn (): array => $this->buildProgress($monthKey),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function monthProgress(string $monthKey): array
    {
        $goalCents = $this->monthlyGoalCents();
        $raisedCents = $this->raisedCentsForMonth($monthKey);
        $carryoverCents = $this->carryoverCentsBeforeMonth($monthKey);
        $effectiveCents = $carryoverCents + $raisedCents;
        $goalReached = $effectiveCents >= $goalCents;
        $surplusCents = max(0, $effectiveCents - $goalCents);
        $percent = (int) min(100, round(($effectiveCents / $goalCents) * 100));

        return [
            'month_key' => $monthKey,
            'raised_cents' => $raisedCents,
            'carryover_cents' => $carryoverCents,
            'effective_cents' => $effectiveCents,
            'surplus_cents' => $surplusCents,
            'goal_cents' => $goalCents,
            'percent' => $percent,
            'goal_reached' => $goalReached,
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function monthlyHistory(int $months = 12): array
    {
        $timezone = $this->timezone();
        $goalCents = $this->monthlyGoalCents();
        $startMonth = now($timezone)->startOfMonth()->subMonths($months - 1);

        $aggregates = Donation::query()
            ->select('month_key', DB::raw('SUM(amount_cents) as raised_cents'), DB::raw('COUNT(*) as donation_count'))
            ->where('month_key', '>=', $startMonth->format('Y-m'))
            ->groupBy('month_key')
            ->orderBy('month_key')
            ->get()
            ->keyBy('month_key');

        $history = [];
        $carryoverCents = $this->carryoverCentsBeforeMonth($startMonth->format('Y-m'));

        for ($i = 0; $i < $months; $i++) {
            $month = $startMonth->copy()->addMonths($i);
            $key = $month->format('Y-m');
            $aggregate = $aggregates->get($key);
            $raisedCents = (int) ($aggregate->raised_cents ?? 0);
            $effectiveCents = $carryoverCents + $raisedCents;
            $goalReached = $effectiveCents >= $goalCents;
            $surplusCents = max(0, $effectiveCents - $goalCents);

            $history[] = [
                'month_key' => $key,
                'raised_cents' => $raisedCents,
                'carryover_cents' => $carryoverCents,
                'effective_cents' => $effectiveCents,
                'surplus_cents' => $surplusCents,
                'goal_cents' => $goalCents,
                'goal_reached' => $goalReached,
                'donation_count' => (int) ($aggregate->donation_count ?? 0),
            ];

            $carryoverCents = $surplusCents;
        }

        return array_reverse($history);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function userDonationHistory(User $user, int $limit = 50): array
    {
        if ($user->isGuest()) {
            return [];
        }

        return Donation::query()
            ->where('user_id', $user->id)
            ->orderByDesc('donated_at')
            ->limit($limit)
            ->get()
            ->map(fn (Donation $donation): array => [
                'id' => $donation->id,
                'amount_cents' => $donation->amount_cents,
                'currency' => $donation->currency,
                'month_key' => $donation->month_key,
                'donated_at' => $donation->donated_at->toIso8601String(),
            ])
            ->values()
            ->all();
    }

    /**
     * @return array<string, int|string|null>
     */
    public function userDonationSummary(User $user): array
    {
        if ($user->isGuest()) {
            return [
                'total_cents' => 0,
                'donation_count' => 0,
                'months_supported' => 0,
                'first_donated_at' => null,
                'first_supported_month_key' => null,
            ];
        }

        $query = Donation::query()->where('user_id', $user->id);

        return [
            'total_cents' => (int) (clone $query)->sum('amount_cents'),
            'donation_count' => (int) (clone $query)->count(),
            'months_supported' => (int) (clone $query)->pluck('month_key')->unique()->count(),
            'first_donated_at' => (clone $query)->orderBy('donated_at')->value('donated_at')?->toIso8601String(),
            'first_supported_month_key' => (clone $query)->orderBy('month_key')->value('month_key'),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function recentDonations(int $limit = 30): array
    {
        return Donation::query()
            ->with(['user:id,name,photo_path'])
            ->orderByDesc('donated_at')
            ->limit($limit)
            ->get()
            ->map(function (Donation $donation): array {
                $entry = [
                    'id' => $donation->id,
                    'amount_cents' => $donation->amount_cents,
                    'currency' => $donation->currency,
                    'month_key' => $donation->month_key,
                    'donated_at' => $donation->donated_at->toIso8601String(),
                    'anonymous' => $donation->user_id === null,
                ];

                if ($donation->user !== null) {
                    $entry['user'] = [
                        'id' => $donation->user->id,
                        'name' => $donation->user->name,
                        'photo' => $donation->user->photo,
                    ];
                }

                return $entry;
            })
            ->values()
            ->all();
    }

    public function userIsSupporter(?User $user, ?string $monthKey = null): bool
    {
        if ($user === null || $user->isGuest()) {
            return false;
        }

        $monthKey ??= $this->monthKey();

        return Donation::query()
            ->where('month_key', $monthKey)
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * @param  array<string, mixed>  $session
     */
    public function recordCheckoutSession(array $session): ?Donation
    {
        $sessionId = $session['id'] ?? null;

        if (! is_string($sessionId) || $sessionId === '') {
            return null;
        }

        if (Donation::query()->where('stripe_checkout_session_id', $sessionId)->exists()) {
            return Donation::query()->where('stripe_checkout_session_id', $sessionId)->first();
        }

        if (($session['payment_status'] ?? null) !== 'paid') {
            return null;
        }

        $amountCents = (int) ($session['amount_total'] ?? 0);

        if ($amountCents <= 0) {
            return null;
        }

        $donatedAt = isset($session['created'])
            ? Carbon::createFromTimestamp((int) $session['created'], $this->timezone())
            : now($this->timezone());

        $monthKey = $this->monthKey($donatedAt);
        $donorEmail = $session['customer_details']['email'] ?? null;
        $donorEmail = is_string($donorEmail) ? strtolower($donorEmail) : null;
        $user = $this->resolveUser($session, $donorEmail);

        $donation = Donation::query()->create([
            'stripe_checkout_session_id' => $sessionId,
            'amount_cents' => $amountCents,
            'currency' => strtolower((string) ($session['currency'] ?? 'eur')),
            'month_key' => $monthKey,
            'user_id' => $user?->id,
            'donor_email' => $donorEmail,
            'donated_at' => $donatedAt,
        ]);

        $this->forgetCache($monthKey);

        if ($user !== null) {
            $user->notify(new DonationThankYou($donation));
        }

        return $donation;
    }

    /**
     * @param  list<array<string, mixed>>  $entries
     * @return list<array<string, mixed>>
     */
    public function annotateSupporterStatus(array $entries): array
    {
        if ($entries === []) {
            return [];
        }

        $userIds = collect($entries)
            ->pluck('user.id')
            ->filter()
            ->unique()
            ->values()
            ->all();

        $perkMap = app(DonorPerkService::class)->perkMapForUserIds($userIds);
        $donorPerks = app(DonorPerkService::class);

        return array_map(function (array $entry) use ($donorPerks, $perkMap): array {
            if (isset($entry['user']['id'])) {
                $entry['user'] = $donorPerks->enrichUserPayloadWithMap(
                    $entry['user'],
                    (int) $entry['user']['id'],
                    $perkMap,
                );
            }

            return $entry;
        }, $entries);
    }

    public function carryoverCentsBeforeMonth(string $monthKey): int
    {
        $timezone = $this->timezone();
        $goalCents = $this->monthlyGoalCents();

        $firstDonationMonth = Donation::query()
            ->orderBy('month_key')
            ->value('month_key');

        if (! is_string($firstDonationMonth) || $firstDonationMonth === '') {
            return 0;
        }

        $cursor = Carbon::createFromFormat('Y-m', $firstDonationMonth, $timezone)->startOfMonth();
        $target = Carbon::createFromFormat('Y-m', $monthKey, $timezone)->startOfMonth();

        if ($cursor->gte($target)) {
            return 0;
        }

        $carryoverCents = 0;

        while ($cursor->lt($target)) {
            $key = $cursor->format('Y-m');
            $availableCents = $carryoverCents + $this->raisedCentsForMonth($key);
            $carryoverCents = max(0, $availableCents - $goalCents);
            $cursor->addMonth();
        }

        return $carryoverCents;
    }

    protected function raisedCentsForMonth(string $monthKey): int
    {
        return (int) Donation::query()
            ->where('month_key', $monthKey)
            ->sum('amount_cents');
    }

    /**
     * @return array<string, mixed>
     */
    protected function buildProgress(string $monthKey): array
    {
        $timezone = $this->timezone();
        $now = now($timezone);
        $monthStart = Carbon::createFromFormat('Y-m', $monthKey, $timezone)->startOfMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();
        $progress = $this->monthProgress($monthKey);
        $daysRemaining = max(0, $now->copy()->startOfDay()->diffInDays($monthEnd->copy()->startOfDay(), false));

        return array_merge($progress, [
            'days_remaining' => $daysRemaining,
            'payment_url' => $this->paymentUrl(),
            'ads_disabled' => $progress['goal_reached'],
        ]);
    }

    /**
     * @param  array<string, mixed>  $session
     */
    protected function resolveUser(array $session, ?string $donorEmail): ?User
    {
        $clientReferenceId = $session['client_reference_id'] ?? null;

        if (is_string($clientReferenceId) && ctype_digit($clientReferenceId)) {
            $user = User::query()->find((int) $clientReferenceId);

            if ($user !== null && ! $user->isGuest()) {
                return $user;
            }
        }

        if ($donorEmail === null) {
            return null;
        }

        return User::query()
            ->whereRaw('LOWER(email) = ?', [$donorEmail])
            ->where('is_guest', false)
            ->first();
    }

    protected function cacheKey(string $monthKey): string
    {
        return "donation_goal_progress:{$monthKey}";
    }

    protected function forgetCache(string $monthKey): void
    {
        Cache::forget($this->cacheKey($monthKey));
    }
}
