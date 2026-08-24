<?php

namespace App\Http\Controllers;

use App\Events\MessageDeleted;
use App\Events\MessageReported;
use App\Events\NewMessage;
use App\Models\Message;
use App\Models\Room;
use App\Services\Chat\ChatModerationService;
use App\Support\ClientIp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class RoomMessageController extends Controller
{
    public function __construct(
        private ChatModerationService $chatModeration,
    ) {}

    public function store(Room $room)
    {
        if (Auth::user()->isGuest()) {
            abort(403);
        }

        if (Auth::user()->isNotBanned()) {
            Request::validate([
                'body' => ['required', 'max:255'],
            ]);

            $body = Request::string('body')->toString();

            $this->chatModeration->assertCanSend(Auth::user(), $room, $body);

            $message = $room->messages()->create([
                'user_id' => Auth::user()->id,
                'user_ip' => ClientIp::from(Request::instance()),
                'body' => $this->chatModeration->filterBody($body),
            ]);

            $this->chatModeration->recordSentMessage(Auth::user(), $room, $body);

            broadcast(new NewMessage($message));
        }

        return response()->noContent();
    }

    public function report(Room $room, Message $message)
    {
        if (Auth::user()->isGuest()) {
            abort(403);
        }

        Auth::user()->downVote($message);
        broadcast(new MessageReported($message));

        if ($message->totalDownvotes() < -2) {
            broadcast(new MessageDeleted($message));
            $message->delete();
        }

        return response()->noContent();
    }

    public function destroy(Message $message)
    {
        if (Auth::user()->isRoomModerator(Room::find($message->messagable_id)) || Auth::user()->isPublicModerator() || Auth::user()->isAdministrator()) {
            broadcast(new MessageDeleted($message));
            $message->delete();
        }

        return response()->noContent();
    }

    public function restore($id)
    {
        if (Auth::user()->isPublicModerator() || Auth::user()->isAdministrator()) {
            Message::withTrashed()->findOrFail($id)->restore();

            return redirect()->back()->with('success', __('Message restored'));
        }

        abort(403);
    }
}
