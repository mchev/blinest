<?php

namespace Tests\Unit;

use App\Seo\LocaleUrl;
use Illuminate\Http\Request;
use Tests\TestCase;

class LocaleUrlTest extends TestCase
{
    public function test_locale_from_path_detects_prefixed_locales(): void
    {
        $this->assertSame('en', LocaleUrl::localeFromPath('en'));
        $this->assertSame('en', LocaleUrl::localeFromPath('en/docs/howto'));
        $this->assertSame('es', LocaleUrl::localeFromPath('es/contact'));
        $this->assertNull(LocaleUrl::localeFromPath('docs/howto'));
        $this->assertNull(LocaleUrl::localeFromPath('rooms/quiz-general'));
    }

    public function test_strip_locale_prefix_removes_prefixed_segments(): void
    {
        $this->assertSame('/', LocaleUrl::stripLocalePrefix('en'));
        $this->assertSame('/docs/howto', LocaleUrl::stripLocalePrefix('en/docs/howto'));
        $this->assertSame('/rooms/quiz-general', LocaleUrl::stripLocalePrefix('es/rooms/quiz-general'));
        $this->assertSame('/contact', LocaleUrl::stripLocalePrefix('contact'));
    }

    public function test_is_localizable_path_for_seo_pages(): void
    {
        $this->assertTrue(LocaleUrl::isLocalizablePath('/'));
        $this->assertTrue(LocaleUrl::isLocalizablePath('docs'));
        $this->assertTrue(LocaleUrl::isLocalizablePath('docs/howto'));
        $this->assertTrue(LocaleUrl::isLocalizablePath('contact'));
        $this->assertTrue(LocaleUrl::isLocalizablePath('faq'));
        $this->assertTrue(LocaleUrl::isLocalizablePath('pages/terms'));
        $this->assertTrue(LocaleUrl::isLocalizablePath('en/docs/howto'));
    }

    public function test_is_localizable_path_for_app_pages(): void
    {
        $this->assertFalse(LocaleUrl::isLocalizablePath('rooms/quiz-general'));
        $this->assertFalse(LocaleUrl::isLocalizablePath('en/rooms/quiz-general'));
        $this->assertFalse(LocaleUrl::isLocalizablePath('login'));
    }

    public function test_localized_path_uses_prefix_for_non_default_locales(): void
    {
        $this->assertSame(
            rtrim(config('app.url'), '/').'/docs/howto',
            LocaleUrl::localizedPath('docs/howto', 'fr'),
        );

        $this->assertSame(
            rtrim(config('app.url'), '/').'/en/docs/howto',
            LocaleUrl::localizedPath('docs/howto', 'en'),
        );

        $this->assertSame(
            rtrim(config('app.url'), '/').'/es/pages/terms',
            LocaleUrl::localizedPath('pages/terms', 'es'),
        );
    }

    public function test_alternates_include_all_locales_and_x_default(): void
    {
        $localeUrl = new LocaleUrl(Request::create('/docs/howto'));
        $alternates = $localeUrl->alternates('docs/howto');

        $this->assertSame(
            [
                'fr' => rtrim(config('app.url'), '/').'/docs/howto',
                'en' => rtrim(config('app.url'), '/').'/en/docs/howto',
                'es' => rtrim(config('app.url'), '/').'/es/docs/howto',
                'x-default' => rtrim(config('app.url'), '/').'/docs/howto',
            ],
            $alternates,
        );
    }

    public function test_route_name_prefixes_non_default_locales(): void
    {
        $this->assertSame('home', LocaleUrl::routeName('home', 'fr'));
        $this->assertSame('en.home', LocaleUrl::routeName('home', 'en'));
        $this->assertSame('es.home', LocaleUrl::routeName('home', 'es'));
    }

    public function test_instance_canonical_uses_current_locale(): void
    {
        app()->setLocale('en');

        $localeUrl = new LocaleUrl(Request::create('/en/docs/howto'));

        $this->assertSame(
            rtrim(config('app.url'), '/').'/en/docs/howto',
            $localeUrl->canonical('docs/howto'),
        );
    }
}
