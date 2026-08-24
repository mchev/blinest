<?php

namespace App\Http\Controllers\Moderation;

use App\Http\Controllers\Controller;
use App\Models\Playlist;
use App\Models\Room;
use App\Models\User;
use App\Services\Moderation\ModerationModeratorService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class ModeratorController extends Controller
{
    public function __construct(
        private ModerationModeratorService $moderators,
    ) {}

    public function index(Request $request): InertiaResponse
    {
        $payload = $this->moderators->paginate(
            $request->string('search')->toString() ?: null,
            $request->boolean('inactive_only'),
            (int) $request->input('per_page', 20),
        );

        return Inertia::render('Moderation/Moderators', [
            'moderators' => $payload['moderators'],
            'stats' => $payload['stats'],
            'coverage' => $payload['coverage'],
            'filters' => $request->only(['search', 'inactive_only', 'per_page']),
        ]);
    }

    public function detachRoom(Room $room, User $user)
    {
        $this->moderators->detachFromPublicRoom($room, $user);

        return back()->with('success', __('Moderation moderator room access revoked'));
    }

    public function detachPlaylist(Playlist $playlist, User $user)
    {
        $this->moderators->detachFromPublicPlaylist($playlist, $user);

        return back()->with('success', __('Moderation moderator playlist access revoked'));
    }
}
