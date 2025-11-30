<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\LevelCalculator;
use Illuminate\Console\Command;

class CalculateUserLevels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:calculate-levels {--force : Force recalculation for all users}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate and update user levels based on their scores and activity';

    /**
     * Execute the console command.
     */
    public function handle(LevelCalculator $calculator): int
    {
        $query = User::query();

        if (! $this->option('force')) {
            // Only calculate for users without a level or with outdated calculation
            // Update if: no level exists, or last calculation was more than 1 hour ago
            // This ensures seniority (months) and other metrics stay up to date
            $query->whereDoesntHave('userLevel')
                ->orWhereHas('userLevel', function ($q) {
                    $q->where('last_calculated_at', '<', now()->subHour());
                });
        }

        $count = $query->count();

        if ($count === 0) {
            $this->info('No users need level calculation.');

            return Command::SUCCESS;
        }

        $this->info("Calculating levels for {$count} users...");

        $bar = $this->getOutput()->createProgressBar($count);
        $bar->start();

        $query->chunk(100, function ($users) use ($calculator, $bar) {
            foreach ($users as $user) {
                $calculator->updateUserLevel($user);
                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine();
        $this->info("Successfully calculated levels for {$count} users.");

        return Command::SUCCESS;
    }
}
