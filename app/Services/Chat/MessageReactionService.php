<?php

namespace App\Services\Chat;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class MessageReactionService
{
    /**
     * @return list<array{emoji: string, count: int, users: list<array{id: int, name: string}>}>
     */
    public function groupedReactions(Message $message): array
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

    public function userReaction(Message $message, ?User $user): ?string
    {
        if ($user === null) {
            return null;
        }

        $message->loadMissing('reactions');

        return $message->reactions->firstWhere('user_id', $user->id)?->emoji;
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function appendSummary(array $payload, Message $message, ?User $viewer): array
    {
        $payload['reactions'] = $this->groupedReactions($message);
        $payload['user_reaction'] = $this->userReaction($message, $viewer);

        return $payload;
    }

    /**
     * @param  iterable<Message>  $messages
     */
    public function eagerLoad(iterable $messages): EloquentCollection
    {
        $collection = $messages instanceof EloquentCollection
            ? $messages
            : new EloquentCollection(collect($messages)->all());

        if ($collection->isNotEmpty()) {
            $collection->loadMissing(['reactions.user']);
        }

        return $collection;
    }
}
