<?php

namespace App\Support;

use App\Seo\LocaleUrl;
use Illuminate\Http\Request;

class AutomatedTraffic
{
    /**
     * Clients that should not receive a guest session (crawlers, scrapers, AI bots).
     */
    public static function shouldSkipGuestSession(Request $request): bool
    {
        if (LocaleUrl::isSearchEngineBot($request)) {
            return true;
        }

        $userAgent = strtolower($request->userAgent() ?? '');

        if ($userAgent === '') {
            return false;
        }

        $automatedClients = [
            'gptbot',
            'chatgpt-user',
            'oai-searchbot',
            'claudebot',
            'anthropic-ai',
            'bytespider',
            'ccbot',
            'amazonbot',
            'petalbot',
            'semrushbot',
            'ahrefsbot',
            'dotbot',
            'mj12bot',
            'python-requests',
            'curl/',
            'wget/',
            'go-http-client',
            'headlesschrome',
            'scrapy',
            'puppeteer',
            'playwright',
        ];

        foreach ($automatedClients as $needle) {
            if (str_contains($userAgent, $needle)) {
                return true;
            }
        }

        return false;
    }
}
