<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $availableLocales = config('app.available_locales', ['fr', 'en']);
        $defaultLocale = config('app.locale', 'fr');

        // Si l'utilisateur a déjà choisi une langue dans la session, l'utiliser
        if (session()->has('locale')) {
            $locale = session('locale');
            if (in_array($locale, $availableLocales)) {
                app()->setLocale($locale);

                return $next($request);
            }
        }

        // Sinon, essayer de détecter la langue du navigateur
        $browserLocale = $this->detectBrowserLocale($request, $availableLocales);
        if ($browserLocale) {
            app()->setLocale($browserLocale);
            // Sauvegarder la détection automatique en session pour éviter de redétecter à chaque requête
            session()->put('locale', $browserLocale);
        } else {
            // Utiliser la locale par défaut
            app()->setLocale($defaultLocale);
        }

        return $next($request);
    }

    /**
     * Detect browser locale from Accept-Language header
     */
    private function detectBrowserLocale(Request $request, array $availableLocales): ?string
    {
        $acceptLanguage = $request->header('Accept-Language');

        if (! $acceptLanguage) {
            return null;
        }

        // Parser l'en-tête Accept-Language
        // Format: "fr-FR,fr;q=0.9,en-US;q=0.8,en;q=0.7"
        $languages = [];
        $parts = explode(',', $acceptLanguage);

        foreach ($parts as $part) {
            $part = trim($part);
            if (strpos($part, ';') !== false) {
                [$locale, $quality] = explode(';', $part);
                $quality = (float) str_replace('q=', '', $quality);
            } else {
                $locale = $part;
                $quality = 1.0;
            }

            // Extraire le code de langue principal (ex: "fr" depuis "fr-FR")
            $locale = strtolower(explode('-', $locale)[0]);

            if (in_array($locale, $availableLocales)) {
                $languages[$locale] = $quality;
            }
        }

        if (empty($languages)) {
            return null;
        }

        // Trier par qualité décroissante
        arsort($languages);

        // Retourner la première langue disponible avec la meilleure qualité
        return array_key_first($languages);
    }
}
