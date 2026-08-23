<?php

namespace App\Services\Moderation;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Mchev\Banhammer\Models\Ban;

class ModerationBannedUserService
{
    public function __construct(
        private ModerationUserService $moderationUsers,
    ) {}

    /**
     * @return LengthAwarePaginator<int, array<string, mixed>>
     */
    public function paginateBannedUsers(User $viewer, ?string $search, int $perPage = 20): LengthAwarePaginator
    {
        $canViewSensitive = $this->moderationUsers->canViewSensitiveData($viewer);

        return User::query()
            ->banned()
            ->realUsers()
            ->when(filled($search), fn (Builder $query) => $this->applySearch($query, $search, $canViewSensitive))
            ->withMax('bans as latest_ban_at', 'created_at')
            ->with(['bans' => fn ($query) => $query->with('createdBy:id,name')->latest()])
            ->orderByDesc('latest_ban_at')
            ->paginate($perPage)
            ->withQueryString()
            ->through(fn (User $user) => $this->formatBannedUser($user, $canViewSensitive));
    }

    /**
     * @return array<string, mixed>
     */
    public function formatBannedUser(User $user, bool $canViewSensitive): array
    {
        $activeBan = $this->activeBan($user);

        $payload = [
            'id' => $user->id,
            'name' => $user->name,
            'photo' => $user->photo,
            'profile_url' => route('moderation.users.show', $user),
            'bans_count' => $user->bans->count(),
            'active_ban' => $activeBan ? $this->formatBan($activeBan, $canViewSensitive) : null,
            'ban_history' => $user->bans
                ->map(fn (Ban $ban) => $this->formatBan($ban, $canViewSensitive))
                ->all(),
        ];

        if ($canViewSensitive) {
            $payload['email'] = $user->email;
            $payload['ip'] = $user->ip;
        }

        return $payload;
    }

    /**
     * @return array<string, mixed>
     */
    public function formatBan(Ban $ban, bool $canViewSensitive): array
    {
        $payload = [
            'id' => $ban->id,
            'comment' => $ban->comment,
            'created_at' => $ban->created_at?->toIso8601String(),
            'expires_at' => $ban->expired_at?->toIso8601String(),
            'expires_at_label' => $ban->expired_at
                ? $ban->expired_at->diffForHumans()
                : __('Permanent ban'),
            'is_permanent' => $ban->expired_at === null,
            'banned_by' => $ban->createdBy?->name,
        ];

        if ($canViewSensitive) {
            $payload['ip'] = $ban->ip;
        }

        return $payload;
    }

    private function activeBan(User $user): ?Ban
    {
        return $user->bans->first(function (Ban $ban) {
            return $ban->expired_at === null || $ban->expired_at->isFuture();
        }) ?? $user->bans->first();
    }

    private function applySearch(Builder $query, string $search, bool $canViewSensitive): void
    {
        $term = trim($search);

        $query->where(function (Builder $query) use ($term, $canViewSensitive) {
            $query->where('name', 'like', '%'.$term.'%');

            if (ctype_digit($term)) {
                $query->orWhere('id', (int) $term);
            }

            if ($canViewSensitive) {
                $query->orWhere('email', 'like', '%'.$term.'%')
                    ->orWhere('ip', 'like', '%'.$term.'%');
            }
        });
    }
}
