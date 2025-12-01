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
    public $queue = 'level-calculations';

    /**
     * Create a new job instance.
     */
    public function __construct(
        public User $user,
        public ?\DateTimeInterface $loginDate = null
    ) {
        // Auto-detect if this is an initial calculation (no userLevel exists)
        // Initial calculations are expensive and should use the heavy queue
        $isInitialCalculation = ! $this->user->userLevel;
    }

    /**
     * Execute the job.
     */
    public function handle(LevelCalculator $calculator): void
    {
        // $calculator->updateUserLevel($this->user, $this->loginDate);

    }
}
