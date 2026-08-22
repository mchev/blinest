<?php

namespace App\Services\Donations;

use App\Enums\DonorPerk;
use App\Models\Donation;
use App\Models\Message;
use App\Models\User;

class DonorPerkService
{
    public function __construct(private DonationGoalService $donationGoal) {}

    /**
     * @return list<string>
     */
    public function configuredPerks(): array
    {
        $configured = config('donations.supporter_perks', [
            DonorPerk::AdFree->value,
            DonorPerk::AvatarCrown->value,
        ]);

        return array_values(array_intersect(
            $configured,
            DonorPerk::values(),
        ));
    }

    /**
     * @return list<string>
     */
    public function activePerksForUser(?User $user, ?string $monthKey = null): array
    {
        if ($user === null || $user->isGuest()) {
            return [];
        }

        if (! $this->donationGoal->userIsSupporter($user, $monthKey)) {
            return [];
        }

        return $this->configuredPerks();
    }

    public function userHasPerk(?User $user, DonorPerk $perk, ?string $monthKey = null): bool
    {
        return in_array($perk->value, $this->activePerksForUser($user, $monthKey), true);
    }

    public function shouldDisableAdsForUser(?User $user, ?string $monthKey = null): bool
    {
        return $this->userHasPerk($user, DonorPerk::AdFree, $monthKey);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function enrichUserPayload(array $payload, User $user, ?string $monthKey = null): array
    {
        $perks = $this->activePerksForUser($user, $monthKey);

        $payload['donor_perks'] = $perks;
        $payload['is_supporter'] = $perks !== [];

        return $payload;
    }

    /**
     * @param  list<int>  $userIds
     * @return array<int, list<string>>
     */
    public function perkMapForUserIds(array $userIds, ?string $monthKey = null): array
    {
        if ($userIds === []) {
            return [];
        }

        $monthKey ??= $this->donationGoal->monthKey();
        $configuredPerks = $this->configuredPerks();

        $supporterIds = Donation::query()
            ->where('month_key', $monthKey)
            ->whereIn('user_id', $userIds)
            ->pluck('user_id')
            ->unique()
            ->all();

        $map = [];

        foreach ($supporterIds as $userId) {
            $map[(int) $userId] = $configuredPerks;
        }

        return $map;
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function enrichUserPayloadWithMap(array $payload, int $userId, array $perkMap): array
    {
        $perks = $perkMap[$userId] ?? [];

        $payload['donor_perks'] = $perks;
        $payload['is_supporter'] = $perks !== [];

        return $payload;
    }

    /**
     * @param  iterable<Message>  $messages
     * @return list<array<string, mixed>>
     */
    public function enrichMessagesForChat(iterable $messages): array
    {
        $messages = collect($messages);

        if ($messages->isEmpty()) {
            return [];
        }

        $userIds = $messages
            ->pluck('user.id')
            ->filter()
            ->unique()
            ->values()
            ->all();

        $perkMap = $this->perkMapForUserIds($userIds);

        return $messages
            ->map(function (Message $message) use ($perkMap): array {
                $payload = $message->toArray();

                if (isset($payload['user']['id'])) {
                    $payload['user'] = $this->enrichUserPayloadWithMap(
                        $payload['user'],
                        (int) $payload['user']['id'],
                        $perkMap,
                    );
                }

                return $payload;
            })
            ->all();
    }
}
