<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageReactionUpdated;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\MessageReaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageReactionController extends Controller
{
    // Liste les réactions d'un message
    public function index(Message $message)
    {
        $reactions = $message->reactions
            ->groupBy('emoji')
            ->map(function ($group) {
                return [
                    'emoji' => $group[0]->emoji,
                    'count' => $group->count(),
                    'users' => $group->map(fn ($r) => [
                        'id' => $r->user->id,
                        'name' => $r->user->name,
                    ])->values(),
                ];
            })->values();

        $userReaction = $message->reactions()->where('user_id', auth()->id())->first()?->emoji;

        return response()->json([
            'reactions' => $reactions,
            'userReaction' => $userReaction,
        ]);
    }

    // Ajoute ou retire une réaction
    public function store(Request $request, Message $message)
    {
        $request->validate([
            'emoji' => 'required|string|max:191',
        ]);
        $user = Auth::user();
        $reaction = MessageReaction::where('message_id', $message->id)
            ->where('user_id', $user->id)
            ->where('emoji', $request->emoji)
            ->first();
        if ($reaction) {
            $reaction->delete();
            $reactions = $message->reactions
                ->groupBy('emoji')
                ->map(function ($group) {
                    return [
                        'emoji' => $group[0]->emoji,
                        'count' => $group->count(),
                        'users' => $group->map(fn ($r) => [
                            'id' => $r->user->id,
                            'name' => $r->user->name,
                        ])->values(),
                    ];
                })->values();
            $userReaction = $message->reactions()->where('user_id', auth()->id())->first()?->emoji;
            broadcast(new MessageReactionUpdated($message->id, $reactions, $userReaction));

            return response()->json(['removed' => true]);
        } else {
            MessageReaction::create([
                'message_id' => $message->id,
                'user_id' => $user->id,
                'emoji' => $request->emoji,
            ]);
            $reactions = $message->reactions
                ->groupBy('emoji')
                ->map(function ($group) {
                    return [
                        'emoji' => $group[0]->emoji,
                        'count' => $group->count(),
                        'users' => $group->map(fn ($r) => [
                            'id' => $r->user->id,
                            'name' => $r->user->name,
                        ])->values(),
                    ];
                })->values();
            $userReaction = $message->reactions()->where('user_id', auth()->id())->first()?->emoji;
            broadcast(new MessageReactionUpdated($message->id, $reactions, $userReaction));

            return response()->json(['added' => true]);
        }
    }
}
