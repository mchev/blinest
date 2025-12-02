<?php

namespace App\Http\Middleware;

use App\Models\User;
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

        return array_merge(parent::share($request), [
            'auth' => function () use ($user) {
                return [
                    'user' => $user ? [
                        'id' => $user->id,
                        'name' => $user->name,
                        'photo' => $user->photo,
                        'admin' => $user->isAdministrator(),
                        'is_public_moderator' => $user->isPublicModerator(),
                        'team' => $user->team,
                        'level' => $user->userLevel?->level ?? 1,
                        'current_xp' => $user->userLevel?->current_xp ?? 0,
                        'xp_for_next_level' => $user->userLevel?->xp_for_next_level ?? 100,
                        'total_xp' => $user->userLevel?->total_xp ?? 0,
                        'level_metrics' => $user->userLevel ? [
                            'score_public_rooms' => $user->userLevel->score_public_rooms ?? 0,
                            'seniority_months' => $user->userLevel->months_seniority ?? 0,
                            'consecutive_days_streak' => $user->userLevel->consecutive_days_streak ?? 0,
                            'rooms_created_count' => $user->userLevel->rooms_created_count ?? 0,
                            'playlists_created_count' => $user->userLevel->playlists_created_count ?? 0,
                            'tracks_liked_count' => $user->userLevel->tracks_liked_count ?? 0,
                            'has_team' => $user->hasTeam(),
                        ] : null,
                        'notifications' => Cache::rememberForever("{$user->id}_unread_notifications", function () use ($user) {
                            return $user->unreadNotifications;
                        }),
                        'can' => Gate::forUser($user)->abilities(),
                        'pending_requests' => $user->teamRequests()->whereNull('declined_at')->pluck('team_id'),
                        'declined_requests' => $user->teamRequests()->whereNotNull('declined_at')->pluck('team_id'),
                    ] : null,
                ];
            },
            'publicModerators' => Cache::remember('public-moderators', 3600, function () {
                return User::publicModerators()->select('id', 'name', 'photo_path')->get();
            }),
            'flash' => function () use ($request) {
                return [
                    'success' => $request->session()->get('success'),
                    'error' => $request->session()->get('error'),
                    'message' => $request->session()->get('message'),
                ];
            },
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(), // For ssr.js
                ]);
            },
            'locale' => function () {
                return app()->getLocale();
            },
            'language' => function () {
                return translations(
                    base_path('lang/'.app()->getLocale().'.json')
                );
            },
        ]);
    }
}
