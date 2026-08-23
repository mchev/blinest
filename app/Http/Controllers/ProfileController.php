<?php

namespace App\Http\Controllers;

use App\Jobs\UpdateUserLevel;
use App\Models\Track;
use App\Models\User;
use App\Services\Profiles\ProfileCacheService;
use App\Services\Profiles\ProfilePageService;
use Illuminate\Http\Request;
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
        $sort = $request->string('sort', 'updated_at')->toString();
        $direction = $request->string('direction', 'desc')->toString();
        $profile = $this->profiles->header($user);

        Head::title($user->name);

        $props = [
            'profile' => $profile,
            'activeTab' => $tab,
            'scoresSort' => $sort,
            'scoresDirection' => $direction === 'asc' ? 'asc' : 'desc',
            'scores' => null,
            'likes' => null,
            'bookmarks' => null,
            'minigames' => null,
            'profileHighlights' => Inertia::defer(fn () => $this->profiles->highlights($user)),
            'scoreEvolution' => Inertia::defer(fn () => $this->profiles->scoreEvolution($user)),
        ];

        $props[$tab] = match ($tab) {
            'likes' => $this->profiles->likes($user, $page),
            'bookmarks' => $this->profiles->bookmarks($user, $page),
            'minigames' => $this->profiles->minigames($user, $page),
            default => $this->profiles->scores($user, $page, 10, $sort, $direction),
        };

        if ($this->profiles->shouldShowDonationHistory($user)) {
            $props['donations'] = Inertia::defer(fn () => $this->profiles->donationHistory($user));
        }

        return Inertia::render('Profiles/Show', $props);
    }

    public function scoreEvolution(User $user)
    {
        return response()->json([
            'score_evolution' => $this->profiles->scoreEvolution($user),
        ]);
    }

    public function unlikeTrack(Request $request, Track $track)
    {
        $request->user()->votes()
            ->where('votable_type', Track::class)
            ->where('votable_id', $track->id)
            ->delete();

        app(ProfileCacheService::class)->forget($request->user());

        UpdateUserLevel::dispatch(
            user: $request->user(),
            type: 'likes_count'
        );

        return back();
    }
}
