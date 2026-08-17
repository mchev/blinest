<?php

namespace App\Http\Controllers;

use App\Models\MinigameScore;
use App\Models\Room;
use App\Models\RoundStanding;
use App\Models\Team;
use App\Models\TotalScore;
use App\Models\User;
use App\Models\UserLevel;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Laravel\Head\Facades\Head;

class RankingController extends Controller
{
    public function index()
    {
        return redirect()->route('rankings.level');
    }

    public function byLevel()
    {
        Head::title(__('Rankings').' - '.__('Level'));

        $user = Auth::user();

        // Paginated by Level
        $topByLevel = UserLevel::query()
            ->with(['user'])
            ->orderByDesc('level')
            ->orderByDesc('total_xp')
            ->paginate(50)
            ->through(function ($userLevel) {
                $user = $userLevel->user;

                return [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'photo' => $user->photo,
                        'elo' => $user->elo ?? 1500,
                        'userLevel' => $userLevel,
                    ],
                    'userLevel' => $userLevel,
                    'level' => $userLevel->level,
                ];
            });

        // User position
        $userPosition = null;
        if ($user && $user->userLevel) {
            $userLevelPosition = User::query()
                ->whereHas('userLevel')
                ->join('user_levels', 'users.id', '=', 'user_levels.user_id')
                ->where(function ($query) use ($user) {
                    $query->where('user_levels.level', '>', $user->userLevel->level)
                        ->orWhere(function ($q) use ($user) {
                            $q->where('user_levels.level', '=', $user->userLevel->level)
                                ->where('user_levels.total_xp', '>', $user->userLevel->total_xp);
                        });
                })
                ->count() + 1;
            $userPosition = $userLevelPosition;
        }

