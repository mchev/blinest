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
     * Create a new job instance.
     */
    public function __construct(
        public User $user
    ) {
        $this->onQueue('level-calculations');
    }

    /**
     * Execute the job.
     */
    public function handle(LevelCalculator $calculator): void
    {
        $calculator->updateUserLevel($this->user);
    }
}
