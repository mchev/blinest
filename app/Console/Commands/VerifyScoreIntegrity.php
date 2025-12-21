<?php

namespace App\Console\Commands;

use App\Models\Round;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class VerifyScoreIntegrity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scores:verify-integrity {--round-id= : Vérifier un round spécifique} {--fix : Corriger automatiquement les problèmes détectés}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifie l\'intégrité des scores et standings pour détecter les pertes de données';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $roundId = $this->option('round-id');
        $fix = $this->option('fix');

        $this->info('🔍 Vérification de l\'intégrité des scores...');
        $this->newLine();

        $issues = [];

        if ($roundId) {
            $rounds = Round::where('id', $roundId)->get();
        } else {
            // Vérifier tous les rounds terminés
            $rounds = Round::whereNotNull('finished_at')
                ->orderBy('finished_at', 'desc')
                ->get();
        }

        $bar = $this->output->createProgressBar($rounds->count());
        $bar->start();

        foreach ($rounds as $round) {
            $roundIssues = $this->checkRound($round);
            if (! empty($roundIssues)) {
                $issues[$round->id] = $roundIssues;
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        if (empty($issues)) {
            $this->info('✅ Aucun problème détecté ! Tous les scores sont intacts.');

            return Command::SUCCESS;
        }

        $this->error('⚠️  Problèmes détectés :');
        $this->newLine();

        $totalIssues = 0;
        foreach ($issues as $roundId => $roundIssues) {
            $round = Round::find($roundId);
            $roomName = $round->room ? $round->room->name : 'N/A';
            $this->warn("Round #{$roundId} (Room: {$roomName}, Finished: {$round->finished_at})");

            foreach ($roundIssues as $issue) {
                $this->line("  - {$issue}");
                $totalIssues++;
            }
            $this->newLine();
        }

        $this->error("Total: {$totalIssues} problème(s) détecté(s) sur ".count($issues).' round(s)');

        if ($fix) {
            $this->newLine();
            if ($this->confirm('Voulez-vous corriger automatiquement ces problèmes ?', false)) {
                $this->fixIssues($issues);
            }
        } else {
            $this->newLine();
            $this->info('💡 Utilisez --fix pour corriger automatiquement les problèmes');
        }

        return Command::FAILURE;
    }

    /**
     * Vérifie l'intégrité d'un round
     */
    private function checkRound(Round $round): array
    {
        $issues = [];

        // Vérifier si le round a des standings
        $hasStandings = $round->standings()->exists();
        $hasScores = $round->scores()->exists();

        // Cas 1: Le round a des standings mais aussi des scores (normalement supprimés)
        if ($hasStandings && $hasScores) {
            // Vérifier que les totals correspondent
            $standings = $round->standings()->get();
            $scoresByUser = $round->scores()
                ->select('user_id', DB::raw('SUM(score) as total'))
                ->groupBy('user_id')
                ->get()
                ->keyBy('user_id');

            foreach ($standings as $standing) {
                $scoreTotal = $scoresByUser->get($standing->user_id);
                if ($scoreTotal && abs((float) $scoreTotal->total - (float) $standing->total_score) > 0.01) {
                    $issues[] = "User #{$standing->user_id}: total_score dans standings ({$standing->total_score}) ne correspond pas à la somme des scores ({$scoreTotal->total})";
                }
            }
        }

        // Cas 2: Le round a des standings mais pas de scores (normal après nettoyage)
        // C'est OK, pas de problème

        // Cas 3: Le round n'a pas de standings mais a des scores (pas encore traité)
        // C'est OK si le round vient d'être terminé, mais suspect si c'est ancien
        if (! $hasStandings && $hasScores) {
            $finishedAt = $round->finished_at;
            if ($finishedAt && $finishedAt->lt(now()->subHours(24))) {
                $issues[] = 'Round terminé depuis plus de 24h mais pas encore traité (pas de standings)';
            }
        }

        // Cas 4: Le round n'a ni standings ni scores (problème si le round est terminé)
        if (! $hasStandings && ! $hasScores && $round->finished_at) {
            $issues[] = 'Round terminé mais aucun score ni standing trouvé (données perdues ?)';
        }

        // Cas 5: Vérifier que les standings ont bien un total_score
        if ($hasStandings) {
            $standingsWithoutTotal = $round->standings()
                ->whereNull('total_score')
                ->count();

            if ($standingsWithoutTotal > 0) {
                $issues[] = "{$standingsWithoutTotal} standing(s) sans total_score";
            }
        }

        return $issues;
    }

    /**
     * Corrige les problèmes détectés
     */
    private function fixIssues(array $issues): void
    {
        $this->info('🔧 Correction des problèmes...');
        $this->newLine();

        foreach ($issues as $roundId => $roundIssues) {
            $round = Round::find($roundId);
            if (! $round) {
                continue;
            }

            $this->line("Traitement du round #{$roundId}...");

            // Si le round a des standings mais aussi des scores avec des totaux différents
            // On recalcule les totals depuis les scores et on met à jour les standings
            if ($round->standings()->exists() && $round->scores()->exists()) {
                $scoresByUser = $round->scores()
                    ->select('user_id', DB::raw('SUM(score) as total'))
                    ->groupBy('user_id')
                    ->get()
                    ->keyBy('user_id');

                foreach ($round->standings as $standing) {
                    $scoreTotal = $scoresByUser->get($standing->user_id);
                    if ($scoreTotal && abs((float) $scoreTotal->total - (float) $standing->total_score) > 0.01) {
                        $standing->update(['total_score' => (float) $scoreTotal->total]);
                        $this->info("  ✓ Mis à jour total_score pour user #{$standing->user_id}");
                    }
                }
            }

            // Si le round est terminé depuis plus de 24h mais n'a pas de standings
            // On peut essayer de créer les standings (mais attention, les scores peuvent avoir été supprimés)
            if (! $round->standings()->exists() && $round->scores()->exists() && $round->finished_at) {
                $this->warn("  ⚠️  Round non traité détecté. Utilisez 'scores:migrate-to-standings' pour le traiter.");
            }
        }

        $this->newLine();
        $this->info('✅ Correction terminée');
    }
}