        return Inertia::render('Rankings/Level', [
            'topByLevel' => $topByLevel,
            'userPosition' => $userPosition,
        ]);
    }

    public function byScore()
    {
        Head::title(__('Rankings').' - '.__('Score'));

        $user = Auth::user();

        $paginatedScores = $this->publicUserScoresQuery()
            ->select('total_scores.totalscorable_id')
            ->selectRaw('ROUND(SUM(total_scores.score), 1) as total_score')
            ->groupBy('total_scores.totalscorable_id')
            ->orderByDesc('total_score')
            ->paginate(50);

        // Get user IDs from paginated results
        $userIds = $paginatedScores->pluck('totalscorable_id');

        // Load users with their levels
        $users = User::with('userLevel')
            ->whereIn('id', $userIds)
            ->get()
            ->keyBy('id');

        // Map scores to include user data
        $mappedScores = $paginatedScores->getCollection()->map(function ($score) use ($users) {
            $user = $users->get($score->totalscorable_id);

            if (! $user) {
                return null;
            }

            return [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'photo' => $user->photo,
                    'elo' => $user->elo ?? 1500,
                    'userLevel' => $user->userLevel,
                ],
                'total_score' => (float) $score->total_score,
            ];
        })->filter()->values();

        // Create a new paginator with the mapped data
        $topByScore = new LengthAwarePaginator(
            $mappedScores,
            $paginatedScores->total(),
            $paginatedScores->perPage(),
            $paginatedScores->currentPage(),
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );

        // User position
        $userPosition = null;
        $userScore = null;
        if ($user) {
            $userScore = $this->publicUserScoresQuery()
                ->where('total_scores.totalscorable_id', $user->id)
                ->selectRaw('ROUND(SUM(total_scores.score), 1) as total_score')
                ->groupBy('total_scores.totalscorable_id')
                ->first();

            if ($userScore) {
                $userPosition = $this->countUsersWithHigherPublicScore((float) $userScore->total_score) + 1;
            }
        }

        return Inertia::render('Rankings/Score', [
            'topByScore' => $topByScore,
            'userPosition' => $userPosition,
            'userScore' => $userScore ? (float) $userScore->total_score : 0,
        ]);
    }

    public function byWeek()
    {
        Head::title(__('Rankings').' - '.__('Top Week'));

        $user = Auth::user();

        // Paginated Week (last 7 days) - Utiliser round_standings au lieu de scores
        // First, get the total count without ORDER BY
        $total = RoundStanding::query()
            ->join('rounds', 'round_standings.round_id', '=', 'rounds.id')
            ->join('rooms', 'round_standings.room_id', '=', 'rooms.id')
            ->where('rooms.is_public', true)
            ->where('round_standings.created_at', '>=', now()->subDays(7))
            ->groupBy('round_standings.user_id')
            ->get()
            ->count();

        // Now get the paginated results with ORDER BY
        $perPage = 50;
        $currentPage = request()->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;

        $userIdsPaginated = RoundStanding::query()
            ->join('rounds', 'round_standings.round_id', '=', 'rounds.id')
            ->join('rooms', 'round_standings.room_id', '=', 'rooms.id')
            ->where('rooms.is_public', true)
            ->where('round_standings.created_at', '>=', now()->subDays(7))
            ->selectRaw('round_standings.user_id, ROUND(SUM(round_standings.total_score), 1) as total_score')
            ->groupBy('round_standings.user_id')
            ->orderByDesc('total_score')
            ->offset($offset)
            ->limit($perPage)
            ->get();

        $users = User::with('userLevel')
            ->whereIn('id', $userIdsPaginated->pluck('user_id'))
            ->get()
            ->keyBy('id');

        $topWeek = $userIdsPaginated->map(function ($standing) use ($users) {
            $user = $users->get($standing->user_id);

            return [
                'user' => $user ? [
                    'id' => $user->id,
                    'name' => $user->name,
                    'photo' => $user->photo,
                    'elo' => $user->elo ?? 1500,
                    'userLevel' => $user->userLevel,
                ] : null,
                'total_score' => (float) $standing->total_score,
            ];
        })->filter(fn ($item) => $item['user'] !== null);

        $topWeek = new LengthAwarePaginator(
            $topWeek,
            $total,
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // User position
        $userPosition = null;
        $userWeekScore = null;
        if ($user) {
            $userWeekScore = RoundStanding::query()
                ->join('rounds', 'round_standings.round_id', '=', 'rounds.id')
                ->join('rooms', 'round_standings.room_id', '=', 'rooms.id')
                ->where('rooms.is_public', true)
                ->where('round_standings.user_id', $user->id)
                ->where('round_standings.created_at', '>=', now()->subDays(7))
                ->selectRaw('ROUND(SUM(round_standings.total_score), 1) as total_score')
                ->first();

            if ($userWeekScore) {
                $userWeekPosition = RoundStanding::query()
                    ->join('rounds', 'round_standings.round_id', '=', 'rounds.id')
                    ->join('rooms', 'round_standings.room_id', '=', 'rooms.id')
                    ->where('rooms.is_public', true)
                    ->where('round_standings.created_at', '>=', now()->subDays(7))
                    ->selectRaw('round_standings.user_id, ROUND(SUM(round_standings.total_score), 1) as total_score')
                    ->groupBy('round_standings.user_id')
                    ->havingRaw('ROUND(SUM(round_standings.total_score), 1) > ?', [$userWeekScore->total_score])
                    ->count() + 1;
                $userPosition = $userWeekPosition;
            }
        }

        return Inertia::render('Rankings/Week', [
            'topWeek' => $topWeek,
            'userPosition' => $userPosition,
            'userWeekScore' => $userWeekScore ? (float) $userWeekScore->total_score : 0,
        ]);
    }

    public function byElo()
    {
        Head::title(__('Rankings').' - '.__('ELO'));

        $user = Auth::user();

        // Paginated by ELO
        $topByElo = User::query()
            ->whereNotNull('elo')
            ->with('userLevel')
            ->orderByDesc('elo')
            ->paginate(50)
            ->through(function ($user) {
                return [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'photo' => $user->photo,
                        'elo' => $user->elo ?? 1500,
                        'userLevel' => $user->userLevel,
                    ],
                    'elo' => $user->elo ?? 1500,
                ];
            });

        // User position
        $userPosition = null;
        if ($user) {
            $userElo = $user->elo ?? 1500;
            $userEloPosition = User::query()
                ->whereNotNull('elo')
                ->where('elo', '>', $userElo)
                ->count() + 1;
            $userPosition = $userEloPosition;
        }

        return Inertia::render('Rankings/Elo', [
            'topByElo' => $topByElo,
            'userPosition' => $userPosition,
            'userElo' => $user ? ($user->elo ?? 1500) : 1500,
        ]);
    }

    public function byTeams()
    {
        Head::title(__('Rankings').' - '.__('Teams'));

        $user = Auth::user();

        // Paginated Teams
        $topTeams = TotalScore::query()
            ->with('team')
            ->select('totalscorable_id')
            ->selectRaw('ROUND(SUM(score), 1) as total_score')
            ->where('totalscorable_type', Team::class)
            ->join('teams', 'teams.id', '=', 'total_scores.totalscorable_id')
            ->join('rooms', 'rooms.id', '=', 'total_scores.room_id')
            ->where('rooms.is_public', true)
            ->whereNull('teams.deleted_at')
            ->groupBy('totalscorable_id')
            ->orderByDesc('total_score')
            ->paginate(50);

        // User team position
        $userPosition = null;
        $userTeamScore = null;
        if ($user && $user->team) {
            $userTeamScore = TotalScore::query()
                ->where('totalscorable_type', Team::class)
                ->where('totalscorable_id', $user->team->id)
                ->join('rooms', 'total_scores.room_id', '=', 'rooms.id')
                ->where('rooms.is_public', true)
                ->selectRaw('ROUND(SUM(score), 1) as total_score')
                ->groupBy('totalscorable_id')
                ->first();

            if ($userTeamScore) {
                $userTeamPosition = TotalScore::query()
                    ->where('totalscorable_type', Team::class)
                    ->join('teams', 'teams.id', '=', 'total_scores.totalscorable_id')
                    ->join('rooms', 'rooms.id', '=', 'total_scores.room_id')
                    ->where('rooms.is_public', true)
                    ->whereNull('teams.deleted_at')
                    ->groupBy('totalscorable_id')
                    ->selectRaw('ROUND(SUM(score), 1) as total_score')
                    ->havingRaw('ROUND(SUM(score), 1) > ?', [$userTeamScore->total_score])
                    ->count() + 1;
                $userPosition = $userTeamPosition;
            }
        }

        return Inertia::render('Rankings/Teams', [
            'topTeams' => $topTeams,
            'userPosition' => $userPosition,
            'userTeamScore' => $userTeamScore ? (float) $userTeamScore->total_score : 0,
        ]);
    }

    // Room Podium
    public function roomScores(Room $room)
    {
        $scores = Cache::remember($room->slug.'-scores', now()->addMinutes(10), fn () => [
            'week' => $room->weekUsersScores()->with('user.userLevel')->get(),
            // 'month' => $room->monthUsersScores,
            'lifetime' => $room->lifetimeUsersScores()->with('user.userLevel')->get(),
            'teams' => $room->lifetimeTeamsScores,
            'user' => [
                'week' => Auth::user()->weekScoreByRoom($room)->first(),
                // 'month' => Auth::user()->monthScoreByRoom($room)->first(),
                'lifetime' => TotalScore::byUsers()->where('room_id', $room->id)->where('totalscorable_id', Auth::user()->id)->first(),
                'team' => Auth::user()?->team?->scoreByRoom($room)->first(),
            ],
        ]);

        return response()->json($scores);
    }

    public function byMinigames()
    {
        Head::title(__('Rankings').' - '.__('Mini-games'));

        $user = Auth::user();

        $paginated = MinigameScore::query()
            ->selectRaw('user_id, sum(score) as total_score')
            ->groupBy('user_id')
            ->orderByDesc('total_score')
            ->paginate(50);

        $userIds = $paginated->pluck('user_id');
        $users = User::with('userLevel')
            ->whereIn('id', $userIds)
            ->get()
            ->keyBy('id');

        $mapped = $paginated->getCollection()->map(function ($row) use ($users) {
            $user = $users->get($row->user_id);
            if (! $user) {
                return null;
            }

            return [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'photo' => $user->photo,
                    'elo' => $user->elo ?? 1500,
                    'userLevel' => $user->userLevel,
                ],
                'total_score' => (int) $row->total_score,
            ];
        })->filter()->values();

        $topByMinigames = new LengthAwarePaginator(
            $mapped,
            $paginated->total(),
            $paginated->perPage(),
            $paginated->currentPage(),
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $userPosition = null;
        $userScore = null;
        if ($user) {
            $userScore = (int) MinigameScore::query()
                ->where('user_id', $user->id)
                ->sum('score');

            $userPosition = MinigameScore::query()
                ->fromSub(
                    MinigameScore::query()
                        ->selectRaw('user_id, sum(score) as total_score')
                        ->groupBy('user_id'),
                    'minigame_totals'
                )
                ->where('total_score', '>', $userScore)
                ->count() + 1;
        }

        return Inertia::render('Rankings/Minigames', [
            'topByMinigames' => $topByMinigames,
            'userPosition' => $userPosition,
            'userScore' => $userScore ?? 0,
        ]);
    }

    // Get user level metrics for modal
    public function userLevelMetrics(User $user)
    {
        $user->loadMissing('userLevel');

        if (! $user->userLevel) {
            return response()->json([
                'level' => 1,
                'current_xp' => 0,
                'xp_for_next_level' => 100,
                'total_xp' => 0,
                'level_metrics' => null,
            ]);
        }

        $userLevel = $user->userLevel;

        return response()->json([
            'level' => $userLevel->level,
            'current_xp' => $userLevel->current_xp,
            'xp_for_next_level' => $userLevel->xp_for_next_level,
            'total_xp' => $userLevel->total_xp,
            'level_metrics' => [
                'score_public_rooms' => $userLevel->score_public_rooms ?? 0,
                'minigame_scores_total' => $userLevel->minigame_scores_total ?? 0,
                'seniority_months' => $userLevel->months_seniority ?? 0,
                'consecutive_days_streak' => $userLevel->consecutive_days_streak ?? 0,
                'rooms_created_count' => $userLevel->rooms_created_count ?? 0,
                'playlists_created_count' => $userLevel->playlists_created_count ?? 0,
                'tracks_liked_count' => $userLevel->tracks_liked_count ?? 0,
                'has_team' => $user->hasTeam(),
            ],
        ]);
    }

    private function publicUserScoresQuery()
    {
        return TotalScore::query()
            ->join('rooms', 'rooms.id', '=', 'total_scores.room_id')
            ->where('rooms.is_public', true)
            ->where('total_scores.totalscorable_type', User::class);
    }

    private function countUsersWithHigherPublicScore(float $totalScore): int
    {
        return TotalScore::query()
            ->fromSub(
                $this->publicUserScoresQuery()
                    ->groupBy('total_scores.totalscorable_id')
                    ->selectRaw('total_scores.totalscorable_id, ROUND(SUM(total_scores.score), 1) as total_score'),
                'public_user_scores'
            )
            ->where('total_score', '>', $totalScore)
            ->count();
    }
}
