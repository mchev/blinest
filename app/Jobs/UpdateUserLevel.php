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
        public User $user,
        public ?string $type = null,
    ) {
        $this->onQueue('level-calculations');
        $this->tries = 3;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $type = $this->type ?? 'score';
        $calculator = new LevelCalculator($this->user, $type);
        $calculator->update();
    }
}
