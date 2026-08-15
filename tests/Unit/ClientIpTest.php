<?php

namespace Tests\Unit;

use App\Support\ClientIp;
use Illuminate\Http\Request;
use Tests\TestCase;

class ClientIpTest extends TestCase
{
    public function test_prefers_cloudflare_connecting_ip(): void
    {
        $request = Request::create('/rooms/test/guest-join', 'GET', server: [
            'REMOTE_ADDR' => '127.0.0.1',
            'HTTP_CF_CONNECTING_IP' => '203.0.113.10',
            'HTTP_X_FORWARDED_FOR' => '198.51.100.5',
        ]);

        $this->assertSame('203.0.113.10', ClientIp::from($request));
    }

    public function test_uses_first_valid_ip_from_x_forwarded_for(): void
    {
        $request = Request::create('/rooms/test/guest-join', 'GET', server: [
            'REMOTE_ADDR' => '127.0.0.1',
            'HTTP_X_FORWARDED_FOR' => '203.0.113.20, 198.51.100.5',
        ]);

        $this->assertSame('203.0.113.20', ClientIp::from($request));
    }

    public function test_falls_back_to_request_ip(): void
    {
        $request = Request::create('/rooms/test/guest-join', 'GET', server: [
            'REMOTE_ADDR' => '203.0.113.30',
        ]);

        $this->assertSame('203.0.113.30', ClientIp::from($request));
    }
}
