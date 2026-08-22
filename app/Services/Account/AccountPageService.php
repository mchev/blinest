<?php

namespace App\Services\Account;

use App\Models\User;
use App\Services\Donations\DonationGoalService;
use App\Services\Donations\DonorPerkService;
use Illuminate\Support\Facades\Cache;

class AccountPageService
{
    public function __construct(
        private DonorPerkService $donorPerks,
        private DonationGoalService $donationGoal,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function payload(User $user): array
    {
        $stats = Cache::remember("user_profile_stats_{$user->id}", 1800, function () use ($user) {
            return [
                'rooms' => $user->moderatedRooms()->count(),
                'playlists' => $user->moderatedPlaylists()->count(),
                'bookmarks' => $user->bookmarkedRooms()->count(),
            ];
        });

        $payload = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'photo' => $user->photo,
            'has_custom_photo' => filled($user->photo_path),
            'created_at' => $user->created_at->format('d/m/Y'),
            'created_at_from_now' => $user->created_at->diffForHumans(null, true),
            'has_password' => $user->hasPassword(),
            'stats' => $stats,
            'links' => [
                'profile' => route('user.profile', $user),
                'profile_scores' => route('user.profile', ['user' => $user, 'tab' => 'scores']),
                'profile_bookmarks' => route('user.profile', ['user' => $user, 'tab' => 'bookmarks']),
                'profile_likes' => route('user.profile', ['user' => $user, 'tab' => 'likes']),
                'profile_minigames' => route('user.profile', ['user' => $user, 'tab' => 'minigames']),
                'rooms' => route('rooms.index'),
                'playlists' => route('playlists'),
                'rankings' => route('rankings.index'),
                'support' => route('docs.support'),
            ],
            'donation_summary' => $this->donationGoal->userDonationSummary($user),
        ];

        return $this->donorPerks->enrichUserPayload($payload, $user);
    }
}
