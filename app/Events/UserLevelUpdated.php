<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserLevelUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public User $user,
        public array $levelData
    ) {
        // Ensure user is fresh (important when event is dispatched from queued jobs)
        $this->user->refresh();
        // Reload the userLevel relation to get the latest data
        $this->user->load('userLevel');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('App.Models.User.'.$this->user->id),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'user.level.updated';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        $userLevel = $this->user->userLevel;

        return [
            'level' => $this->levelData['level'],
            'current_xp' => $this->levelData['current_xp'],
            'xp_for_next_level' => $this->levelData['xp_for_next_level'],
            'total_xp' => $this->levelData['total_xp'],
            'level_metrics' => $userLevel ? [
                'score_public_rooms' => $userLevel->score_public_rooms ?? 0,
                'seniority_months' => $userLevel->months_seniority ?? 0,
                'consecutive_days_streak' => $userLevel->consecutive_days_streak ?? 0,
                'rooms_created_count' => $userLevel->rooms_created_count ?? 0,
                'playlists_created_count' => $userLevel->playlists_created_count ?? 0,
                'tracks_liked_count' => $userLevel->tracks_liked_count ?? 0,
                'has_team' => $this->user->hasTeam(),
            ] : null,
        ];
    }
}
