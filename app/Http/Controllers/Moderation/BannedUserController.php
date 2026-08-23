<?php

namespace App\Http\Controllers\Moderation;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Moderation\ModerationBannedUserService;
use App\Services\Moderation\ModerationUserService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BannedUserController extends Controller
{
    public function __construct(
        private ModerationBannedUserService $bannedUsers,
        private ModerationUserService $moderationUsers,
    ) {}

    public function index(Request $request)
    {
        $viewer = $request->user();

        return Inertia::render('Moderation/BannedUsers', [
            'bannedUsers' => $this->bannedUsers->paginateBannedUsers(
                $viewer,
                $request->string('search')->toString() ?: null,
                (int) $request->input('per_page', 20),
            ),
            'filters' => $request->only(['search', 'per_page']),
            'canViewSensitiveData' => $this->moderationUsers->canViewSensitiveData($viewer),
        ]);
    }

    public function unban(User $user)
    {
        if (! $user->isBanned()) {
            return back()->withErrors([
                'error' => __('Moderation user is not banned'),
            ]);
        }

        try {
            $user->unban();

            return back()->with('success', __('Moderation user unbanned', ['name' => $user->name]));
        } catch (\Exception $e) {
            report($e);

            return back()->withErrors([
                'error' => __('Moderation unban failed'),
            ]);
        }
    }
}
