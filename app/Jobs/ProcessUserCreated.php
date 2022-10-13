<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\Welcome;
use App\Services\SendinblueService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessUserCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private User $user)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Send a welcome email
        $this->user->notify(new Welcome);

        // Create sendinblue contact
        (new SendinblueService)->contacts()->create($this->user);
    }
}
