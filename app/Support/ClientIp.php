<?php

namespace App\Support;

use Illuminate\Http\Request;

class ClientIp
{
    /**
     * Resolve the client IP behind reverse proxies / Cloudflare.
     *
     * Without this, every request can appear as 127.0.0.1 on Forge/nginx,
     * which makes route throttles shared across all visitors.
     */
    public static function from(Request $request): string
    {
        foreach (self::candidates($request) as $candidate) {
            if (self::isUsableIp($candidate)) {
                return $candidate;
            }
        }

        return $request->ip() ?: '0.0.0.0';
    }

    /**
     * @return list<string>
     */
    private static function candidates(Request $request): array
    {
        $forwardedFor = array_map(
            trim(...),
            explode(',', (string) $request->header('X-Forwarded-For'))
        );

        return array_values(array_filter([
            $request->header('CF-Connecting-IP'),
            ...$forwardedFor,
            $request->header('X-Real-IP'),
            $request->ip(),
        ]));
    }

    private static function isUsableIp(?string $ip): bool
    {
        if (! is_string($ip) || $ip === '') {
            return false;
        }

        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }
}
