<?php

namespace App\Http\Controllers\Moderation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Moderation\BanUserRequest;
use App\Models\User;
use App\Services\Moderation\ModerationUserService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserManagementController extends Controller
{
    public function __construct(
        private ModerationUserService $moderationUsers,
    ) {}

    public function index(Request $request)
    {
        $viewer = $request->user();

        return Inertia::render('Moderation/UsersManagement', [
            'users' => $this->moderationUsers->paginateUsers(
                $viewer,
                $request->string('search')->toString() ?: null,
                (int) $request->input('per_page', 20),
            ),
            'filters' => $request->only(['search', 'per_page']),
            'canViewSensitiveData' => $this->moderationUsers->canViewSensitiveData($viewer),
        ]);
    }

    public function show(Request $request, User $user)
    {
        abort_if($user->isGuest(), 404);

        $viewer = $request->user();

        return Inertia::render('Moderation/UsersManagement', [
            'user' => $this->moderationUsers->formatUserDetail($user, $viewer),
            'canViewSensitiveData' => $this->moderationUsers->canViewSensitiveData($viewer),
            'filters' => $request->only(['search', 'per_page']),
        ]);
    }

    public function ban(BanUserRequest $request, User $user)
    {
        if ($user->isAdministrator() || $user->isPublicModerator()) {
            return back()->withErrors([
                'error' => __('Moderation cannot ban staff'),
            ]);
        }

        try {
            $user->ban([
                'expired_at' => $request->duration ? now()->addMinutes($request->duration) : null,
                'comment' => $request->reason,
                'metas' => ['banned_by' => $request->user()->id],
                'ip' => $user->ip,
            ]);

            return back()->with('success', __('Moderation user banned', ['name' => $user->name]));
        } catch (\Exception $e) {
            report($e);

            return back()->withErrors([
                'error' => __('Moderation ban failed'),
            ]);
        }
    }
}
