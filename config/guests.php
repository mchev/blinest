<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Guest account creation rate limits (per IP)
    |--------------------------------------------------------------------------
    |
    | Limits how many new guest users a single IP can create. Existing sessions
    | are not affected. Uses ClientIp (Cloudflare / X-Forwarded-For aware).
    |
    */

    'rate_limit' => [
        'per_minute' => (int) env('GUEST_RATE_LIMIT_PER_MINUTE', 3),
        'per_hour' => (int) env('GUEST_RATE_LIMIT_PER_HOUR', 20),
        'per_day' => (int) env('GUEST_RATE_LIMIT_PER_DAY', 50),
    ],

];
