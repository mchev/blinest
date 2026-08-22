<?php

namespace App\Services\Chat;

use App\Enums\DonorPerk;
use App\Models\User;
use App\Services\Donations\DonorPerkService;

class ChatReactionService
{
    public function __construct(private DonorPerkService $donorPerks) {}

    /**
     * @return list<string>
     */
    public function standardEmojis(): array
    {
        return config('chat.reaction_emojis', []);
    }

    /**
     * @return list<string>
     */
    public function supporterEmojis(): array
    {
        return config('donations.supporter_reaction_emojis', []);
    }

    public function isSupporterEmoji(string $emoji): bool
    {
        return in_array($emoji, $this->supporterEmojis(), true);
    }

    public function isAllowedEmoji(string $emoji): bool
    {
        return in_array($emoji, $this->standardEmojis(), true)
            || in_array($emoji, $this->supporterEmojis(), true);
    }

    public function canUserReactWith(?User $user, string $emoji): bool
    {
        if (! $this->isAllowedEmoji($emoji)) {
            return false;
        }

        if ($this->isSupporterEmoji($emoji)) {
            return $this->donorPerks->userHasPerk($user, DonorPerk::SupporterReactions);
        }

        return $user !== null && ! $user->isGuest();
    }

    /**
     * @return array{standard: list<string>, supporter: list<string>}
     */
    public function emojiCatalog(): array
    {
        return [
            'standard' => $this->standardEmojis(),
            'supporter' => $this->supporterEmojis(),
        ];
    }
}
