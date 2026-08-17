<?php

namespace App\Http\Middleware;

use App\Seo\LocaleUrl;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $availableLocales = LocaleUrl::availableLocales();
        $defaultLocale = LocaleUrl::defaultLocale();
        $pathLocale = LocaleUrl::localeFromPath($request->path());

        if ($pathLocale !== null && in_array($pathLocale, LocaleUrl::prefixedLocales(), true)) {
            app()->setLocale($pathLocale);
            session()->put('locale', $pathLocale);
        } elseif (LocaleUrl::isSearchEngineBot($request) && $pathLocale === null) {
            app()->setLocale($defaultLocale);
        } elseif (session()->has('locale')) {
            $locale = session('locale');

            if (is_string($locale) && in_array($locale, $availableLocales, true)) {
                app()->setLocale($locale);
            } else {
                app()->setLocale($defaultLocale);
            }
        } else {
            app()->setLocale($defaultLocale);
        }

        /** @var Response $response */
        $response = $next($request);

        $response->headers->set('Content-Language', app()->getLocale());

        return $response;
    }
}
