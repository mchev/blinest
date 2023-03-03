<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Playlist;
use App\Models\Room;
use App\Models\User;
use Inertia\Inertia;

class ModerationController extends Controller
{
    public function index()
    {
        return Inertia::render('Moderation/Index', [
            'stats' => [
                'users_count' => User::count(),
                'playlists_count' => Playlist::count(),
                'rooms_count' => Room::count(),
                'public_playlists_count' => Playlist::isPublic()->count(),
                'public_rooms_count' => Room::isPublic()->count(),
                'banned_users_count' => User::banned()->count(),
            ],
            'moderators' => Room::isPublic()->select('id', 'name')->with('moderators')->get(),
            'trashedMessages' => Message::onlyTrashed()
                ->orderByDesc('deleted_at')
                ->with('user', 'room')
                ->paginate(10, ['*'], 'trashedMessages')
                ->withQueryString()
                ->through(fn ($message) => [
                    'id' => $message->id,
                    'body' => $message->body,
                    'time' => $message->time,
                    'created_at' => $message->created_at->format('d/m/Y H:i:s'),
                    'deleted_at' => $message->deleted_at->format('d/m/Y H:i:s'),
                    'room' => [
                        'id' => $message->room->id,
                        'name' => $message->room->name,
                        'slug' => $message->room->slug,
                    ],
                    'user' => [
                        'id' => $message->user->id,
                        'name' => $message->user->name,
                        'ip' => $message->user_ip,
                        'photo' => $message->user->photo,
                    ],
                    'votes' => [
                        'total' => $message->totalDownvotes,
                        'voters' => $message->voters,
                    ],
                ]),
            'bannedUsers' => User::banned()
                ->with('bans')
                ->paginate(10, ['*'], 'bans')
                ->through(fn ($user) => [
                    'id' => $user->id,
                    'ip' => $user->ip,
                    'name' => $user->name,
                    'photo' => $user->photo,
                    'bans' => $user->bans->transform(fn ($ban) => [
                        'id' => $ban->id,
                        'ip' => $ban->ip,
                        'comment' => $ban->comment,
                        'created_at' => $ban->created_at->format('d/m/Y H:i:s'),
                        'expired_at' => $ban->expired_at ? $ban->expired_at->diffForHumans() : __('Permanent ban'),
                        'banned_by' => $ban->createdby?->name,
                    ]),
                ]),
        ]);
    }

    public function fetchUserInformations(User $user)
    {
        $user = [
            'id' => $user->id,
            'name' => $user->name,
            'photo' => $user->photo,
            'created_at' => $user->created_at->format('d/m/Y H:i'),
            'reports_count' => $user->messages()->whereHas('downvotes')->count(),
            'latest_messages' => $user->messages()->withTrashed()->orderByDesc('created_at')->with('room')->limit(10)->get(),
            'bans' => $user->bans()->orderByDesc('created_at')->get()->transform(fn ($ban) => [
                'id' => $ban->id,
                'comment' => $ban->comment,
                'created_at' => $ban->created_at->format('d/m/Y H:i:s'),
                'expired_at' => $ban->expired_at ? $ban->expired_at->format('d/m/Y H:i:s') : __('Permanent ban'),
                'banned_by' => User::find($ban->created_by_id)->name,
            ]),
        ];

        return response()->json($user);
    }
}
