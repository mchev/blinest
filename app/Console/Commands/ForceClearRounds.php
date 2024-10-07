<?php

namespace App\Console\Commands;

use App\Models\Room;
use App\Models\Round;
use Illuminate\Console\Command;
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

            return Command::SUCCESS;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('An error occurred: '.$e->getMessage());

            return Command::FAILURE;
        }
    }
}
