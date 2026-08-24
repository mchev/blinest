<?php

namespace App\Services\Moderation;

use App\Models\Playlist;
use App\Models\Room;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ModerationModeratorService
{
    private const INACTIVITY_MONTHS = 8;

    private const SNAPSHOT_CACHE_KEY = 'moderation.moderators.snapshot';

    private const SNAPSHOT_CACHE_SECONDS = 600;

    /**
     * @return array{
     *     moderators: LengthAwarePaginator<int, array<string, mixed>>,
     *     stats: array<string, int>,
     *     coverage: array<string, mixed>
     * }
     */
    public function paginate(
        ?string $search,
        bool $inactiveOnly = false,
        int $perPage = 20,
    ): array {
        $snapshot = $this->snapshot();
        $page = max(1, (int) request()->input('page', 1));

        $filtered = collect($snapshot['moderators'])
            ->when(filled($search), fn (Collection $moderators) => $this->filterBySearch($moderators, $search))
            ->when($inactiveOnly, fn (Collection $moderators) => $moderators->where('is_inactive', true))
            ->values();

        $pageItems = $filtered
            ->slice(($page - 1) * $perPage, $perPage)
            ->values();

        $userIds = $pageItems->pluck('id');
        $roomAssignments = $this->roomAssignmentsFor($userIds);
        $playlistAssignments = $this->playlistAssignmentsFor($userIds);

        $moderators = $pageItems
            ->map(fn (array $moderator) => $this->attachAssignments(
                $moderator,
                $roomAssignments->get($moderator['id'], collect()),
                $playlistAssignments->get($moderator['id'], collect()),
            ))
            ->values()
            ->all();

        $paginator = new Paginator(
            $moderators,
            $filtered->count(),
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ],
        );

        return [
            'moderators' => $paginator,
            'stats' => $snapshot['stats'],
            'coverage' => $snapshot['coverage'],
        ];
    }

    public function detachFromPublicRoom(Room $room, User $user): void
    {
        $this->assertPublicRoom($room);
        $this->assertNotRoomOwner($room, $user);

        $room->moderators()->detach($user->id);

        $this->forgetModeratorCaches();
    }

    public function detachFromPublicPlaylist(Playlist $playlist, User $user): void
    {
        $this->assertPublicPlaylist($playlist);
        $this->assertNotPlaylistOwner($playlist, $user);

        $playlist->moderators()->detach($user->id);

        $this->forgetModeratorCaches();
    }

    /**
     * @return array{
     *     moderators: list<array<string, mixed>>,
     *     stats: array<string, int>,
     *     coverage: array<string, mixed>
     * }
     */
    private function snapshot(): array
    {
        return Cache::remember(
            self::SNAPSHOT_CACHE_KEY,
            self::SNAPSHOT_CACHE_SECONDS,
            fn (): array => $this->buildSnapshot(),
        );
    }

    /**
     * @return array{
     *     moderators: list<array<string, mixed>>,
     *     stats: array<string, int>,
     *     coverage: array<string, mixed>
     * }
     */
    private function buildSnapshot(): array
    {
        $inactivityThreshold = now()->subMonths(self::INACTIVITY_MONTHS);
        $moderatorIds = $this->publicModeratorIds();

        if ($moderatorIds->isEmpty()) {
            $coverage = $this->coverageOverview($inactivityThreshold);

            return [
                'moderators' => [],
                'stats' => $this->statsFrom($coverage, 0, 0),
                'coverage' => $coverage,
            ];
        }

        $users = User::query()
            ->whereIn('id', $moderatorIds)
            ->get(['id', 'name', 'photo_path', 'updated_at'])
            ->keyBy('id');

        $activities = $this->batchActivityMetrics($moderatorIds);
        $roomCounts = $this->assignmentCounts(Room::class, $moderatorIds);
        $playlistCounts = $this->assignmentCounts(Playlist::class, $moderatorIds);

        $moderators = $moderatorIds
            ->map(function (int $userId) use ($users, $activities, $roomCounts, $playlistCounts, $inactivityThreshold) {
                $user = $users->get($userId);

                if ($user === null) {
                    return null;
                }

                return $this->formatModeratorSummary(
                    $user,
                    $activities[$userId] ?? [],
                    (int) ($roomCounts[$userId] ?? 0),
                    (int) ($playlistCounts[$userId] ?? 0),
                    $inactivityThreshold,
                );
            })
            ->filter()
            ->sortBy([
                fn (array $moderator) => $moderator['last_activity_at'] === null ? 0 : 1,
                fn (array $moderator) => $moderator['last_activity_at'] ?? '',
            ])
            ->values()
            ->all();

        $coverage = $this->coverageOverview($inactivityThreshold);
        $inactiveCount = collect($moderators)->where('is_inactive', true)->count();

        return [
            'moderators' => $moderators,
            'stats' => $this->statsFrom($coverage, count($moderators), $inactiveCount),
            'coverage' => $coverage,
        ];
    }

    /**
     * @return Collection<int, int>
     */
    private function publicModeratorIds(): Collection
    {
        $roomModeratorIds = DB::table('moderables')
            ->join('rooms', function ($join) {
                $join->on('rooms.id', '=', 'moderables.moderable_id')
                    ->where('moderables.moderable_type', Room::class)
                    ->where('rooms.is_public', true)
                    ->whereNull('rooms.deleted_at');
            })
            ->distinct()
            ->pluck('moderables.user_id');

        $playlistModeratorIds = DB::table('moderables')
            ->join('playlists', function ($join) {
                $join->on('playlists.id', '=', 'moderables.moderable_id')
                    ->where('moderables.moderable_type', Playlist::class)
                    ->where('playlists.is_public', true);
            })
            ->distinct()
            ->pluck('moderables.user_id');

        return $roomModeratorIds
            ->merge($playlistModeratorIds)
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();
    }

    /**
     * @param  Collection<int, int>  $userIds
     * @return array<int, array<string, mixed>>
     */
    private function batchActivityMetrics(Collection $userIds): array
    {
        if ($userIds->isEmpty()) {
            return [];
        }

        $ids = $userIds->values()->all();
        $userClass = User::class;

        $logins = DB::table('user_levels')
            ->whereIn('user_id', $ids)
            ->pluck('last_login_date', 'user_id');

        $scores = DB::table('total_scores')
            ->selectRaw('totalscorable_id, MAX(updated_at) as last_at')
            ->where('totalscorable_type', $userClass)
            ->whereIn('totalscorable_id', $ids)
            ->groupBy('totalscorable_id')
            ->pluck('last_at', 'totalscorable_id');

        $messages = DB::table('messages')
            ->selectRaw('user_id, MAX(created_at) as last_at')
            ->whereIn('user_id', $ids)
            ->groupBy('user_id')
            ->pluck('last_at', 'user_id');

        $bans = DB::table('bans')
            ->selectRaw('created_by_id, MAX(created_at) as last_at')
            ->where('created_by_type', $userClass)
            ->whereIn('created_by_id', $ids)
            ->groupBy('created_by_id')
            ->pluck('last_at', 'created_by_id');

        $tracks = DB::table('tracks')
            ->join('playlists', function ($join) {
                $join->on('playlists.id', '=', 'tracks.playlist_id')
                    ->where('playlists.is_public', true);
            })
            ->selectRaw('tracks.user_id, MAX(tracks.created_at) as last_at')
            ->whereIn('tracks.user_id', $ids)
            ->groupBy('tracks.user_id')
            ->pluck('last_at', 'tracks.user_id');

        $localTracks = DB::table('local_tracks')
            ->selectRaw('user_id, MAX(created_at) as last_at')
            ->whereIn('user_id', $ids)
            ->groupBy('user_id')
            ->pluck('last_at', 'user_id');

        $metrics = [];

        foreach ($ids as $userId) {
            $metrics[$userId] = [
                'last_login_at' => $logins[$userId] ?? null,
                'last_score_at' => $scores[$userId] ?? null,
                'last_message_at' => $messages[$userId] ?? null,
                'last_ban_at' => $bans[$userId] ?? null,
                'last_track_added_at' => $tracks[$userId] ?? null,
                'last_local_track_at' => $localTracks[$userId] ?? null,
            ];
        }

        return $metrics;
    }

    /**
     * @param  Collection<int, int>  $userIds
     * @return Collection<int, int>
     */
    private function assignmentCounts(string $moderableType, Collection $userIds): Collection
    {
        if ($userIds->isEmpty()) {
            return collect();
        }

        $table = $moderableType === Room::class ? 'rooms' : 'playlists';

        return DB::table('moderables')
            ->join($table, function ($join) use ($moderableType, $table) {
                $join->on("{$table}.id", '=', 'moderables.moderable_id')
                    ->where('moderables.moderable_type', $moderableType)
                    ->where("{$table}.is_public", true);

                if ($moderableType === Room::class) {
                    $join->whereNull("{$table}.deleted_at");
                }
            })
            ->selectRaw('moderables.user_id, COUNT(*) as total')
            ->whereIn('moderables.user_id', $userIds)
            ->groupBy('moderables.user_id')
            ->pluck('total', 'moderables.user_id')
            ->map(fn ($count) => (int) $count);
    }

    /**
     * @param  array<string, mixed>  $activity
     * @return array<string, mixed>
     */
    private function formatModeratorSummary(
        User $user,
        array $activity,
        int $roomsCount,
        int $playlistsCount,
        Carbon $inactivityThreshold,
    ): array {
        $lastActivity = collect([
            $user->updated_at,
            isset($activity['last_login_at']) ? Carbon::parse($activity['last_login_at'])->startOfDay() : null,
            isset($activity['last_score_at']) ? Carbon::parse($activity['last_score_at']) : null,
            isset($activity['last_message_at']) ? Carbon::parse($activity['last_message_at']) : null,
            isset($activity['last_ban_at']) ? Carbon::parse($activity['last_ban_at']) : null,
            isset($activity['last_track_added_at']) ? Carbon::parse($activity['last_track_added_at']) : null,
            isset($activity['last_local_track_at']) ? Carbon::parse($activity['last_local_track_at']) : null,
        ])->filter()->max();

        $lastActivityAt = $lastActivity ? Carbon::parse($lastActivity) : null;

        return [
            'id' => $user->id,
            'name' => $user->name,
            'photo' => $user->photo,
            'profile_url' => route('moderation.users.show', $user),
            'last_connection_at' => isset($activity['last_login_at'])
                ? Carbon::parse($activity['last_login_at'])->startOfDay()->toIso8601String()
                : $user->updated_at?->toIso8601String(),
            'last_score_at' => $activity['last_score_at'] ?? null,
            'last_message_at' => $activity['last_message_at'] ?? null,
            'last_ban_at' => $activity['last_ban_at'] ?? null,
            'last_track_added_at' => $activity['last_track_added_at'] ?? null,
            'last_local_track_at' => $activity['last_local_track_at'] ?? null,
            'last_activity_at' => $lastActivityAt?->toIso8601String(),
            'days_since_activity' => $lastActivityAt ? (int) $lastActivityAt->diffInDays(now()) : null,
            'is_inactive' => $lastActivityAt === null || $lastActivityAt->isBefore($inactivityThreshold),
            'rooms_count' => $roomsCount,
            'playlists_count' => $playlistsCount,
        ];
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $rooms
     * @param  Collection<int, array<string, mixed>>  $playlists
     * @return array<string, mixed>
     */
    private function attachAssignments(
        array $moderator,
        Collection $rooms,
        Collection $playlists,
    ): array {
        return [
            ...$moderator,
            'rooms' => $rooms->values()->all(),
            'playlists' => $playlists->values()->all(),
        ];
    }

    /**
     * @return array{
     *     rooms_without_moderators: list<array<string, mixed>>,
     *     playlists_without_moderators: list<array<string, mixed>>,
     *     stale_public_playlists: list<array<string, mixed>>
     * }
     */
    private function coverageOverview(Carbon $inactivityThreshold): array
    {
        return [
            'rooms_without_moderators' => $this->publicRoomsWithoutModerators(),
            'playlists_without_moderators' => $this->publicPlaylistsWithoutModerators(),
            'stale_public_playlists' => $this->stalePublicPlaylists($inactivityThreshold),
        ];
    }

    /**
     * @return array<string, int>
     */
    private function statsFrom(array $coverage, int $totalModerators, int $inactiveModerators): array
    {
        return [
            'total_moderators' => $totalModerators,
            'active_moderators' => max(0, $totalModerators - $inactiveModerators),
            'inactive_moderators' => $inactiveModerators,
            'public_rooms' => Room::query()->isPublic()->whereNull('deleted_at')->count(),
            'public_playlists' => Playlist::query()->isPublic()->count(),
            'rooms_without_moderators' => count($coverage['rooms_without_moderators']),
            'playlists_without_moderators' => count($coverage['playlists_without_moderators']),
            'stale_public_playlists' => count($coverage['stale_public_playlists']),
        ];
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $moderators
     * @return Collection<int, array<string, mixed>>
     */
    private function filterBySearch(Collection $moderators, string $search): Collection
    {
        $term = mb_strtolower(trim($search));

        return $moderators->filter(function (array $moderator) use ($term) {
            if (ctype_digit($term) && (int) $term === (int) $moderator['id']) {
                return true;
            }

            return str_contains(mb_strtolower($moderator['name']), $term);
        });
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function publicRoomsWithoutModerators(): array
    {
        $rows = DB::table('rooms')
            ->leftJoin('moderables', function ($join) {
                $join->on('moderables.moderable_id', '=', 'rooms.id')
                    ->where('moderables.moderable_type', Room::class);
            })
            ->leftJoin('users', 'users.id', '=', 'rooms.user_id')
            ->where('rooms.is_public', true)
            ->whereNull('rooms.deleted_at')
            ->whereNull('moderables.id')
            ->orderBy('rooms.name')
            ->limit(30)
            ->get([
                'rooms.id',
                'rooms.name',
                'users.name as owner_name',
                'rooms.created_at',
            ]);

        return $rows->map(fn ($row) => [
            'id' => (int) $row->id,
            'name' => $row->name,
            'owner_name' => $row->owner_name,
            'created_at' => $row->created_at ? Carbon::parse($row->created_at)->toIso8601String() : null,
        ])->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function publicPlaylistsWithoutModerators(): array
    {
        $rows = DB::table('playlists')
            ->leftJoin('moderables', function ($join) {
                $join->on('moderables.moderable_id', '=', 'playlists.id')
                    ->where('moderables.moderable_type', Playlist::class);
            })
            ->leftJoin('users', 'users.id', '=', 'playlists.user_id')
            ->where('playlists.is_public', true)
            ->whereNull('moderables.id')
            ->orderBy('playlists.name')
            ->limit(30)
            ->get([
                'playlists.id',
                'playlists.name',
                'users.name as owner_name',
                'playlists.updated_at',
            ]);

        return $rows->map(fn ($row) => [
            'id' => (int) $row->id,
            'name' => $row->name,
            'owner_name' => $row->owner_name,
            'updated_at' => $row->updated_at ? Carbon::parse($row->updated_at)->toIso8601String() : null,
        ])->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function stalePublicPlaylists(Carbon $inactivityThreshold): array
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            return Playlist::query()
                ->isPublic()
                ->withMax('tracks as last_track_added_at', 'created_at')
                ->with('owner:id,name')
                ->orderBy('updated_at')
                ->get(['id', 'name', 'user_id', 'updated_at', 'created_at'])
                ->map(function (Playlist $playlist) {
                    $lastActivity = collect([
                        $playlist->updated_at,
                        $playlist->last_track_added_at ? Carbon::parse($playlist->last_track_added_at) : null,
                    ])->filter()->max();

                    return [
                        'playlist' => $playlist,
                        'last_activity_at' => $lastActivity ? Carbon::parse($lastActivity) : null,
                    ];
                })
                ->filter(fn (array $row) => $row['last_activity_at'] === null || $row['last_activity_at']->isBefore($inactivityThreshold))
                ->sortBy(fn (array $row) => $row['last_activity_at']?->timestamp ?? 0)
                ->take(30)
                ->map(fn (array $row) => [
                    'id' => $row['playlist']->id,
                    'name' => $row['playlist']->name,
                    'owner_name' => $row['playlist']->owner?->name,
                    'updated_at' => $row['playlist']->updated_at?->toIso8601String(),
                    'last_track_added_at' => $row['playlist']->last_track_added_at,
                    'last_activity_at' => $row['last_activity_at']?->toIso8601String(),
                ])
                ->values()
                ->all();
        }

        $publicPlaylistIds = DB::table('playlists')
            ->where('is_public', true)
            ->pluck('id');

        if ($publicPlaylistIds->isEmpty()) {
            return [];
        }

        $rows = DB::table('playlists')
            ->leftJoin('users', 'users.id', '=', 'playlists.user_id')
            ->leftJoinSub(
                DB::table('tracks')
                    ->selectRaw('playlist_id, MAX(created_at) AS last_track_added_at')
                    ->whereIn('playlist_id', $publicPlaylistIds)
                    ->groupBy('playlist_id'),
                'playlist_tracks',
                'playlist_tracks.playlist_id',
                '=',
                'playlists.id',
            )
            ->whereIn('playlists.id', $publicPlaylistIds)
            ->whereRaw(
                'COALESCE(
                    GREATEST(playlists.updated_at, playlist_tracks.last_track_added_at),
                    playlists.updated_at,
                    playlists.created_at
                ) < ?',
                [$inactivityThreshold],
            )
            ->orderByRaw('COALESCE(GREATEST(playlists.updated_at, playlist_tracks.last_track_added_at), playlists.updated_at)')
            ->limit(30)
            ->get([
                'playlists.id',
                'playlists.name',
                'users.name as owner_name',
                'playlists.updated_at',
                'playlist_tracks.last_track_added_at',
            ]);

        return $rows->map(function ($row) {
            $lastActivity = collect([
                $row->updated_at ? Carbon::parse($row->updated_at) : null,
                $row->last_track_added_at ? Carbon::parse($row->last_track_added_at) : null,
            ])->filter()->max();

            return [
                'id' => (int) $row->id,
                'name' => $row->name,
                'owner_name' => $row->owner_name,
                'updated_at' => $row->updated_at ? Carbon::parse($row->updated_at)->toIso8601String() : null,
                'last_track_added_at' => $row->last_track_added_at,
                'last_activity_at' => $lastActivity ? Carbon::parse($lastActivity)->toIso8601String() : null,
            ];
        })->all();
    }

    /**
     * @param  Collection<int, int>  $userIds
     * @return Collection<int, Collection<int, array<string, mixed>>>
     */
    private function roomAssignmentsFor(Collection $userIds): Collection
    {
        if ($userIds->isEmpty()) {
            return collect();
        }

        return DB::table('moderables')
            ->join('rooms', function ($join) {
                $join->on('rooms.id', '=', 'moderables.moderable_id')
                    ->where('moderables.moderable_type', Room::class)
                    ->where('rooms.is_public', true)
                    ->whereNull('rooms.deleted_at');
            })
            ->whereIn('moderables.user_id', $userIds)
            ->orderBy('rooms.name')
            ->get([
                'moderables.user_id',
                'rooms.id as room_id',
                'rooms.name as room_name',
                'rooms.user_id as owner_id',
            ])
            ->groupBy('user_id')
            ->map(fn (Collection $rows) => $rows->map(fn ($row) => [
                'id' => (int) $row->room_id,
                'name' => $row->room_name,
                'is_owner' => (int) $row->owner_id === (int) $row->user_id,
            ]));
    }

    /**
     * @param  Collection<int, int>  $userIds
     * @return Collection<int, Collection<int, array<string, mixed>>>
     */
    private function playlistAssignmentsFor(Collection $userIds): Collection
    {
        if ($userIds->isEmpty()) {
            return collect();
        }

        return DB::table('moderables')
            ->join('playlists', function ($join) {
                $join->on('playlists.id', '=', 'moderables.moderable_id')
                    ->where('moderables.moderable_type', Playlist::class)
                    ->where('playlists.is_public', true);
            })
            ->whereIn('moderables.user_id', $userIds)
            ->orderBy('playlists.name')
            ->get([
                'moderables.user_id',
                'playlists.id as playlist_id',
                'playlists.name as playlist_name',
                'playlists.user_id as owner_id',
            ])
            ->groupBy('user_id')
            ->map(fn (Collection $rows) => $rows->map(fn ($row) => [
                'id' => (int) $row->playlist_id,
                'name' => $row->playlist_name,
                'is_owner' => (int) $row->owner_id === (int) $row->user_id,
            ]));
    }

    private function assertPublicRoom(Room $room): void
    {
        if (! $room->is_public) {
            throw ValidationException::withMessages([
                'room' => __('Moderation moderator room is not public'),
            ]);
        }
    }

    private function assertPublicPlaylist(Playlist $playlist): void
    {
        if (! $playlist->is_public) {
            throw ValidationException::withMessages([
                'playlist' => __('Moderation moderator playlist is not public'),
            ]);
        }
    }

    private function assertNotRoomOwner(Room $room, User $user): void
    {
        if ($room->user_id === $user->id) {
            throw ValidationException::withMessages([
                'user' => __('Moderation moderator cannot revoke room owner'),
            ]);
        }
    }

    private function assertNotPlaylistOwner(Playlist $playlist, User $user): void
    {
        if ($playlist->user_id === $user->id) {
            throw ValidationException::withMessages([
                'user' => __('Moderation moderator cannot revoke playlist owner'),
            ]);
        }
    }

    private function forgetModeratorCaches(): void
    {
        Cache::forget(self::SNAPSHOT_CACHE_KEY);
        Cache::forget('public-moderators');
    }
}
