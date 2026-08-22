<?php

namespace App\Services\Profiles;

use App\Models\AnswerType;
use App\Models\MinigameScore;
use App\Models\Room;
use App\Models\RoundStanding;
use App\Models\Track;
use App\Models\User;
use App\Services\Donations\DonationGoalService;
use App\Services\Donations\DonorPerkService;
use App\Services\Minigames\MinigameScoreService;
use App\Services\Rankings\GlobalLeaderboardService;
use App\Services\Rankings\MinigameLeaderboardService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class ProfilePageService
{
    public function __construct(
        private DonationGoalService $donationGoal,
        private DonorPerkService $donorPerks,
        private GlobalLeaderboardService $leaderboard,
        private ProfileBadgeService $badges,
        private MinigameScoreService $minigameScores,
        private MinigameLeaderboardService $minigameLeaderboard,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function header(User $user): array
    {
        $user->loadMissing(['userLevel', 'team']);

        $totals = Cache::remember("user_profile_totals_{$user->id}", 1800, function () use ($user) {
            $row = $user->totalScores()
                ->leftJoin('rooms', 'total_scores.room_id', '=', 'rooms.id')
                ->selectRaw('
                    COALESCE(SUM(total_scores.score), 0) as total_score,
                    COALESCE(SUM(CASE WHEN rooms.is_public = 1 THEN total_scores.score ELSE 0 END), 0) as total_public_score,
                    COALESCE(SUM(CASE WHEN rooms.is_public = 0 THEN total_scores.score ELSE 0 END), 0) as total_private_score
                ')
                ->first();

            return [
                'total_score' => (float) ($row->total_score ?? 0),
                'total_public_score' => (float) ($row->total_public_score ?? 0),
                'total_private_score' => (float) ($row->total_private_score ?? 0),
            ];
        });

        $stats = Cache::remember("user_profile_stats_{$user->id}", 1800, function () use ($user) {
            return [
                'rooms' => $user->moderatedRooms()->count(),
                'playlists' => $user->moderatedPlaylists()->count(),
                'bookmarks' => $user->bookmarkedRooms()->count(),
            ];
        });

        $userLevel = $user->userLevel;

        return $this->donorPerks->enrichUserPayload([
            'id' => $user->id,
            'name' => $user->name,
            'photo' => $user->photo,
            'team' => $user->team ? [
                'id' => $user->team->id,
                'name' => $user->team->name,
                'photo' => $user->team->photo,
            ] : null,
            'level' => $userLevel?->level ?? 1,
            'current_xp' => $userLevel?->current_xp ?? 0,
            'xp_for_next_level' => $userLevel?->xp_for_next_level ?? 100,
            'total_xp' => $userLevel?->total_xp ?? 0,
            'level_metrics' => $this->levelMetrics($user, $userLevel),
            'created_at_from_now' => $user->created_at->diffForHumans(null, true),
            'elo' => $user->elo ?? 1500,
            'total_score' => $totals['total_score'],
            'total_public_score' => $totals['total_public_score'],
            'total_private_score' => $totals['total_private_score'],
            'stats' => $stats,
            'donation_summary' => $this->donationGoal->userDonationSummary($user),
        ], $user);
    }

    public function scores(User $user, int $page = 1, int $perPage = 10, string $sort = 'updated_at', string $direction = 'desc'): LengthAwarePaginator
    {
        $allowedSorts = ['updated_at', 'score', 'room'];
        $sort = in_array($sort, $allowedSorts, true) ? $sort : 'updated_at';
        $direction = $direction === 'asc' ? 'asc' : 'desc';
        $publicRoomIds = Room::cachedPublicIds();

        $query = $user->totalScores()
            ->whereIn('room_id', $publicRoomIds)
            ->select('total_scores.id', 'total_scores.score', 'total_scores.room_id', 'total_scores.updated_at');

        if ($sort === 'room') {
            $query->join('rooms', 'total_scores.room_id', '=', 'rooms.id')
                ->orderBy('rooms.name', $direction)
                ->select('total_scores.id', 'total_scores.score', 'total_scores.room_id', 'total_scores.updated_at');
        } elseif ($sort === 'score') {
            $query->orderBy('total_scores.score', $direction);
        } else {
            $query->orderBy('total_scores.updated_at', $direction);
        }

        $paginator = $query->paginate($perPage, ['*'], 'page', $page);

        $rooms = Room::query()
            ->whereIn('id', $paginator->pluck('room_id')->unique())
            ->select('id', 'name', 'slug', 'photo_path')
            ->get()
            ->keyBy('id');

        return $paginator->through(function ($score) use ($rooms) {
            $room = $rooms->get($score->room_id);

            return [
                'id' => $score->id,
                'score' => $score->score,
                'room' => $room ? [
                    'id' => $room->id,
                    'name' => $room->name,
                    'slug' => $room->slug,
                    'photo' => $room->photo,
                ] : null,
                'updated_at' => $score->updated_at->format('d/m/Y'),
            ];
        });
    }

    public function likes(User $user, int $page = 1, int $perPage = 10): LengthAwarePaginator
    {
        $answerTypeIds = Cache::remember('profile_like_answer_type_ids', 3600, fn () => AnswerType::query()
            ->whereIn('name', ['Title', 'Artist'])
            ->pluck('id')
            ->all());

        return Track::query()
            ->join('votes', function ($join) use ($user) {
                $join->on('tracks.id', '=', 'votes.votable_id')
                    ->where('votes.votable_type', Track::class)
                    ->where('votes.user_id', $user->id)
                    ->where('votes.votes', 1);
            })
            ->select('tracks.id', 'tracks.artwork_url', 'tracks.created_at')
            ->with(['answers' => function ($query) use ($answerTypeIds) {
                $query->whereIn('answer_type_id', $answerTypeIds)->with('type:id,name');
            }])
            ->orderByDesc('tracks.created_at')
            ->paginate($perPage, ['*'], 'page', $page)
            ->through(fn (Track $track) => [
                'id' => $track->id,
                'artwork_url' => $track->artwork_url,
                'title' => $this->answerValue($track, 'Title'),
                'artist' => $this->answerValue($track, 'Artist'),
            ]);
    }

    public function bookmarks(User $user, int $page = 1, int $perPage = 10): LengthAwarePaginator
    {
        return $user->bookmarkedRooms()
            ->with(['category:id,name'])
            ->select('rooms.id', 'rooms.name', 'rooms.slug', 'rooms.photo_path', 'rooms.category_id')
            ->orderByDesc('bookmarks.created_at')
            ->paginate($perPage, ['*'], 'page', $page)
            ->through(fn (Room $room) => [
                'id' => $room->id,
                'name' => $room->name,
                'slug' => $room->slug,
                'photo' => $room->photo,
                'category' => $room->category ? [
                    'id' => $room->category->id,
                    'name' => $room->category->name,
                ] : null,
            ]);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function donationHistory(User $user): array
    {
        return $this->donationGoal->userDonationHistory($user);
    }

    /**
     * @return array<string, mixed>
     */
    public function highlights(User $user): array
    {
        return Cache::remember("user_profile_highlights_v2_{$user->id}", 1800, function () use ($user) {
            $user->loadMissing('userLevel');

            $context = $this->leaderboard->userContext($user, 'elo');
            $entryStats = $context['entry']['stats'] ?? [];
            $eloRank = $context['position'] ?? null;
            $isSupporter = $this->donorPerks->activePerksForUser($user) !== [];

            return [
                'performance' => $this->performanceSummary($user, $entryStats),
                'rank' => [
                    'sort' => 'elo',
                    'position' => $eloRank,
                    'rankings_url' => route('rankings.index', ['sort' => 'elo']),
                ],
                'top_rooms' => $this->topRooms($user),
                'badges' => $this->badges->forUser($user, $eloRank, $isSupporter),
            ];
        });
    }

    /**
     * @return array<string, mixed>
     */
    public function minigames(User $user, int $page = 1, int $perPage = 15): array
    {
        $totals = $this->minigameScores->getTotalsByTypeForUser($user);
        $minigameContext = $this->minigameLeaderboard->userContext($user);

        $games = collect($this->minigameDefinitions())
            ->map(fn (array $definition) => [
                ...$definition,
                'total' => $totals[$definition['type']] ?? 0,
            ])
            ->values()
            ->all();

        $history = MinigameScore::query()
            ->where('user_id', $user->id)
            ->select('id', 'game_type', 'score', 'created_at')
            ->orderByDesc('created_at')
            ->paginate($perPage, ['*'], 'page', $page)
            ->through(fn (MinigameScore $row) => [
                'id' => $row->id,
                'game_type' => $row->game_type,
                'label_key' => $this->minigameLabelKey($row->game_type),
                'score' => $row->score,
                'played_at' => $row->created_at->format('d/m/Y H:i'),
            ]);

        return [
            'games' => $games,
            'history' => $history,
            'rankings_url' => route('rankings.minigames'),
            'user_rank' => $minigameContext['position'],
            'user_total_score' => $minigameContext['score'],
        ];
    }

    /**
     * @return list<array{date: string, total_score: float}>
     */
    public function scoreEvolution(User $user): array
    {
        return Cache::remember("user_score_evolution_{$user->id}", 3600, function () use ($user) {
            $scoreHistory = $user->scores()
                ->selectRaw('DATE(created_at) as date, SUM(score) as daily_score')
                ->groupByRaw('DATE(created_at)')
                ->orderBy('date')
                ->limit(365)
                ->get();

            if ($scoreHistory->isEmpty()) {
                return [];
            }

            $cumulative = 0;

            return $scoreHistory->map(function ($row) use (&$cumulative) {
                $cumulative += (float) $row->daily_score;

                return [
                    'date' => (string) $row->date,
                    'total_score' => round($cumulative, 1),
                ];
            })->values()->all();
        }) ?: [];
    }

    /**
     * @param  array<string, mixed>  $leaderboardStats
     * @return array<string, int|float>
     */
    private function performanceSummary(User $user, array $leaderboardStats = []): array
    {
        $userLevel = $user->userLevel;

        $bestWinStreak = (int) RoundStanding::query()
            ->join('rooms', 'round_standings.room_id', '=', 'rooms.id')
            ->where('round_standings.user_id', $user->id)
            ->where('rooms.is_public', true)
            ->max('round_standings.win_streak');

        return [
            'rounds_played' => (int) ($leaderboardStats['rounds_played'] ?? $userLevel?->rounds_played_count ?? 0),
            'best_round_score' => (float) ($leaderboardStats['best_round_score'] ?? $userLevel?->best_round_score ?? 0),
            'consecutive_days_streak' => (int) ($userLevel?->consecutive_days_streak ?? 0),
            'best_win_streak' => max($bestWinStreak, (int) ($leaderboardStats['best_win_streak'] ?? 0)),
            'week_score' => (float) ($leaderboardStats['week_score'] ?? 0),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function topRooms(User $user, int $limit = 3): array
    {
        $publicRoomIds = Room::cachedPublicIds();

        $scores = $user->totalScores()
            ->whereIn('room_id', $publicRoomIds)
            ->select('total_scores.score', 'total_scores.room_id', 'total_scores.updated_at')
            ->orderByDesc('total_scores.score')
            ->limit($limit)
            ->get();

        if ($scores->isEmpty()) {
            return [];
        }

        $rooms = Room::query()
            ->whereIn('id', $scores->pluck('room_id'))
            ->select('id', 'name', 'slug', 'photo_path')
            ->get()
            ->keyBy('id');

        return $scores->map(function ($score) use ($rooms) {
            $room = $rooms->get($score->room_id);

            if ($room === null) {
                return null;
            }

            return [
                'score' => (float) $score->score,
                'updated_at' => $score->updated_at->format('d/m/Y'),
                'room' => [
                    'id' => $room->id,
                    'name' => $room->name,
                    'slug' => $room->slug,
                    'photo' => $room->photo,
                ],
            ];
        })->filter()->values()->all();
    }

    /**
     * @return list<array{type: string, label_key: string, play_url: string}>
     */
    private function minigameDefinitions(): array
    {
        return [
            [
                'type' => MinigameScore::TYPE_QUIZ,
                'label_key' => 'Minigame quiz label',
                'play_url' => route('minigames.quiz.play'),
            ],
            [
                'type' => MinigameScore::TYPE_WHO_SANG,
                'label_key' => 'Who sang?',
                'play_url' => route('minigames.who_sang.play'),
            ],
            [
                'type' => MinigameScore::TYPE_ANAGRAM,
                'label_key' => 'Anagram',
                'play_url' => route('minigames.anagram.play'),
            ],
            [
                'type' => MinigameScore::TYPE_FIRST_LETTER,
                'label_key' => 'First letter',
                'play_url' => route('minigames.first_letter.play'),
            ],
            [
                'type' => MinigameScore::TYPE_ALBUM_COVER,
                'label_key' => 'Album cover',
                'play_url' => route('minigames.album_cover.play'),
            ],
        ];
    }

    private function minigameLabelKey(string $gameType): string
    {
        return match ($gameType) {
            MinigameScore::TYPE_QUIZ => 'Minigame quiz label',
            MinigameScore::TYPE_WHO_SANG => 'Who sang?',
            MinigameScore::TYPE_ANAGRAM => 'Anagram',
            MinigameScore::TYPE_FIRST_LETTER => 'First letter',
            MinigameScore::TYPE_ALBUM_COVER => 'Album cover',
            default => 'Mini-games',
        };
    }

    /**
     * @return array<string, mixed>|null
     */
    private function levelMetrics(User $user, mixed $userLevel): ?array
    {
        if ($userLevel === null) {
            return null;
        }

        return [
            'score_public_rooms' => $userLevel->score_public_rooms ?? 0,
            'minigame_scores_total' => $userLevel->minigame_scores_total ?? 0,
            'seniority_months' => $userLevel->months_seniority ?? 0,
            'consecutive_days_streak' => $userLevel->consecutive_days_streak ?? 0,
            'rooms_created_count' => $userLevel->rooms_created_count ?? 0,
            'playlists_created_count' => $userLevel->playlists_created_count ?? 0,
            'tracks_liked_count' => $userLevel->tracks_liked_count ?? 0,
            'has_team' => $user->team !== null,
        ];
    }

    private function answerValue(Track $track, string $typeName): ?string
    {
        $answer = $track->answers->first(fn ($answer) => $answer->type?->name === $typeName);

        return $answer?->value;
    }
}
