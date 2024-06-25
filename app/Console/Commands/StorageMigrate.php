<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StorageMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:migrate {sourceDisk=local} {targetDisk=s3} {--delete : Delete files from source after migration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfer all the files from the source disk to the target disk';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sourceDisk = $this->argument('sourceDisk');
        $targetDisk = $this->argument('targetDisk');
        $deleteAfterMigration = $this->option('delete');

        try {
            $files = Storage::disk($sourceDisk)->allFiles();
        } catch (\Exception $e) {
            Log::error('Failed to retrieve files from source storage: '.$e->getMessage());
            $this->error('Failed to retrieve files from source storage.');

            return;
        }

        foreach ($files as $file) {
            if (! $this->shouldIgnoreFile($file)) {
                $this->migrateFile($file, $sourceDisk, $targetDisk, $deleteAfterMigration);
            }
        }
    }

    /**
     * Determine if the file should be ignored.
     */
    private function shouldIgnoreFile(string $file): bool
    {
        return Str::of($file)->contains(['.DS_Store', '.gitignore', '.glide-cache']);
    }

    /**
     * Migrate a single file from source to target disk.
     */
    private function migrateFile(string $file, string $sourceDisk, string $targetDisk, bool $deleteAfterMigration)
    {
        try {
            $fileContents = Storage::disk($sourceDisk)->get($file);
            Storage::disk($targetDisk)->put($file, $fileContents);
            $this->info("File '{$file}' migrated to '{$targetDisk}'");

            if ($deleteAfterMigration) {
                Storage::disk($sourceDisk)->delete($file);
                $this->info("File '{$file}' deleted from '{$sourceDisk}'");
            }
        } catch (\Exception $e) {
            Log::error("Error processing file '{$file}': ".$e->getMessage());
            $this->error("Failed to process file '{$file}'. Check logs for details.");
        }
    }
}
