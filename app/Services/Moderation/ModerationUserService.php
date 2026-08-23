<?php

namespace App\Services\Moderation;

use App\Models\Message;
use App\Models\Room;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ModerationUserService
{
    public function canViewSensitiveData(User $viewer): bool
    {
        return (bool) $viewer->is_administrator;
    }

    /**
     * @return LengthAwarePaginator<int, array<string, mixed>>
     */
    public function paginateUsers(User $viewer, ?string $search, int $perPage = 20): LengthAwarePaginator
    {
        $canViewSensitive = $this->canViewSensitiveData($viewer);

        return $this->baseQuery($canViewSensitive)
            ->when(filled($search), fn (Builder $query) => $this->applySearch($query, $search, $canViewSensitive))
            ->withCount([
                'messages',
                'bans',
                'messages as reported_messages_count' => fn (Builder $query) => $query->whereHas('downvotes'),
            ])
            ->withMax('messages as last_message_at', 'created_at')
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString()
            ->through(fn (User $user) => $this->formatListUser($user, $canViewSensitive));
    }

    /**
     * @return array<string, mixed>
     */
    public function formatUserDetail(User $user, User $viewer): array
    {
        $canViewSensitive = $this->canViewSensitiveData($viewer);

        $user->loadCount([
            'messages',
            'bans',
            'rooms',
            'playlists',
            'messages as reported_messages_count' => fn (Builder $query) => $query->whereHas('downvotes'),
        ]);

        $user->load([
            'bans.createdBy:id,name',
            'rooms' => fn ($query) => $query
                ->select('id', 'name', 'slug', 'is_public', 'user_id', 'created_at')
                ->withCount('messages')
                ->latest()
                ->limit(10),
            'playlists' => fn ($query) => $query
                ->select('id', 'name', 'is_public', 'user_id', 'created_at')
                ->withCount('tracks')
                ->latest()
                ->limit(10),
        ]);

        $detail = [
            ...$this->formatListUser($user, $canViewSensitive),
            'profile_url' => route('user.profile', $user),
            'bans' => $user->bans
                ->sortByDesc('created_at')
                ->values()
                ->map(fn ($ban) => [
                    'id' => $ban->id,
                    'comment' => $ban->comment,
                    'created_at' => $ban->created_at?->toIso8601String(),
                    'expires_at' => $ban->expired_at?->toIso8601String(),
                    'expires_at_label' => $ban->expired_at
                        ? $ban->expired_at->diffForHumans()
                        : __('Permanent ban'),
                    'banned_by' => $ban->createdBy?->name,
                ])
                ->all(),
            'rooms' => $user->rooms->map(fn ($room) => [
                'id' => $room->id,
                'name' => $room->name,
                'slug' => $room->slug,
                'url' => route('rooms.show', $room->slug),
                'created_at' => $room->created_at?->toIso8601String(),
                'is_public' => (bool) $room->is_public,
                'messages_count' => $room->messages_count ?? 0,
            ])->all(),
            'playlists' => $user->playlists->map(fn ($playlist) => [
                'id' => $playlist->id,
                'name' => $playlist->name,
                'created_at' => $playlist->created_at?->toIso8601String(),
                'is_public' => (bool) $playlist->is_public,
                'tracks_count' => $playlist->tracks_count ?? 0,
            ])->all(),
            'conversation_rooms' => $this->recentConversationsByRoom($user),
            'related_accounts' => $this->formatRelatedAccounts($user, $canViewSensitive),
        ];

        if ($canViewSensitive) {
            $detail['admin'] = $this->adminInsights($user);
        }

        return $detail;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function recentConversationsByRoom(User $user, int $threadsPerRoom = 8, int $maxRooms = 12): array
    {
        $latestByRoom = Message::query()
            ->withTrashed()
            ->where('user_id', $user->id)
            ->where('messagable_type', Room::class)
            ->selectRaw('messagable_id, MAX(created_at) as last_message_at')
            ->groupBy('messagable_id')
            ->orderByDesc('last_message_at')
            ->limit($maxRooms)
            ->get();

        if ($latestByRoom->isEmpty()) {
            return [];
        }

        $rooms = Room::query()
            ->whereIn('id', $latestByRoom->pluck('messagable_id'))
            ->get()
            ->keyBy('id');

        return $latestByRoom
            ->map(function ($row) use ($user, $rooms, $threadsPerRoom) {
                $room = $rooms->get($row->messagable_id);

                if ($room === null) {
                    return null;
                }

                $messages = Message::query()
                    ->withTrashed()
                    ->where('user_id', $user->id)
                    ->where('messagable_type', Room::class)
                    ->where('messagable_id', $room->id)
                    ->orderByDesc('created_at')
                    ->limit($threadsPerRoom)
                    ->get();

                return [
                    'room' => [
                        'id' => $room->id,
                        'name' => $room->name,
                        'slug' => $room->slug,
                        'url' => route('rooms.show', $room->slug),
                    ],
                    'threads_count' => $messages->count(),
                    'last_message_at' => $row->last_message_at,
                    'threads' => $messages
                        ->map(fn (Message $message) => $this->formatMessageThread($message))
                        ->all(),
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @return array{count: int, users: list<array<string, mixed>>}
     */
    public function formatRelatedAccounts(User $user, bool $canViewSensitive): array
    {
        $relatedUsers = $this->relatedUsersByIp($user);

        return [
            'count' => count($relatedUsers),
            'users' => collect($relatedUsers)
                ->map(function (array $relatedUser) use ($canViewSensitive) {
                    $payload = [
                        'id' => $relatedUser['id'],
                        'name' => $relatedUser['name'],
                        'created_at' => $relatedUser['created_at'],
                        'provider' => $relatedUser['provider'],
                        'profile_url' => $relatedUser['profile_url'],
                        'is_banned' => $relatedUser['is_banned'],
                    ];

                    if ($canViewSensitive) {
                        $payload['ip'] = $relatedUser['ip'];
                    }

                    return $payload;
                })
                ->all(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function formatListUser(User $user, bool $canViewSensitive): array
    {
        $payload = [
            'id' => $user->id,
            'name' => $user->name,
            'photo' => $user->photo,
            'created_at' => $user->created_at?->toIso8601String(),
            'provider' => $user->provider,
            'is_guest' => $user->isGuest(),
            'is_admin' => $user->isAdministrator(),
            'is_moderator' => $user->isPublicModerator(),
            'is_banned' => $user->isBanned(),
            'messages_count' => $user->messages_count ?? 0,
            'reported_messages_count' => $user->reported_messages_count ?? 0,
            'bans_count' => $user->bans_count ?? 0,
            'rooms_count' => $user->rooms_count ?? null,
            'playlists_count' => $user->playlists_count ?? null,
            'last_message_at' => $user->last_message_at ?? null,
            'can_ban' => ! $user->isAdministrator() && ! $user->isPublicModerator(),
        ];

        if ($canViewSensitive) {
            $payload['email'] = $user->email;
            $payload['ip'] = $user->ip;
        }

        return $payload;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function recentMessageThreads(User $user, int $limit = 15): array
    {
        $messages = Message::query()
            ->withTrashed()
            ->where('user_id', $user->id)
            ->where('messagable_type', Room::class)
            ->with([
                'room:id,name,slug',
            ])
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();

        return $messages
            ->map(fn (Message $message) => $this->formatMessageThread($message))
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    public function formatMessageThread(Message $message): array
    {
        $context = $this->messageContext($message);

        return [
            'id' => $message->id,
            'body' => $message->body,
            'created_at' => $message->created_at?->toIso8601String(),
            'created_at_label' => $message->created_at?->format('d/m/Y H:i'),
            'is_deleted' => $message->trashed(),
            'reports_count' => abs($message->totalDownvotes()),
            'room' => [
                'id' => $message->room?->id,
                'name' => $message->room?->name,
                'slug' => $message->room?->slug,
                'url' => $message->room ? route('rooms.show', $message->room->slug) : null,
            ],
            'context' => $context,
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function messageContext(Message $message, int $before = 4, int $after = 2): array
    {
        if ($message->messagable_type !== Room::class) {
            return [$this->formatContextMessage($message, true)];
        }

        $baseQuery = fn () => Message::query()
            ->withTrashed()
            ->where('messagable_type', $message->messagable_type)
            ->where('messagable_id', $message->messagable_id)
            ->with('user:id,name');

        $previous = $baseQuery()
            ->where(function (Builder $query) use ($message) {
                $query->where('created_at', '<', $message->created_at)
                    ->orWhere(function (Builder $query) use ($message) {
                        $query->where('created_at', $message->created_at)
                            ->where('id', '<', $message->id);
                    });
            })
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->limit($before)
            ->get()
            ->reverse()
            ->values();

        $next = $baseQuery()
            ->where(function (Builder $query) use ($message) {
                $query->where('created_at', '>', $message->created_at)
                    ->orWhere(function (Builder $query) use ($message) {
                        $query->where('created_at', $message->created_at)
                            ->where('id', '>', $message->id);
                    });
            })
            ->orderBy('created_at')
            ->orderBy('id')
            ->limit($after)
            ->get();

        return $previous
            ->map(fn (Message $contextMessage) => $this->formatContextMessage($contextMessage, $contextMessage->is($message)))
            ->push($this->formatContextMessage($message, true))
            ->concat($next->map(fn (Message $contextMessage) => $this->formatContextMessage($contextMessage, false)))
            ->values()
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    private function formatContextMessage(Message $message, bool $isTarget): array
    {
        return [
            'id' => $message->id,
            'body' => $message->body,
            'created_at_label' => $message->created_at?->format('d/m/Y H:i'),
            'is_deleted' => $message->trashed(),
            'is_target' => $isTarget,
            'user' => [
                'id' => $message->user?->id,
                'name' => $message->user?->name,
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function adminInsights(User $user): array
    {
        $messageIps = Message::query()
            ->where('user_id', $user->id)
            ->whereNotNull('user_ip')
            ->where('user_ip', '!=', '')
            ->selectRaw('user_ip, COUNT(*) as messages_count, MAX(created_at) as last_seen_at')
            ->groupBy('user_ip')
            ->orderByDesc('last_seen_at')
            ->limit(10)
            ->get()
            ->map(fn ($row) => [
                'ip' => $row->user_ip,
                'messages_count' => (int) $row->messages_count,
                'last_seen_at' => $row->last_seen_at,
            ])
            ->all();

        $relatedUsers = $this->relatedUsersByIp($user);

        return [
            'email' => $user->email,
            'registration_ip' => $user->ip,
            'message_ips' => $messageIps,
            'risk_flags' => $this->ipRiskFlags($user, $messageIps, $relatedUsers),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function relatedUsersByIp(User $user): array
    {
        $ips = collect([$user->ip])
            ->merge(
                Message::query()
                    ->where('user_id', $user->id)
                    ->whereNotNull('user_ip')
                    ->distinct()
                    ->pluck('user_ip')
            )
            ->filter()
            ->unique()
            ->values();

        if ($ips->isEmpty()) {
            return [];
        }

        return User::query()
            ->realUsers()
            ->where('id', '!=', $user->id)
            ->where(function (Builder $query) use ($ips) {
                $query->whereIn('ip', $ips)
                    ->orWhereIn('id', function ($subQuery) use ($ips) {
                        $subQuery->select('user_id')
                            ->from('messages')
                            ->whereIn('user_ip', $ips)
                            ->whereNotNull('user_id');
                    });
            })
            ->select('id', 'name', 'ip', 'created_at', 'provider')
            ->orderByDesc('created_at')
            ->limit(20)
            ->get()
            ->map(fn (User $relatedUser) => [
                'id' => $relatedUser->id,
                'name' => $relatedUser->name,
                'ip' => $relatedUser->ip,
                'created_at' => $relatedUser->created_at?->toIso8601String(),
                'provider' => $relatedUser->provider,
                'profile_url' => route('moderation.users.show', $relatedUser),
                'is_banned' => $relatedUser->isBanned(),
            ])
            ->all();
    }

    /**
     * @param  list<array{ip: string, messages_count: int, last_seen_at: mixed}>  $messageIps
     * @param  list<array<string, mixed>>  $relatedUsers
     * @return list<array{key: string, level: string, message: string}>
     */
    public function ipRiskFlags(User $user, array $messageIps, array $relatedUsers): array
    {
        $flags = [];

        if (count($relatedUsers) > 0) {
            $flags[] = [
                'key' => 'shared_ip',
                'level' => 'warning',
                'message' => __('Moderation shared ip accounts', ['count' => count($relatedUsers)]),
            ];
        }

        if (count($messageIps) > 1) {
            $flags[] = [
                'key' => 'multiple_ips',
                'level' => 'info',
                'message' => __('Moderation multiple ips detected', ['count' => count($messageIps)]),
            ];
        }

        $latestMessageIp = $messageIps[0]['ip'] ?? null;

        if ($user->ip && $latestMessageIp && $user->ip !== $latestMessageIp) {
            $flags[] = [
                'key' => 'registration_ip_mismatch',
                'level' => 'info',
                'message' => __('Moderation registration ip mismatch'),
            ];
        }

        if ($flags === []) {
            $flags[] = [
                'key' => 'none',
                'level' => 'neutral',
                'message' => __('Moderation no ip risk detected'),
            ];
        }

        return $flags;
    }

    private function baseQuery(bool $canViewSensitive): Builder
    {
        return User::query()
            ->realUsers()
            ->when(
                ! $canViewSensitive,
                fn (Builder $query) => $query->select([
                    'id',
                    'name',
                    'photo_path',
                    'created_at',
                    'provider',
                    'is_guest',
                    'is_administrator',
                    'ip',
                ])
            );
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
