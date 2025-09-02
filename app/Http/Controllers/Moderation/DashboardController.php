<?php

namespace App\Http\Controllers\Moderation;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Playlist;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Mchev\Banhammer\Models\Ban;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Basic Statistics
            $stats = [
                'total_users' => User::count(),
                'total_rooms' => Room::count(),
                'public_rooms' => Room::isPublic()->count(),
                'private_rooms' => Room::isPrivate()->count(),
                'total_playlists' => Playlist::count(),
                'public_playlists' => Playlist::isPublic()->count(),
                'private_playlists' => Playlist::isNotPublic()->count(),
                'todays_messages' => Message::whereDate('created_at', Carbon::today())->count(),
                'banned_users' => User::banned()->count(),
                'moderators' => User::publicModerators()->count(),
            ];

            // Time-based Statistics
            $timeStats = [
                'new_users_today' => User::whereDate('created_at', Carbon::today())->count(),
                'new_messages_today' => Message::whereDate('created_at', Carbon::today())->count(),
                'deleted_messages_today' => Message::onlyTrashed()->whereDate('deleted_at', Carbon::today())->count(),
                'banned_users_today' => Ban::whereDate('created_at', Carbon::today())->count(),
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
                            'deleted_at' => $message->deleted_at->format('d/m/Y H:i:s'),
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
                'banned_users' => User::banned()
                    ->with('team')
                    ->latest()
                    ->take(5)
                    ->get()
                    ->map(function ($user) {
                        $ban = $user->bans()->with('createdBy')->latest()->first();

                        return [
                            'id' => $user->id,
                            'name' => $user->name,
                            'reason' => $ban?->comment ?? 'Aucune raison spécifiée',
                            'duration' => $ban?->expires_at ? $ban->expires_at->diffForHumans() : 'Permanent',
                            'moderator_name' => $ban?->createdBy?->name ?? 'Système',
                            'team_name' => $user->team?->name,
                            'banned_at' => $ban?->created_at->format('d/m/Y H:i:s') ?? 'Date inconnue',
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
                    ];
                });

            return Inertia::render('Moderation/Dashboard', [
                'stats' => $stats,
                'timeStats' => $timeStats,
                'recentActivity' => $recentActivity,
                'roomStats' => $roomStats,
            ]);
        } catch (\Exception $e) {
            Log::error('Dashboard moderation error: '.$e->getMessage(), [
                'exception' => $e,
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()->with('error', 'Une erreur est survenue lors du chargement du tableau de bord.' . $e->getMessage());
        }
    }
}
