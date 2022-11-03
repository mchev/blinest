<?php

namespace App\Console\Commands;

use Spatie\Sitemap\SitemapGenerator;
use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automaticaly generate a sitemap';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        SitemapGenerator::create(env('APP_URL'))->getSitemap()->writeToDisk('public', 'sitemap.xml');
    }
}
