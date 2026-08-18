<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\Donations\DonationGoalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     *
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array
     */
    public function share(Request $request)
    {
        $user = $request->user()?->load('userLevel');
        $donationGoal = app(DonationGoalService::class);

        return array_merge(parent::share($request), [
            'auth' => function () use ($user, $donationGoal) {
                return [
                    'user' => $user ? [
                        'id' => $user->id,
                        'name' => $user->name,
                        'photo' => $user->photo,
                        'is_guest' => $user->isGuest(),
                        'is_supporter' => $donationGoal->userIsSupporter($user),
                        'admin' => $user->isAdministrator(),
                        'is_public_moderator' => $user->isPublicModerator(),
                        'team' => $user->team,
                        'elo' => $user->elo ?? 1500,
                        'level' => $user->userLevel?->level ?? 1,
                        'current_xp' => $user->userLevel?->current_xp ?? 0,
                        'xp_for_next_level' => $user->userLevel?->xp_for_next_level ?? 100,
                        'total_xp' => $user->userLevel?->total_xp ?? 0,
                        'level_metrics' => $user->userLevel ? [
                            'score_public_rooms' => $user->userLevel->score_public_rooms ?? 0,
                            'minigame_scores_total' => $user->userLevel->minigame_scores_total ?? 0,
                            'seniority_months' => $user->userLevel->months_seniority ?? 0,
                            'consecutive_days_streak' => $user->userLevel->consecutive_days_streak ?? 0,
                            'rooms_created_count' => $user->userLevel->rooms_created_count ?? 0,
                            'playlists_created_count' => $user->userLevel->playlists_created_count ?? 0,
                            'tracks_liked_count' => $user->userLevel->tracks_liked_count ?? 0,
                            'has_team' => $user->hasTeam(),
                        ] : null,
                        'notifications' => Cache::rememberForever("{$user->id}_unread_notifications", function () use ($user) {
                            return $user->unreadNotifications
                                ->map(fn ($notification) => $notification->toArray())
                                ->values()
                                ->all();
                        }),
                        'can' => Gate::forUser($user)->abilities(),
                        'pending_requests' => $user->cachedTeamRequestIds()['pending'],
                        'declined_requests' => $user->cachedTeamRequestIds()['declined'],
                    ] : null,
                ];
            },
            'publicModerators' => Cache::remember('public-moderators', 3600, function () {
                return User::publicModerators()
                    ->select('id', 'name', 'photo_path')
                    ->get()
                    ->map(fn (User $moderator) => $moderator->only(['id', 'name', 'photo_path']) + ['photo' => $moderator->photo])
                    ->values()
                    ->all();
            }),
            'flash' => function () use ($request) {
                return [
                    'success' => $request->session()->get('success'),
                    'error' => $request->session()->get('error'),
                    'message' => $request->session()->get('message'),
                ];
            },
            'ziggy' => function () use ($request) {
                $routes = Cache::remember('inertia_ziggy_routes_v4', 3600, fn () => (new Ziggy)->toArray());

                return array_merge($routes, [
                    'location' => $request->url(),
                ]);
            },
            'locale' => function () {
                return app()->getLocale();
            },
            'default_locale' => fn () => config('app.locale', 'fr'),
            'available_locales' => function () {
                return config('app.available_locales', ['fr', 'en', 'es']);
            },
            'locale_names' => function () {
                return config('app.locale_names', [
                    'fr' => 'Français',
                    'en' => 'English',
                    'es' => 'Español',
                ]);
            },
            'language' => function () {
                $locale = app()->getLocale();

                return Cache::remember("inertia_translations_v40_{$locale}", 3600, function () use ($locale) {
                    return translations(
                        base_path('lang/'.$locale.'.json')
                    );
                });
            },
            'donation_goal' => fn () => $donationGoal->currentProgressForUser(
                $request->user(),
                app()->getLocale(),
            ),
        ]);
    }
}
