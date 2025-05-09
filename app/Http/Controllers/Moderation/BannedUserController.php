<?php

namespace App\Http\Controllers\Moderation;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BannedUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::onlyTrashed()
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($request->sort_by, function ($query, $sortBy) use ($request) {
                $direction = $request->sort_direction === 'asc' ? 'asc' : 'desc';
                $query->orderBy($sortBy, $direction);
            }, function ($query) {
                $query->latest('deleted_at');
            });

        $bannedUsers = $query->paginate($request->per_page ?? 10)
            ->withQueryString();

        return Inertia::render('Moderation/BannedUsers', [
            'bannedUsers' => $bannedUsers,
            'filters' => $request->only(['search', 'per_page', 'sort_by', 'sort_direction']),
        ]);
    }

    public function ban(Request $request, User $user)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
        ]);

        try {
            $user->ban([
                'reason' => $request->reason,
                'expires_at' => now()->addDays($request->duration),
            ]);

            return back()->with('success', 'User has been banned successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to ban user. Please try again.');
        }
    }

    public function unban(User $user)
    {
        try {
            if (! $user->trashed()) {
                return back()->with('error', 'User is not banned.');
            }

            $user->restore();

            return back()->with('success', 'User has been unbanned successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to unban user. Please try again.');
        }
    }
}
