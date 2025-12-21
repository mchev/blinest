<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class AdjustEloInflation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elo:adjust-inflation
                            {--dry-run : Show what would be done without actually doing it}
                            {--target=1500 : Target average ELO}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adjust ELO ratings to prevent inflation/deflation by maintaining target average';

    /**
     * ELO initial cible
     */
    private const TARGET_ELO = 1500;

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $targetElo = (int) $this->option('target');
        $dryRun = $this->option('dry-run');

        $this->info('Calculating ELO inflation/deflation adjustment...');
        $this->newLine();

        // Calculer la moyenne ELO actuelle (seulement pour les joueurs qui ont joué au moins un round)
        $averageElo = User::whereHas('roundStandings', function ($query) {
            $query->where('is_elo_counted', true);
        })->avg('elo') ?? self::TARGET_ELO;

        $this->info("Current average ELO: {$averageElo}");
        $this->info("Target average ELO: {$targetElo}");

        $difference = $targetElo - $averageElo;

        if (abs($difference) < 1) {
            $this->info('ELO average is already close to target. No adjustment needed.');

            return Command::SUCCESS;
        }

        $this->newLine();
        $this->info("Difference: {$difference} points");

        if ($dryRun) {
            $this->warn('DRY RUN MODE - No changes will be made');
            $this->newLine();
            $this->info("Would adjust all ELOs by: {$difference} points");

            return Command::SUCCESS;
        }

        // Ajuster tous les ELO pour ramener la moyenne au target
        $this->info('Adjusting ELOs...');
        $this->newLine();

        $bar = $this->output->createProgressBar(User::count());
        $bar->start();

        $adjusted = 0;
        User::chunk(500, function ($users) use ($difference, &$adjusted, $bar) {
            foreach ($users as $user) {
                $newElo = max(100, (int) round($user->elo + $difference)); // Minimum 100
                $user->update(['elo' => $newElo]);
                $adjusted++;
                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine(2);

        $this->info("Successfully adjusted {$adjusted} user ELOs by {$difference} points.");
        $this->info("New average ELO should be close to {$targetElo}.");

        return Command::SUCCESS;
    }
}
