<?php

namespace App\Http\Controllers\Moderation;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ModeratorController extends Controller
{
    public function index(Request $request)
    {
        $roomsWithModerators = \App\Models\Room::isPublic()
            ->select('id', 'name')
            ->with(['moderators' => function ($query) use ($request) {
                if ($request->search) {
                    $query->where('name', 'like', "%{$request->search}%")
                        ->orWhere('email', 'like', "%{$request->search}%");
                }
                $query->withCount('moderatedRooms')
                    ->with([
                        'scores' => function ($query) {
                            $query->latest()->limit(1);
                        },
                        'tracks' => function ($query) {
                            $query->whereHas('playlist', function ($q) {
                                $q->where('is_public', true);
                            })->latest()->limit(1);
                        },
                    ]);
            }])
            ->orderBy('name')
            ->get();

        return Inertia::render('Moderation/Moderators', [
            'roomsWithModerators' => $roomsWithModerators->map(function ($room) {
                return [
                    'id' => $room->id,
                    'name' => $room->name,
                    'moderators' => $room->moderators->map(function ($moderator) {
                        $lastActivity = max(
                            $moderator->updated_at?->timestamp ?? 0,
                            $moderator->scores->first()?->created_at?->timestamp ?? 0,
                            $moderator->tracks->first()?->created_at?->timestamp ?? 0
                        );
                        $isInactive = $lastActivity < now()->subMonths(6)->timestamp;

                        return [
                            'id' => $moderator->id,
                            'name' => $moderator->name,
                            'email' => $moderator->email,
                            'photo' => $moderator->photo,
                            'last_connection' => $moderator->updated_at ? $moderator->updated_at->diffForHumans() : 'Jamais',
                            'last_game_activity' => $moderator->scores->first()?->created_at ? $moderator->scores->first()->created_at->diffForHumans() : 'Jamais',
                            'last_track_added' => $moderator->tracks->first()?->created_at ? $moderator->tracks->first()->created_at->diffForHumans() : 'Jamais',
                            'moderated_rooms_count' => $moderator->moderated_rooms_count,
                            'is_inactive' => $isInactive,
                        ];
                    }),
                ];
            }),
            'filters' => $request->only(['search']),
        ]);
    }

    public function store(Request $request, User $user)
    {
        try {
            if ($user->is_public_moderator) {
                return back()->with('error', 'User is already a moderator.');
            }

            $user->update(['is_public_moderator' => true]);

            return back()->with('success', 'User has been promoted to moderator successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to promote user to moderator. Please try again.');
        }
    }

    public function destroy(User $user)
    {
        try {
            if (! $user->is_public_moderator) {
                return back()->with('error', 'User is not a moderator.');
            }

            $user->update(['is_public_moderator' => false]);

            return back()->with('success', 'User has been demoted from moderator successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to demote user from moderator. Please try again.');
        }
    }
}
