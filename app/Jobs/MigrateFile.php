<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class MigrateFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected string $sourceFile, protected string $targetFile)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Storage::disk('contabo')->put($this->targetFile, Storage::disk('local')->get($this->sourceFile));

        // if (Storage::disk('contabo')->put($this->targetFile, Storage::disk('local')->get($this->sourceFile))) {
        //     Storage::disk('local')->delete($this->sourceFile);
        // }
    }
}
