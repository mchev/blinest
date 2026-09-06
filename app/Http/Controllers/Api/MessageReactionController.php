<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageReactionUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessageReactionRequest;
use App\Models\Message;
use App\Models\MessageReaction;
use App\Services\Chat\MessageReactionService;
use Illuminate\Http\JsonResponse;

class MessageReactionController extends Controller
{
    public function __construct(private MessageReactionService $reactions) {}

    public function index(Message $message): JsonResponse
    {
        return response()->json([
            'reactions' => $this->reactions->groupedReactions($message),
            'userReaction' => $this->reactions->userReaction($message, auth()->user()),
        ]);
    }

    public function store(StoreMessageReactionRequest $request, Message $message): JsonResponse
    {
        $user = $request->user();
        $emoji = $request->string('emoji')->toString();

        $reaction = MessageReaction::query()
            ->where('message_id', $message->id)
            ->where('user_id', $user->id)
            ->where('emoji', $emoji)
            ->first();

        if ($reaction) {
            $reaction->delete();

            $reactions = $this->reactions->groupedReactions($message->fresh());
            $userReaction = $this->reactions->userReaction($message->fresh(), $user);
            broadcast(new MessageReactionUpdated($message->id, $reactions, $userReaction));

            return response()->json(['removed' => true]);
        }

        MessageReaction::query()->create([
            'message_id' => $message->id,
            'user_id' => $user->id,
            'emoji' => $emoji,
        ]);

        $freshMessage = $message->fresh();
        $reactions = $this->reactions->groupedReactions($freshMessage);
        $userReaction = $emoji;
        broadcast(new MessageReactionUpdated($message->id, $reactions, $userReaction));

        return response()->json(['added' => true]);
    }
}
