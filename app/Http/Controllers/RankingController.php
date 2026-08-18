<?php

namespace App\Http\Controllers;

use App\Models\MinigameScore;
use App\Models\Room;
use App\Models\Team;
use App\Models\TotalScore;
use App\Models\User;
use App\Services\Rankings\GlobalLeaderboardService;
use App\Services\Rooms\RoomLeaderboardService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Laravel\Head\Facades\Head;

class RankingController extends Controller
{
    public function index(Request $request, GlobalLeaderboardService $leaderboardService)
    {
        return $this->players($request, $leaderboardService);
    }

    public function players(Request $request, GlobalLeaderboardService $leaderboardService)
    {
        Head::title(__('Rankings'));

        $sort = $request->string('sort', 'elo')->toString();
        $page = max(1, (int) $request->get('page', 1));
        $roomId = $request->integer('room') ?: null;

        $user = Auth::user();
        $resolvedRoomId = $leaderboardService->resolveOfficialRoomId($roomId);

        return Inertia::render('Rankings/Players', [
            'leaderboard' => $leaderboardService->paginatedPayload($sort, $page, $resolvedRoomId),
            'sort' => $sort,
            'sorts' => $leaderboardService->availableSorts(),
            'officialRooms' => $leaderboardService->officialRooms(),
            'roomId' => $resolvedRoomId,
            'userContext' => $user
                ? Inertia::defer(fn () => $leaderboardService->userContext($user, $sort, $resolvedRoomId))
                : null,
        ]);
    }

    public function legacySortRedirect(string $sort)
    {
        return redirect()->route('rankings.index', ['sort' => $sort]);
    }

    public function byLevel()
    {
        return $this->legacySortRedirect('level');
    }

    public function byScore()
    {
        return $this->legacySortRedirect('score');
    }

    public function byElo()
    {
        return $this->legacySortRedirect('elo');
    }

    public function byWeek()
    {
        return $this->legacySortRedirect('week');
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
    public function roomScores(Room $room, RoomLeaderboardService $leaderboardService)
    {
        $leaderboards = $leaderboardService->leaderboardsForRoom($room);

        $user = Auth::user();

        return response()->json([
            ...$leaderboards,
            'user' => $user ? $leaderboardService->userSnapshot($room, $user, $leaderboards) : null,
        ]);
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
}
