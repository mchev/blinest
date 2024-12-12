<?php

namespace App\Console\Commands;

use App\Models\Room;
use App\Models\Round;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class ForceClearRounds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rounds:force-clear {--dry-run : Run the command without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Force clear all playing rooms and unfinished rounds';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        try {
            // Activate maintenance mode
            Artisan::call('down');
            $this->info('Maintenance mode activated');

            // Clear horizon scheduled tasks first
            Artisan::call('horizon:clear', ['--force' => true]);
            $this->info('Horizon scheduled tasks cleared');

            // Terminate horizon
            Artisan::call('horizon:terminate');
            $this->info('Horizon terminated');

            DB::beginTransaction();

            try {
                $roomsAffected = Room::where('is_playing', 1)->count();
                $roundsAffected = Round::whereNull('finished_at')->count();

                if ($dryRun) {
                    $this->info("Dry run: Would update $roomsAffected rooms and $roundsAffected rounds.");
                } else {
                    Room::where('is_playing', 1)->update(['is_playing' => 0]);
                    Round::whereNull('finished_at')->update([
                        'finished_at' => now(),
                        'is_playing' => 0,
                    ]);

                    DB::commit();
                    $this->info("Successfully updated $roomsAffected rooms and $roundsAffected rounds.");
                }

                // Clear cache after DB operations
                Artisan::call('cache:clear');
                $this->info('Cache cleared');

                return Command::SUCCESS;
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e; // Re-throw to be caught by outer try-catch
            }
        } catch (\Exception $e) {
            $this->error('An error occurred: '.$e->getMessage());

            return Command::FAILURE;
        } finally {
            // Always ensure maintenance mode is disabled
            Artisan::call('up');
            $this->info('Maintenance mode disabled');
        }
    }
}
