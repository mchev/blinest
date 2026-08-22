<?php

namespace App\Http\Controllers;

use App\Jobs\UpdateUserLevel;
use App\Models\Track;
use App\Models\User;
use App\Services\Profiles\ProfilePageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Laravel\Head\Facades\Head;

class ProfileController extends Controller
{
    private const TABS = ['scores', 'likes', 'bookmarks', 'minigames'];

    public function __construct(
        private ProfilePageService $profiles,
    ) {}

    public function show(Request $request, User $user): InertiaResponse
    {
        if ($user->isGuest()) {
            abort(404);
        }

        $tab = $request->query('tab', 'scores');

        if (! in_array($tab, self::TABS, true)) {
            $tab = 'scores';
        }

        $page = max(1, (int) $request->query('page', 1));
        $profile = $this->profiles->header($user);

        Head::title($user->name);

        $props = [
            'profile' => $profile,
            'activeTab' => $tab,
            'scores' => null,
            'likes' => null,
            'bookmarks' => null,
            'minigames' => null,
            'profileHighlights' => Inertia::defer(fn () => $this->profiles->highlights($user)),
        ];

        $props[$tab] = match ($tab) {
            'likes' => $this->profiles->likes($user, $page),
            'bookmarks' => $this->profiles->bookmarks($user, $page),
            'minigames' => $this->profiles->minigames($user, $page),
            default => $this->profiles->scores($user, $page),
        };

        if (($profile['donation_summary']['donation_count'] ?? 0) > 0) {
            $props['donations'] = Inertia::defer(fn () => $this->profiles->donationHistory($user));
        }

        return Inertia::render('Profiles/Show', $props);
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

        UpdateUserLevel::dispatch(
            user: $request->user(),
            type: 'likes_count'
        );

        return back();
    }
}
