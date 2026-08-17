<?php

namespace App\Seo;

use Illuminate\Http\Request;

class LocaleUrl
{
    public function __construct(protected Request $request) {}

    /**
     * @return list<string>
     */
    public static function availableLocales(): array
    {
        return config('app.available_locales', ['fr', 'en', 'es']);
    }

    /**
     * @return list<string>
     */
    public static function prefixedLocales(): array
    {
        return ['en', 'es'];
    }

    public static function defaultLocale(): string
    {
        return config('app.locale', 'fr');
    }

    public function locale(): string
    {
        return app()->getLocale();
    }

    public static function prefixForLocale(string $locale): string
    {
        return in_array($locale, self::prefixedLocales(), true) ? '/'.$locale : '';
    }

    public static function localeFromPath(string $path): ?string
    {
        $trimmed = trim($path, '/');

        if ($trimmed === 'en' || str_starts_with($trimmed, 'en/')) {
            return 'en';
        }

        if ($trimmed === 'es' || str_starts_with($trimmed, 'es/')) {
            return 'es';
        }

        return null;
    }

    public static function stripLocalePrefix(string $path): string
    {
        $trimmed = trim($path, '/');

        foreach (self::prefixedLocales() as $locale) {
            if ($trimmed === $locale) {
                return '/';
            }

            if (str_starts_with($trimmed, $locale.'/')) {
                $stripped = substr($trimmed, strlen($locale) + 1);

                return $stripped === '' ? '/' : '/'.$stripped;
            }
        }

        return $path === '' ? '/' : (str_starts_with($path, '/') ? $path : '/'.$path);
    }

    public static function isLocalizablePath(string $path): bool
    {
        $path = trim(self::stripLocalePrefix($path), '/');

        if ($path === '') {
            return true;
        }

        if ($path === 'contact' || $path === 'faq') {
            return true;
        }

        if ($path === 'docs' || str_starts_with($path, 'docs/')) {
            return true;
        }

        if (str_starts_with($path, 'pages/')) {
            return true;
        }

        return false;
    }

    public static function localizedPath(string $path, string $locale): string
    {
        $normalized = self::stripLocalePrefix($path);
        $normalized = trim($normalized, '/');
        $prefix = self::prefixForLocale($locale);
        $base = rtrim(config('app.url'), '/');

        if ($normalized === '') {
            return $base.($prefix !== '' ? $prefix : '/');
        }

        return $base.$prefix.'/'.$normalized;
    }

    public function localized(string $locale, ?string $path = null): string
    {
        $path ??= $this->request->path();

        return self::localizedPath($path, $locale);
    }

    public function canonical(?string $path = null): string
    {
        return $this->localized($this->locale(), $path);
    }

    /**
     * @return array<string, string>
     */
    public function alternates(?string $path = null): array
    {
        $path ??= $this->request->path();
        $normalizedPath = self::stripLocalePrefix($path);

        $alternates = [];

        foreach (self::availableLocales() as $locale) {
            $alternates[$locale] = self::localizedPath($normalizedPath, $locale);
        }

        $alternates['x-default'] = self::localizedPath($normalizedPath, self::defaultLocale());

        return $alternates;
    }

    public static function routeName(string $name, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        if ($locale === self::defaultLocale()) {
            return $name;
        }

        return "{$locale}.{$name}";
    }

    public static function isSearchEngineBot(Request $request): bool
    {
        $userAgent = strtolower($request->userAgent() ?? '');

        if ($userAgent === '') {
            return false;
        }

        $bots = [
            'googlebot',
            'bingbot',
            'yandexbot',
            'duckduckbot',
            'slurp',
            'baiduspider',
            'facebot',
            'ia_archiver',
            'applebot',
        ];

        foreach ($bots as $bot) {
            if (str_contains($userAgent, $bot)) {
                return true;
            }
        }

        return false;
    }
}
