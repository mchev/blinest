<?php

namespace App\Http\Controllers;

use App\Events\MessageDeleted;
use App\Events\MessageReported;
use App\Events\NewMessage;
use App\Models\Message;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class RoomMessageController extends Controller
{
    public function store(Room $room)
    {
        if (Auth::user()->isNotBanned()) {

            // Validation
            Request::validate([
                'body' => ['required', 'max:255'],
            ]);

            // Bad Words Filter
            $badwords = trans('bad-words') . ' ';
            $body = str_ireplace($badwords, '*****', Request::input('body'));

            $message = $room->messages()->create([
                'user_id' => Auth::user()->id,
                'user_ip' => Request::ip(),
                'body' => $body,
            ]);

            broadcast(new NewMessage($message));
        }
    }

    public function report(Room $room, Message $message)
    {
        Auth::user()->downVote($message);
        broadcast(new MessageReported($message));

        if ($message->totalDownvotes() < -3) {
            broadcast(new MessageDeleted($message));
            $message->delete();
        }
    }
}
