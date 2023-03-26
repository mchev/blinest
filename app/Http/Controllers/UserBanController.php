<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserBanController extends Controller
{
    public function index()
    {
    }

    public function store(Request $request, User $user)
    {
        if (auth()->user()->isPublicModerator() || auth()->user()->isAdministrator()) {
            if (! $user->isPublicModerator() && ! $user->isAdministrator()) {
                $user->ban([
                    'expired_at' => $request->expired_at,
                    'comment' => $request->comment,
                    'metas' => $request->metas,
                    'ip' => $user->ip,
                ]);

                return redirect()->back()->with('success', $user->name. ' ' .__('has been banned.'));
            } else {
                return redirect()->back()->with('error', __('Impossible to ban a moderator'));
            }
        }

        return abort(403);
    }

    public function destroy(User $user)
    {
        if (auth()->user()->isPublicModerator() || auth()->user()->isAdministrator()) {
            $user->unban();

            return redirect()->back()->with('success', $user->name. ' ' .__('has been unbanned.'));
        }

        return abort(403);
    }
}
