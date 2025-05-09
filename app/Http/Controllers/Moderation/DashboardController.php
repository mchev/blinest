<?php

namespace App\Http\Controllers\Moderation;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Playlist;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic Statistics
        $stats = [
            'total_users' => User::count(),
            'total_rooms' => Room::count(),
            'public_rooms' => Room::where('is_public', true)->count(),
            'private_rooms' => Room::where('is_public', false)->count(),
            'total_playlists' => Playlist::count(),
            'public_playlists' => Playlist::where('is_public', true)->count(),
            'private_playlists' => Playlist::where('is_public', false)->count(),
            'total_messages' => Message::count(),
            'deleted_messages' => Message::onlyTrashed()->count(),
            'banned_users' => User::onlyTrashed()->count(),
            'moderators' => User::publicModerators()->count(),
        ];

        // Time-based Statistics
        $timeStats = [
            'new_users_today' => User::whereDate('created_at', Carbon::today())->count(),
            'new_messages_today' => Message::whereDate('created_at', Carbon::today())->count(),
            'deleted_messages_today' => Message::onlyTrashed()->whereDate('deleted_at', Carbon::today())->count(),
            'banned_users_today' => User::onlyTrashed()->whereDate('deleted_at', Carbon::today())->count(),
        ];

        // Recent Activity
        $recentActivity = [
            'deleted_messages' => Message::onlyTrashed()
                ->with(['user', 'room'])
                ->latest('deleted_at')
                ->take(5)
                ->get()
                ->map(function ($message) {
                    return [
                        'id' => $message->id,
                        'body' => $message->body,
                        'deleted_at' => $message->deleted_at->format('Y-m-d H:i:s'),
                        'user' => [
                            'id' => $message->user->id,
                            'name' => $message->user->name,
                        ],
                        'room' => [
                            'id' => $message->room->id,
                            'name' => $message->room->name,
                        ],
                    ];
                }),
            'banned_users' => User::onlyTrashed()
                ->latest('deleted_at')
                ->take(5)
                ->get()
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'banned_at' => $user->deleted_at->format('Y-m-d H:i:s'),
                    ];
                }),
            'recent_moderators' => User::publicModerators()
                ->latest('updated_at')
                ->take(5)
                ->get()
                ->map(function ($moderator) {
                    return [
                        'id' => $moderator->id,
                        'name' => $moderator->name,
                        'email' => $moderator->email,
                        'promoted_at' => $moderator->updated_at->format('Y-m-d H:i:s'),
                    ];
                }),
        ];

        // Room Statistics
        $roomStats = Room::select('id', 'name', 'is_public')
            ->withCount('messages')
            ->orderByDesc('messages_count')
            ->take(5)
            ->get()
            ->map(function ($room) {
                return [
                    'id' => $room->id,
                    'name' => $room->name,
                    'is_public' => $room->is_public,
                    'messages_count' => $room->messages_count,
                    'users_count' => $room->subscriptions,
                ];
            });

        return Inertia::render('Moderation/Dashboard', [
            'stats' => $stats,
            'timeStats' => $timeStats,
            'recentActivity' => $recentActivity,
            'roomStats' => $roomStats,
        ]);
    }
}
