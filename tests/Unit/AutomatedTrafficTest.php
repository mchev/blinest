<?php

namespace Tests\Unit;

use App\Support\AutomatedTraffic;
use Illuminate\Http\Request;
use Tests\TestCase;

class AutomatedTrafficTest extends TestCase
{
    public function test_search_engine_bot_skips_guest_session(): void
    {
        $request = Request::create('/rooms/rap-fr', 'GET', server: [
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
        ]);

        $this->assertTrue(AutomatedTraffic::shouldSkipGuestSession($request));
    }

    public function test_ai_crawler_skips_guest_session(): void
    {
        $request = Request::create('/rooms/rap-fr', 'GET', server: [
            'HTTP_USER_AGENT' => 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; GPTBot/1.0; +https://openai.com/gptbot)',
        ]);

        $this->assertTrue(AutomatedTraffic::shouldSkipGuestSession($request));
    }

    public function test_regular_browser_does_not_skip_guest_session(): void
    {
        $request = Request::create('/rooms/rap-fr', 'GET', server: [
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        ]);

        $this->assertFalse(AutomatedTraffic::shouldSkipGuestSession($request));
    }
}
