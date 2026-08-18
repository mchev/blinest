<?php

namespace App\Console\Commands;

use App\Models\RoundStanding;
use App\Services\RoundPerformanceMetricsService;
use Illuminate\Console\Command;

class BackfillRoundStandingPerformanceMetrics extends Command
{
    protected $signature = 'standings:backfill-performance-metrics
                            {--force : Recalculate metrics even when average_response_time is already set}
                            {--dry-run : Show what would be updated without writing to the database}
                            {--chunk=500 : Number of standings to process per batch}
                            {--limit= : Maximum number of standings to process}';

    protected $description = 'Backfill average response time and related performance metrics from tracks_history';

    public function handle(RoundPerformanceMetricsService $metricsService): int
    {
        $force = (bool) $this->option('force');
        $dryRun = (bool) $this->option('dry-run');
        $chunkSize = max(1, (int) $this->option('chunk'));
        $limit = $this->option('limit') !== null ? max(1, (int) $this->option('limit')) : null;

        $query = RoundStanding::query()
            ->whereNotNull('tracks_history')
            ->whereJsonLength('tracks_history', '>', 0)
            ->when(! $force, fn ($builder) => $builder->whereNull('average_response_time'))
            ->orderBy('id');

        $total = (clone $query)->count();

        if ($total === 0) {
            $this->info('No standings need performance metric backfill.');

            return Command::SUCCESS;
        }

        $toProcess = $limit !== null ? min($total, $limit) : $total;

        $this->info("Found {$total} standing(s) to process.");
        $this->info("Processing {$toProcess} standing(s)...");

        if ($dryRun) {
            $this->warn('DRY RUN MODE - No changes will be written');
        }

        if ($force) {
            $this->warn('FORCE MODE - Existing metrics will be recalculated');
        }

        $this->newLine();

        $processed = 0;
        $updated = 0;
        $unchanged = 0;
        $skipped = 0;

        $bar = $this->output->createProgressBar($toProcess);
        $bar->start();

        $query->with(['round.room'])->chunkById($chunkSize, function ($standings) use (
            $metricsService,
            $dryRun,
            $limit,
            &$processed,
            &$updated,
            &$unchanged,
            &$skipped,
            $bar,
        ) {
            foreach ($standings as $standing) {
                if ($limit !== null && $processed >= $limit) {
                    return false;
                }

                $processed++;

                $metrics = $metricsService->forStanding($standing);

                if ($metrics['total_answers_count'] === 0) {
                    $skipped++;
                    $bar->advance();

                    continue;
                }

                $hasChanges = $this->metricsChanged($standing, $metrics);

                if (! $hasChanges) {
                    $unchanged++;
                    $bar->advance();

                    continue;
                }

                if (! $dryRun) {
                    $standing->update([
                        'average_response_time' => $metrics['average_response_time'],
                        'fast_answers_count' => $metrics['fast_answers_count'],
                        'total_answers_count' => $metrics['total_answers_count'],
                    ]);
                }

                $updated++;
                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine(2);

        $this->table(
            ['Metric', 'Count'],
            [
                ['Processed', $processed],
                ['Updated', $updated],
                ['Unchanged', $unchanged],
                ['Skipped (empty history)', $skipped],
            ],
        );

        if ($dryRun) {
            $this->warn('Dry run complete. Re-run without --dry-run to apply changes.');
        } else {
            $this->info('Backfill complete.');
        }

        return Command::SUCCESS;
    }

    /**
     * @param  array{
     *     average_response_time: float|null,
     *     fast_answers_count: int,
     *     total_answers_count: int
     * }  $metrics
     */
    private function metricsChanged(RoundStanding $standing, array $metrics): bool
    {
        $currentAverage = $standing->average_response_time === null
            ? null
            : round((float) $standing->average_response_time, 3);

        $nextAverage = $metrics['average_response_time'];

        return $currentAverage !== $nextAverage
            || (int) $standing->fast_answers_count !== $metrics['fast_answers_count']
            || (int) $standing->total_answers_count !== $metrics['total_answers_count'];
    }
}
