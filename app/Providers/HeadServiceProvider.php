<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Head\Facades\Head;
use Laravel\Head\Facades\Schema;
use Laravel\Head\HeadBuilder;

class HeadServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerDefaults();
        $this->registerInertiaGlobals();
        $this->registerErrorPages();
    }

    protected function registerDefaults(): void
    {
        Head::defaults(function (HeadBuilder $head): void {
            $appUrl = rtrim(config('app.url'), '/');
            $locale = $this->ogLocale();
            $defaultDescription = __('Play free multiplayer music quizzes! Online blind tests for all tastes: 2000s, Disney, French songs, 80s, Rock, Pop, and more.');
            $defaultOgDescription = __('Default OG description');
            $defaultTitle = __('Default OG title');
            $defaultImage = url('images/statics/screenshot.png');

            $head
                ->title(config('app.name', 'Blinest'), suffix: ' | Blinest')
                ->description($defaultDescription)
                ->searchableByRobots()
                ->og(
                    type: 'website',
                    title: $defaultTitle,
                    description: $defaultOgDescription,
                    url: url()->current(),
                    image: $defaultImage,
                    siteName: 'Blinest',
                    locale: $locale,
                )
                ->ogImage(
                    $defaultImage,
                    alt: __('Default OG image alt'),
                    width: 1200,
                    height: 630,
                )
                ->twitter(
                    card: 'summary_large_image',
                    site: '@blinest',
                    creator: '@blinest',
                    title: $defaultTitle,
                    description: $defaultOgDescription,
                    image: $defaultImage,
                )
                ->twitterImage($defaultImage, alt: __('Default OG image alt'))
                ->schema(Schema::organization()
                    ->name('Blinest')
                    ->url($appUrl)
                    ->logo(url('images/statics/logo_blinest.png')))
                ->schema(Schema::webSite()
                    ->name('Blinest')
                    ->url($appUrl));

            foreach (['en_US', 'es_ES'] as $alternateLocale) {
                if ($alternateLocale !== $locale) {
                    $head->meta('og:locale:alternate', $alternateLocale, property: true);
                }
            }
        });
    }

    protected function registerInertiaGlobals(): void
    {
        Head::inertiaGlobals(function (HeadBuilder $head): void {
            $head
                ->viewport('width=device-width, initial-scale=1, viewport-fit=cover')
                ->colorScheme('dark')
                ->themeColor('#1A1A2E')
                ->applicationName('Blinest')
                ->favicon(asset('favicon.svg'), type: 'image/svg+xml')
                ->icon(asset('favicon-32x32.png'), type: 'image/png', sizes: '32x32')
                ->icon(asset('favicon-16x16.png'), type: 'image/png', sizes: '16x16')
                ->appleTouchIcon(asset('apple-touch-icon.png'), sizes: '180x180')
                ->manifest(asset('manifest.json'))
                ->webAppCapable()
                ->appleWebAppTitle('Blinest')
                ->appleWebAppStatusBarStyle('black-translucent')
                ->meta('format-detection', 'telephone=no');
        });
    }

    protected function registerErrorPages(): void
    {
        Head::errors(function ($errors): void {
            $errors->defaults(fn (HeadBuilder $head) => $head->hiddenFromRobots());
        });
    }

    protected function ogLocale(): string
    {
        return match (app()->getLocale()) {
            'en' => 'en_US',
            'es' => 'es_ES',
            default => 'fr_FR',
        };
    }
}
