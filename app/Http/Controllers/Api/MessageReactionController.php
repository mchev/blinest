<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageReactionUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessageReactionRequest;
use App\Models\Message;
use App\Models\MessageReaction;
use Illuminate\Http\JsonResponse;

class MessageReactionController extends Controller
{
    public function index(Message $message): JsonResponse
    {
        $reactions = $this->groupedReactions($message);

        $userReaction = $message->reactions()->where('user_id', auth()->id())->first()?->emoji;

        return response()->json([
            'reactions' => $reactions,
            'userReaction' => $userReaction,
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

            $reactions = $this->groupedReactions($message->fresh());
            $userReaction = $message->reactions()->where('user_id', $user->id)->first()?->emoji;
            broadcast(new MessageReactionUpdated($message->id, $reactions, $userReaction));

            return response()->json(['removed' => true]);
        }

        MessageReaction::query()->create([
            'message_id' => $message->id,
            'user_id' => $user->id,
            'emoji' => $emoji,
        ]);

        $reactions = $this->groupedReactions($message->fresh());
        $userReaction = $emoji;
        broadcast(new MessageReactionUpdated($message->id, $reactions, $userReaction));

        return response()->json(['added' => true]);
    }

    /**
     * @return list<array{emoji: string, count: int, users: list<array{id: int, name: string}>}>
     */
    private function groupedReactions(Message $message): array
    {
        $message->loadMissing('reactions.user');

        return $message->reactions
            ->groupBy('emoji')
            ->map(function ($group) {
                return [
                    'emoji' => $group[0]->emoji,
                    'count' => $group->count(),
                    'users' => $group->map(fn ($reaction) => [
                        'id' => $reaction->user->id,
                        'name' => $reaction->user->name,
                    ])->values()->all(),
                ];
            })
            ->values()
            ->all();
    }
}
