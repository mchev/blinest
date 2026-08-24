<?php

namespace App\Jobs;

use App\Models\FacebookDataDeletionRequest;
use App\Services\Auth\FacebookDataDeletionService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessFacebookDataDeletion implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public FacebookDataDeletionRequest $deletionRequest,
    ) {}

    public function handle(FacebookDataDeletionService $facebookDataDeletion): void
    {
        $facebookDataDeletion->process($this->deletionRequest);
    }
}
