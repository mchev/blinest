<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Room;
use Illuminate\Console\Command;

class ExportOfficialRoomSlugs extends Command
{
    protected $signature = 'rooms:export-official-slugs
                            {--dry-run : Print slugs without writing config}';

    protected $description = 'Export production official public room slugs into config/official_room_seo.php';

    public function handle(): int
    {
        $excludedCategorySlugs = [
            'argent',
            'c',
            'pop',
            'pourpre-heraldique',
            'rock',
            't',
        ];

        $excludedCategoryIds = Category::query()
            ->whereIn('slug', $excludedCategorySlugs)
            ->pluck('id')
            ->all();

        $slugs = Room::query()
            ->isPublic()
            ->whereNull('password')
            ->when($excludedCategoryIds !== [], fn ($query) => $query->whereNotIn('category_id', $excludedCategoryIds))
            ->where(function ($query): void {
                $query->where('slug', 'not like', 'test%')
                    ->where('slug', 'not like', 'room-%')
                    ->where('slug', 'not like', '%-test%')
                    ->where('slug', 'not like', 'audit-%')
                    ->where('slug', 'not like', 'my-%');
            })
            ->orderBy('name')
            ->pluck('slug')
            ->values()
            ->all();

        if ($slugs === []) {
            $this->error('No official room slugs found.');

            return Command::FAILURE;
        }

        $this->info('Found '.count($slugs).' official room slug(s):');
        $this->line(implode(', ', $slugs));

        if ($this->option('dry-run')) {
            return Command::SUCCESS;
        }

        $configPath = config_path('official_room_seo.php');
        $existing = config('official_room_seo.intros', []);
        $introsExport = $this->exportIntros($existing, $slugs);

        $content = $this->buildConfigFile($slugs, $introsExport);
        file_put_contents($configPath, $content);

        $this->info("Updated {$configPath}");

        return Command::SUCCESS;
    }

    /**
     * @param  array<string, mixed>  $existing
     * @param  list<string>  $slugs
     * @return array<string, mixed>
     */
    private function exportIntros(array $existing, array $slugs): array
    {
        $intros = [];

        foreach ($slugs as $slug) {
            if (isset($existing[$slug])) {
                $intros[$slug] = $existing[$slug];
            }
        }

        return $intros;
    }

    /**
     * @param  list<string>  $slugs
     * @param  array<string, mixed>  $intros
     */
    private function buildConfigFile(array $slugs, array $intros): string
    {
        $slugExport = var_export($slugs, true);
        $introExport = var_export($intros, true);

        return <<<PHP
<?php

/**
 * Official Blinest moderator rooms (production slugs).
 * Regenerate on production with: php artisan rooms:export-official-slugs
 */
return [

    'slugs' => {$slugExport},

    'intros' => {$introExport},

];

PHP;
    }
}
