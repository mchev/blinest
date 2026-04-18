<?php

namespace App\Http\Controllers\Moderation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Moderation\BanUserRequest;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()
            ->filter(['search' => $request->search])
            ->when($request->sort_by, function ($query, $sortBy) use ($request) {
                $direction = $request->sort_direction === 'asc' ? 'asc' : 'desc';
                $query->orderBy($sortBy, $direction);
            }, function ($query) {
                $query->latest();
            });

        $users = $query->paginate($request->per_page ?? 10)
            ->withQueryString()
            ->through(fn ($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
                'last_login_at' => $user->last_login_at,
                'is_admin' => $user->isAdministrator(),
                'is_moderator' => $user->isPublicModerator(),
                'is_banned' => $user->isBanned(),
                'bans_count' => $user->bans()->count(),
            ]);

        return Inertia::render('Moderation/UsersManagement', [
            'users' => $users,
            'filters' => $request->only(['search', 'per_page', 'sort_by', 'sort_direction']),
        ]);
    }

    public function show(User $user)
    {
        $user->load([
            'messages' => fn ($query) => $query
                ->with(['messagable', 'user'])
                ->orderBy('created_at', 'desc')
                ->take(50),
            'rooms',
            'bans',
            'playlists',
        ]);

        return Inertia::render('Moderation/UsersManagement', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
                'last_login_at' => $user->last_login_at,
                'is_admin' => $user->isAdministrator(),
                'is_moderator' => $user->isPublicModerator(),
                'is_banned' => $user->isBanned(),
                'bans' => $user->bans->map(fn ($ban) => [
                    'id' => $ban->id,
                    'comment' => $ban->comment,
                    'created_at' => $ban->created_at->format('d/m/Y H:i:s'),
                    'expires_at' => $ban->expired_at ? $ban->expired_at->diffForHumans() : __('Permanent ban'),
                    'banned_by' => $ban->createdBy?->name,
                ]),
                'rooms' => $user->rooms->map(fn ($room) => [
                    'id' => $room->id,
                    'name' => $room->name,
                    'created_at' => $room->created_at,
                    'is_private' => $room->is_private,
                    'messages_count' => $room->messages_count,
                ]),
                'playlists' => $user->playlists->map(fn ($playlist) => [
                    'id' => $playlist->id,
                    'name' => $playlist->name,
                    'created_at' => $playlist->created_at,
                    'is_private' => $playlist->is_private,
                    'tracks_count' => $playlist->tracks_count,
                ]),
                'messages' => $user->messages->map(fn ($message) => [
                    'id' => $message->id,
                    'content' => $message->content,
                    'created_at' => $message->created_at,
                    'is_edited' => $message->is_edited,
                    'is_deleted' => $message->is_deleted,
                    'room' => [
                        'id' => $message->messagable->id,
                        'name' => $message->messagable->name,
                    ],
                    'context' => $this->getMessageContext($message),
                ]),
            ],
        ]);
    }

    public function ban(BanUserRequest $request, User $user)
    {
        if ($user->isAdministrator() || $user->isPublicModerator()) {
            return back()->withErrors([
                'error' => 'Vous ne pouvez pas bannir un modérateur ou un administrateur.',
            ]);
        }

        try {
            $user->ban([
                'expired_at' => $request->duration ? now()->addMinutes($request->duration) : null,
                'comment' => $request->reason,
                'metas' => ['banned_by' => $request->user()->id],
                'ip' => $user->ip,
            ]);

            return back()->with('success', $user->name.' a été banni avec succès.');
        } catch (\Exception $e) {
            report($e);

            return back()->withErrors([
                'error' => 'Échec du bannissement. Veuillez réessayer.'.$e->getMessage(),
            ]);
        }
    }

    private function getMessageContext($message)
    {
        return Message::where('messagable_type', 'App\Models\Room')
            ->where('messagable_id', $message->messagable_id)
            ->where('created_at', '<', $message->created_at)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->with('user')
            ->get()
            ->reverse()
            ->map(fn ($contextMsg) => [
                'id' => $contextMsg->id,
                'content' => $contextMsg->content,
                'user' => [
                    'id' => $contextMsg->user->id,
                    'name' => $contextMsg->user->name,
                ],
            ]);
    }
}
