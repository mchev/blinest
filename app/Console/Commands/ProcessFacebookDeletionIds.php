<?php

namespace App\Console\Commands;

use App\Services\Auth\FacebookDataDeletionService;
use Illuminate\Console\Command;
use RuntimeException;

class ProcessFacebookDeletionIds extends Command
{
    protected $signature = 'facebook:process-deletion-ids
                            {file : Path to a CSV or plain-text file of Facebook user IDs}
                            {--dry-run : Preview actions without writing to the database}';

    protected $description = 'Process Meta/Facebook data deletion requests from an exported ID file';

    public function handle(FacebookDataDeletionService $facebookDataDeletion): int
    {
        $path = $this->argument('file');

        if (! is_string($path) || ! is_readable($path)) {
            $this->error('The provided file is not readable.');

            return self::FAILURE;
        }

        try {
            $facebookUserIds = $this->readIdsFromFile($path);
        } catch (RuntimeException $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        if ($facebookUserIds === []) {
            $this->warn('No Facebook user IDs found in the file.');

            return self::SUCCESS;
        }

        if ($this->option('dry-run')) {
            $this->warn('Dry run — no database writes.');
        }

        $summary = $facebookDataDeletion->processIds(
            $facebookUserIds,
            source: 'manual',
            dryRun: (bool) $this->option('dry-run'),
        );

        $this->info(sprintf(
            'Processed %d ID(s): %d deleted, %d unlinked, %d not found, %d failed.',
            $summary['processed'],
            $summary['deleted'],
            $summary['unlinked'],
            $summary['not_found'],
            $summary['failed'],
        ));

        return $summary['failed'] > 0 ? self::FAILURE : self::SUCCESS;
    }

    /**
     * @return list<string>
     */
    private function readIdsFromFile(string $path): array
    {
        $contents = file_get_contents($path);

        if ($contents === false) {
            throw new RuntimeException('Unable to read the provided file.');
        }

        $lines = preg_split('/\R+/', $contents) ?: [];
        $ids = [];

        foreach ($lines as $index => $line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            if ($index === 0 && str_contains(strtolower($line), 'user')) {
                continue;
            }

            $columns = str_getcsv($line);
            $candidate = trim((string) ($columns[0] ?? $line));

            if ($candidate !== '') {
                $ids[] = $candidate;
            }
        }

        return array_values(array_unique($ids));
    }
}
