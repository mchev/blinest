<?php

namespace App\Services\Chat;

use App\Events\MessageDeleted;
use App\Models\Message;
use App\Models\User;

class BannedUserMessageCleanupService
{
    public function cleanup(User $user): int
    {
        $deletedCount = 0;

        Message::query()
            ->where('user_id', $user->id)
            ->orderBy('id')
            ->chunkById(100, function ($messages) use (&$deletedCount): void {
                foreach ($messages as $message) {
                    broadcast(new MessageDeleted($message));
                    $message->delete();
                    $deletedCount++;
                }
            });

        return $deletedCount;
    }
}
