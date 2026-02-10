<?php

namespace App\Http\Controllers;

use App\Models\Track;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class ProfileController extends Controller
{
    public function show(Request $request, User $user): InertiaResponse
    {
        $user->loadMissing(['userLevel', 'team']);

        $scoresPage = $request->query('scores', 1);
        $likesPage = $request->query('likes', 1);
        $bookmarksPage = $request->query('bookmarks', 1);

        $totalScoresQuery = $user->totalScores()
            ->leftJoin('rooms', 'total_scores.room_id', '=', 'rooms.id')
            ->selectRaw('
                SUM(total_scores.score) as total_score,
                SUM(CASE WHEN rooms.is_public = 1 THEN total_scores.score ELSE 0 END) as total_public_score,
                SUM(CASE WHEN rooms.is_public = 0 THEN total_scores.score ELSE 0 END) as total_private_score
            ')
            ->first();

        $scoresPerPage = $request->has('scores') ? 10 : 5;

        // Optimize: Use subquery for public rooms instead of JOIN to avoid slow count
        $publicRoomSubquery = \App\Models\Room::where('is_public', true)
            ->whereNull('deleted_at')
            ->select('id');

        $scores = $user->totalScores()
            ->whereIn('room_id', $publicRoomSubquery)
            ->select('total_scores.id', 'total_scores.score', 'total_scores.room_id', 'total_scores.updated_at')
            ->orderBy('total_scores.updated_at', 'desc')
            ->paginate($scoresPerPage, ['*'], 'scores', $scoresPage);

        // Eager load rooms separately to avoid N+1
        $roomIds = $scores->pluck('room_id')->unique();
        $rooms = \App\Models\Room::whereIn('id', $roomIds)
            ->select('id', 'name', 'slug', 'photo_path')
            ->get()
            ->keyBy('id');

        $scores = $scores->through(function ($score) use ($rooms) {
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

        $likesPerPage = $request->has('likes') ? 10 : 5;

        // Optimize: Use direct JOIN instead of whereHas with EXISTS subquery
        $likes = \App\Models\Track::query()
            ->join('votes', function ($join) use ($user) {
                $join->on('tracks.id', '=', 'votes.votable_id')
                    ->where('votes.votable_type', \App\Models\Track::class)
                    ->where('votes.user_id', $user->id)
                    ->where('votes.votes', 1);
            })
            ->select('tracks.*')
            ->with(['answers' => function ($query) {
                $query->with('type:id,name');
            }])
            ->orderBy('tracks.created_at', 'desc')
            ->paginate($likesPerPage, ['*'], 'likes', $likesPage);

        $bookmarksPerPage = $request->has('bookmarks') ? 10 : 5;
        $bookmarks = $user->bookmarkedRooms()
            ->with(['category:id,name'])
            ->select('rooms.id', 'rooms.name', 'rooms.slug', 'rooms.photo_path', 'rooms.category_id')
            ->orderBy('bookmarks.created_at', 'desc')
            ->paginate($bookmarksPerPage, ['*'], 'bookmarks', $bookmarksPage)
            ->through(fn ($room) => [
                'id' => $room->id,
                'name' => $room->name,
                'slug' => $room->slug,
                'photo' => $room->photo,
                'category' => $room->category ? ['id' => $room->category->id, 'name' => $room->category->name] : null,
            ]);

        $stats = Cache::remember("user_stats_{$user->id}", 1800, function () use ($user) {
            return [
                'rooms' => $user->moderatedRooms()->count(),
                'playlists' => $user->moderatedPlaylists()->count(),
                'bookmarks' => $user->bookmarkedRooms()->count(),
            ];
        });

        $userLevel = $user->userLevel;
        $levelMetrics = $userLevel ? [
            'score_public_rooms' => $userLevel->score_public_rooms ?? 0,
            'minigame_scores_total' => (int) \App\Models\MinigameScore::query()->where('user_id', $user->id)->sum('score'),
            'seniority_months' => $userLevel->months_seniority ?? 0,
            'consecutive_days_streak' => $userLevel->consecutive_days_streak ?? 0,
            'rooms_created_count' => $userLevel->rooms_created_count ?? 0,
            'playlists_created_count' => $userLevel->playlists_created_count ?? 0,
            'tracks_liked_count' => $userLevel->tracks_liked_count ?? 0,
            'has_team' => $user->team !== null,
        ] : null;

        return Inertia::render('Profiles/Show', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'photo' => $user->photo,
                'team' => $user->team ? [
                    'id' => $user->team->id,
                    'name' => $user->team->name,
                ] : null,
                'level' => $userLevel?->level ?? 1,
                'current_xp' => $userLevel?->current_xp ?? 0,
                'xp_for_next_level' => $userLevel?->xp_for_next_level ?? 100,
                'total_xp' => $userLevel?->total_xp ?? 0,
                'level_metrics' => $levelMetrics,
                'created_at_from_now' => $user->created_at->diffForHumans(null, true),
                'elo' => $user->elo ?? 1500,
                'total_score' => (float) ($totalScoresQuery->total_score ?? 0),
                'total_public_score' => (float) ($totalScoresQuery->total_public_score ?? 0),
                'total_private_score' => (float) ($totalScoresQuery->total_private_score ?? 0),
                'stats' => $stats,
                'scores' => $scores,
                'likes' => $likes,
                'bookmarks' => $bookmarks,
            ],
        ]);
    }

    public function scoreEvolution(User $user)
    {
        $scoreEvolution = Cache::remember("user_score_evolution_{$user->id}", 3600, function () use ($user) {
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

        return response()->json([
            'score_evolution' => $scoreEvolution,
        ]);
    }

    public function unlikeTrack(Request $request, Track $track)
    {
        $request->user()->votes()
            ->where('votable_type', Track::class)
            ->where('votable_id', $track->id)
            ->delete();

        \App\Jobs\UpdateUserLevel::dispatch(
            user: $request->user(),
            type: 'likes_count'
        );

        return back();
    }
}
