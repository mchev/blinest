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
            }])
            ->orderBy('name')
            ->get();

        return Inertia::render('Moderation/Moderators', [
            'roomsWithModerators' => $roomsWithModerators,
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
