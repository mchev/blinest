<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class DeployOptimize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy:optimize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize application for deployment without clearing Redis cache';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🚀 Optimizing application for deployment...');
        $this->newLine();

        // Clear route cache (needed for Ziggy to pick up new routes)
        $this->info('Clearing route cache...');
        Artisan::call('route:clear');
        $this->line('  ✓ Route cache cleared');

        // Clear config cache (in case config changed)
        $this->info('Clearing config cache...');
        Artisan::call('config:clear');
        $this->line('  ✓ Config cache cleared');

        // Clear view cache (in case views changed)
        $this->info('Clearing view cache...');
        Artisan::call('view:clear');
        $this->line('  ✓ View cache cleared');

        // Clear event cache (in case events/listeners changed)
        $this->info('Clearing event cache...');
        Artisan::call('event:clear');
        $this->line('  ✓ Event cache cleared');

        // Regenerate Ziggy routes (needed for new routes to be available in frontend)
        $this->info('Regenerating Ziggy routes...');
        Artisan::call('ziggy:generate');
        $this->line('  ✓ Ziggy routes regenerated');

        // Cache optimized versions (for production performance)
        $this->info('Caching optimized files...');
        Artisan::call('config:cache');
        $this->line('  ✓ Config cached');

        Artisan::call('route:cache');
        $this->line('  ✓ Routes cached');

        Artisan::call('view:cache');
        $this->line('  ✓ Views cached');

        Artisan::call('event:cache');
        $this->line('  ✓ Events cached');

        $this->newLine();
        $this->info('✅ Application optimized successfully!');
        $this->comment('Note: Redis cache was NOT cleared to preserve scores and other cached data.');

        return Command::SUCCESS;
    }
}
