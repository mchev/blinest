<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\LevelCalculator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateUserLevel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 300;

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public User $user,
        public ?\DateTimeInterface $loginDate = null,
        public bool $useHeavyQueue = false
    ) {
        // Use dedicated queue for heavy calculations (initial or batch)
        // This prevents blocking the main queue with expensive operations
        $this->queue = $useHeavyQueue ? 'level-calculations' : null;
    }

    /**
     * Execute the job.
     */
    public function handle(LevelCalculator $calculator): void
    {
        // Refresh user to ensure we have the latest data after queue serialization
        $this->user->refresh();

        $calculator->updateUserLevel($this->user, $this->loginDate);
    }
}
