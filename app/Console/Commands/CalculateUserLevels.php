<?php

namespace App\Console\Commands;

use App\Jobs\UpdateUserLevel;
use App\Models\User;
use Illuminate\Console\Command;

class CalculateUserLevels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:calculate-levels {user_id? : The ID of a specific user to calculate} {--force : Force recalculation for all users}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate and update user levels based on their scores and activity';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $userId = $this->argument('user_id');

        // If a specific user ID is provided, only process that user
        if ($userId !== null) {
            $user = User::find($userId);

            if (! $user) {
                $this->error("User with ID {$userId} not found.");

                return Command::FAILURE;
            }

            $this->info("Dispatching level calculation for user #{$userId} ({$user->name}) to 'level-calculations' queue...");
            UpdateUserLevel::dispatch($user);
            $this->info('Level calculation dispatched. Monitor progress in Horizon dashboard.');

            return Command::SUCCESS;
        }

        // Original behavior for multiple users
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

        $this->info("Dispatching level calculations for {$count} users to 'level-calculations' queue...");
        $this->info('This will run in the background without blocking the site.');

        $bar = $this->getOutput()->createProgressBar($count);
        $bar->start();

        // All calculations from this command use the heavy queue to avoid blocking the site
        $query->chunk(20, function ($users) use ($bar) {
            foreach ($users as $user) {
                // Use dedicated queue for all calculations from this command
                UpdateUserLevel::dispatch($user);
                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine();
        $this->info("Successfully dispatched {$count} level calculations to queue.");
        $this->info('Monitor progress in Horizon dashboard.');

        return Command::SUCCESS;
    }
}
