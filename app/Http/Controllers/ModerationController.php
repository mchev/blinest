<?php

namespace App\Http\Controllers;

use App\Events\MessageDeleted;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ModerationController extends Controller
{
    public function destroyMessage(Message $message)
    {
        if (Auth::user()->isRoomModerator($message->room) || Auth::user()->isPublicModerator() || Auth::user()->isAdministrator()) {
            broadcast(new MessageDeleted($message));
            $message->delete();
        }
    }

    public function banUser(User $user)
    {
        if (Auth::user()->isPublicModerator() || Auth::user()->isAdministrator()) {
            if (! $user->isPublicModerator() && ! $user->isAdministrator()) {
                $user->ban([
                    'expired_at' => Request::input('expired_at') ?? null,
                    'comment' => Request::input('comment') ?? null,
                ]);
            }
        }

        return redirect()->back();
    }

    public function unbanUser(User $user)
    {
        if (Auth::user()->isPublicModerator() || Auth::user()->isAdministrator()) {
            $user->unban();
        }
    }
}
