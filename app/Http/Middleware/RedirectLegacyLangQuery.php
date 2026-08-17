<?php

namespace App\Http\Middleware;

use App\Seo\LocaleUrl;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectLegacyLangQuery
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->has('lang')) {
            return $next($request);
        }

        $lang = $request->query('lang');

        if (! is_string($lang) || ! in_array($lang, LocaleUrl::availableLocales(), true)) {
            return redirect()->to($request->url(), 301);
        }

        $path = LocaleUrl::stripLocalePrefix($request->path());
        $normalizedPath = ltrim($path, '/');

        $target = LocaleUrl::isLocalizablePath($path)
            ? LocaleUrl::localizedPath($normalizedPath === '' ? '' : $normalizedPath, $lang)
            : $request->url();

        $query = $request->query();
        unset($query['lang']);

        if ($query !== []) {
            $target .= '?'.http_build_query($query);
        }

        return redirect()->to($target, 301);
    }
}
