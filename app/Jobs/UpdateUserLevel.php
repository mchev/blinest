<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\LevelCalculator;
use App\Services\Profiles\ProfileCacheService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\Attributes\UniqueFor;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

#[UniqueFor(120)]
class UpdateUserLevel implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public User $user,
        public ?string $type = null,
    ) {
        $this->onQueue('level-calculations');
        $this->tries = 3;
        $this->afterCommit();
    }

    public function uniqueId(): string
    {
        return (string) $this->user->id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->user->isGuest()) {
            return;
        }

        $type = $this->type ?? 'score';
        $calculator = new LevelCalculator($this->user, $type);
        $calculator->update();

        app(ProfileCacheService::class)->forget($this->user);
    }
}
