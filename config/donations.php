<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Monthly server funding goal (in euro cents)
    |--------------------------------------------------------------------------
    */
    'monthly_goal_cents' => (int) env('DONATIONS_MONTHLY_GOAL_CENTS', 6_000),

    /*
    |--------------------------------------------------------------------------
    | Stripe donation payment link
    |--------------------------------------------------------------------------
    */
    'stripe_payment_url' => env('STRIPE_DONATION_URL', 'https://donate.stripe.com/00g2bvf8i08X8De6oo'),

    /*
    |--------------------------------------------------------------------------
    | Stripe webhook signing secret (whsec_...)
    |--------------------------------------------------------------------------
    */
    'stripe_webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Stripe secret key (sk_...) — used only for importing historical donations
    |--------------------------------------------------------------------------
    */
    'stripe_secret_key' => env('STRIPE_SECRET_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Optional payment link filter for imports (plink_...)
    |--------------------------------------------------------------------------
    */
    'stripe_payment_link_id' => env('STRIPE_PAYMENT_LINK_ID'),

    /*
    |--------------------------------------------------------------------------
    | Month boundaries for goals and supporter badges
    |--------------------------------------------------------------------------
    */
    'timezone' => env('DONATIONS_TIMEZONE', 'Europe/Paris'),

    /*
    |--------------------------------------------------------------------------
    | Cache TTL for aggregated goal progress (seconds)
    |--------------------------------------------------------------------------
    */
    'cache_ttl_seconds' => (int) env('DONATIONS_CACHE_TTL', 300),

    /*
    |--------------------------------------------------------------------------
    | Perks granted to users who donate during the current month
    |--------------------------------------------------------------------------
    |
    | Add future perk keys here once implemented (see App\Enums\DonorPerk):
    | custom_name_style, solo_elo
    |
    */
    'supporter_perks' => [
        'ad_free',
        'avatar_crown',
        'supporter_reactions',
    ],

    /*
    |--------------------------------------------------------------------------
    | Exclusive chat reaction emojis for monthly supporters
    |--------------------------------------------------------------------------
    */
    'supporter_reaction_emojis' => [
        '☕',
        '💎',
        '🙏',
        '✨',
        '🎁',
        '🫶',
    ],

];
