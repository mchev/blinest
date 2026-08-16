<?php

namespace App\Console\Commands;

use App\Jobs\ProcessRoundFinalization;
use App\Models\Round;
use App\Services\RoundFinalizationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MigrateExistingScoresToStandings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scores:migrate-to-standings {--batch-size=100 : Number of rounds to process per batch} {--limit= : Maximum number of rounds to process (useful for testing)} {--dry-run : Show what would be done without actually doing it} {--queue : Dispatch jobs to queue instead of processing synchronously}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate existing scores to round_standings table for all finished rounds';

    /**
     * Execute the console command.
     */
    public function handle(RoundFinalizationService $roundFinalizationService): int
    {
        $this->info('Starting migration of existing scores to standings...');
        $this->newLine();

        // Trouver tous les rounds terminés qui n'ont pas encore de standings
        $query = Round::whereNotNull('finished_at')
            ->whereDoesntHave('standings')
            ->orderBy('id');

        $totalRounds = $query->count();

        if ($totalRounds === 0) {
            $this->info('No rounds need migration. All finished rounds already have standings.');

            return Command::SUCCESS;
        }

        $limit = $this->option('limit') ? (int) $this->option('limit') : null;
        $batchSize = (int) $this->option('batch-size');
        $dryRun = $this->option('dry-run');
        $useQueue = $this->option('queue');

        if ($limit) {
            $query->limit($limit);
            $this->info("Processing up to {$limit} rounds (out of {$totalRounds} total)...");
        } else {
            $this->info("Processing {$totalRounds} rounds...");
        }

        if ($dryRun) {
            $this->warn('DRY RUN MODE - No changes will be made');
        }

        if ($useQueue) {
            $this->info('Jobs will be dispatched to queue instead of processing synchronously');
        }

        $this->newLine();

        $processed = 0;
        $errors = 0;
        $skipped = 0;

        $bar = $this->output->createProgressBar($limit ?? $totalRounds);
        $bar->start();

        $query->chunk($batchSize, function ($rounds) use ($roundFinalizationService, &$processed, &$errors, &$skipped, $dryRun, $useQueue, $bar) {
            foreach ($rounds as $round) {
                try {
                    // Vérifier à nouveau qu'il n'y a pas déjà de standings (au cas où)
                    if ($round->standings()->exists()) {
                        $skipped++;
                        $bar->advance();

                        continue;
                    }

                    // Charger la relation room
                    $round->load('room');

                    // Vérifier que le round a des scores
                    if (! $round->scores()->exists()) {
                        $skipped++;
                        $bar->advance();

                        continue;
                    }

                    if ($dryRun) {
                        $scoreCount = $round->scores()->count();
                        $userCount = $round->scores()->distinct('user_id')->count('user_id');
                        $roomName = $round->room ? $round->room->name : 'N/A';
                        $this->newLine();
                        $this->line("Would process round #{$round->id} (room: {$roomName}, scores: {$scoreCount}, users: {$userCount})");
                        $processed++;
                    } elseif ($useQueue) {
                        ProcessRoundFinalization::dispatch($round)
                            ->onQueue('default');
                        $processed++;
                    } else {
                        $roundFinalizationService->finalize($round);
                        $processed++;
                    }

                    $bar->advance();

                    // Libérer la mémoire après chaque round
                    unset($round);
                } catch (\Exception $e) {
                    $errors++;
                    Log::error('MigrateExistingScoresToStandings: Error processing round', [
                        'round_id' => $round->id ?? null,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);

                    $roundId = $round->id ?? 'unknown';
                    $this->newLine();
                    $this->error("Error processing round #{$roundId}: {$e->getMessage()}");
                    $bar->advance();
                }
            }

            // Libérer la mémoire après chaque batch
            unset($rounds);
            if (function_exists('gc_collect_cycles')) {
                gc_collect_cycles();
            }
        });

        $bar->finish();
        $this->newLine(2);

        // Afficher le résumé
        $this->info('Migration completed!');
        $this->table(
            ['Status', 'Count'],
            [
                ['Processed', $processed],
                ['Skipped (no scores)', $skipped],
                ['Errors', $errors],
                ['Total', $processed + $skipped + $errors],
            ]
        );

        if ($errors > 0) {
            $this->warn("{$errors} errors occurred. Check logs for details.");

            return Command::FAILURE;
        }

        if ($useQueue) {
            $this->info('Jobs have been dispatched to queue. Monitor progress in Horizon dashboard.');
        }

        return Command::SUCCESS;
    }
}
