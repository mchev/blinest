<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\TotalScore;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class RankingController extends Controller
{
    public function index()
    {
        return redirect()->route('rankings.level');
    }

    public function byLevel()
    {
        $user = Auth::user();

        // Paginated by Level
        $topByLevel = \App\Models\UserLevel::query()
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
                        'userLevel' => $userLevel,
                    ],
                    'userLevel' => $userLevel,
                    'level' => $userLevel->level,
                ];
            });

        // User position
        $userPosition = null;
        if ($user && $user->userLevel) {
            $userLevelPosition = \App\Models\User::query()
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
        $user = Auth::user();

        // Get public room IDs
        $publicRoomIds = \App\Models\Room::where('is_public', true)->pluck('id');

        // Paginated by Score (lifetime, public rooms)
        $paginatedScores = TotalScore::query()
            ->select('totalscorable_id')
            ->selectRaw('ROUND(SUM(score), 1) as total_score')
            ->where('totalscorable_type', \App\Models\User::class)
            ->whereIn('room_id', $publicRoomIds)
            ->groupBy('totalscorable_id')
            ->orderByDesc('total_score')
            ->paginate(50);

        // Get user IDs from paginated results
        $userIds = $paginatedScores->pluck('totalscorable_id');

        // Load users with their levels
        $users = \App\Models\User::with('userLevel')
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
                    'userLevel' => $user->userLevel,
                ],
                'total_score' => (float) $score->total_score,
            ];
        })->filter()->values();

        // Create a new paginator with the mapped data
        $topByScore = new \Illuminate\Pagination\LengthAwarePaginator(
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
            $userScore = TotalScore::query()
                ->where('totalscorable_type', \App\Models\User::class)
                ->where('totalscorable_id', $user->id)
                ->whereIn('room_id', $publicRoomIds)
                ->selectRaw('ROUND(SUM(score), 1) as total_score')
                ->groupBy('totalscorable_id')
                ->first();

            if ($userScore) {
                $userScorePosition = TotalScore::query()
                    ->where('totalscorable_type', \App\Models\User::class)
                    ->whereIn('room_id', $publicRoomIds)
                    ->groupBy('totalscorable_id')
                    ->selectRaw('ROUND(SUM(score), 1) as total_score')
                    ->havingRaw('ROUND(SUM(score), 1) > ?', [$userScore->total_score])
                    ->get()
                    ->count() + 1;
                $userPosition = $userScorePosition;
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
        $user = Auth::user();

        // Paginated Week (last 7 days)
        // First, get the total count without ORDER BY
        $total = \App\Models\Score::query()
            ->join('rounds', 'scores.round_id', '=', 'rounds.id')
            ->join('rooms', 'rounds.room_id', '=', 'rooms.id')
            ->where('rooms.is_public', true)
            ->where('scores.created_at', '>=', now()->subDays(7))
            ->groupBy('scores.user_id')
            ->get()
            ->count();

        // Now get the paginated results with ORDER BY
        $perPage = 50;
        $currentPage = request()->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;

        $userIdsPaginated = \App\Models\Score::query()
            ->join('rounds', 'scores.round_id', '=', 'rounds.id')
            ->join('rooms', 'rounds.room_id', '=', 'rooms.id')
            ->where('rooms.is_public', true)
            ->where('scores.created_at', '>=', now()->subDays(7))
            ->selectRaw('scores.user_id, ROUND(SUM(scores.score), 1) as total_score')
            ->groupBy('scores.user_id')
            ->orderByDesc('total_score')
            ->offset($offset)
            ->limit($perPage)
            ->get();

        $users = \App\Models\User::with('userLevel')
            ->whereIn('id', $userIdsPaginated->pluck('user_id'))
            ->get()
            ->keyBy('id');

        $topWeek = $userIdsPaginated->map(function ($score) use ($users) {
            $user = $users->get($score->user_id);

            return [
                'user' => $user ? [
                    'id' => $user->id,
                    'name' => $user->name,
                    'photo' => $user->photo,
                    'userLevel' => $user->userLevel,
                ] : null,
                'total_score' => (float) $score->total_score,
            ];
        })->filter(fn ($item) => $item['user'] !== null);

        $topWeek = new \Illuminate\Pagination\LengthAwarePaginator(
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
            $userWeekScore = \App\Models\Score::query()
                ->join('rounds', 'scores.round_id', '=', 'rounds.id')
                ->join('rooms', 'rounds.room_id', '=', 'rooms.id')
                ->where('rooms.is_public', true)
                ->where('scores.user_id', $user->id)
                ->where('scores.created_at', '>=', now()->subDays(7))
                ->selectRaw('ROUND(SUM(scores.score), 1) as total_score')
                ->first();

            if ($userWeekScore) {
                $userWeekPosition = \App\Models\Score::query()
                    ->join('rounds', 'scores.round_id', '=', 'rounds.id')
                    ->join('rooms', 'rounds.room_id', '=', 'rooms.id')
                    ->where('rooms.is_public', true)
                    ->where('scores.created_at', '>=', now()->subDays(7))
                    ->selectRaw('scores.user_id, ROUND(SUM(scores.score), 1) as total_score')
                    ->groupBy('scores.user_id')
                    ->havingRaw('ROUND(SUM(scores.score), 1) > ?', [$userWeekScore->total_score])
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

    public function byTeams()
    {
        $user = Auth::user();

        // Paginated Teams
        $topTeams = TotalScore::query()
            ->with('team')
            ->select('totalscorable_id')
            ->selectRaw('ROUND(SUM(score), 1) as total_score')
            ->where('totalscorable_type', \App\Models\Team::class)
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
                ->where('totalscorable_type', \App\Models\Team::class)
                ->where('totalscorable_id', $user->team->id)
                ->join('rooms', 'total_scores.room_id', '=', 'rooms.id')
                ->where('rooms.is_public', true)
                ->selectRaw('ROUND(SUM(score), 1) as total_score')
                ->groupBy('totalscorable_id')
                ->first();

            if ($userTeamScore) {
                $userTeamPosition = TotalScore::query()
                    ->where('totalscorable_type', \App\Models\Team::class)
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
            'week' => $room->weekUsersScores,
            // 'month' => $room->monthUsersScores,
            'lifetime' => $room->lifetimeUsersScores,
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

    // Get user level metrics for modal
    public function userLevelMetrics(\App\Models\User $user)
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
                'seniority_months' => $userLevel->months_seniority ?? 0,
                'consecutive_days_streak' => $userLevel->consecutive_days_streak ?? 0,
                'rooms_created_count' => $userLevel->rooms_created_count ?? 0,
                'playlists_created_count' => $userLevel->playlists_created_count ?? 0,
                'tracks_liked_count' => $userLevel->tracks_liked_count ?? 0,
                'has_team' => $user->hasTeam(),
            ],
        ]);
    }
}
