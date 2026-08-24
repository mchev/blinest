<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Standard chat reaction emojis (available to all authenticated users)
    |--------------------------------------------------------------------------
    */
    'reaction_emojis' => [
        '👍', '😂', '❤️', '🔥', '😮', '😢', '👏', '😡', '🎉', '😎',
        '🤔', '🙌', '💯', '🎵', '🥁', '🎸', '🎤', '🎻', '🎺', '🎷',
        '🎶', '😊', '😃', '😞', '😉', '😛', '😲', '😘', '😕', '🤑',
    ],

    /*
    |--------------------------------------------------------------------------
    | Chat moderation
    |--------------------------------------------------------------------------
    */
    'moderation' => [
        'rate_limit_per_minute' => 15,
        'room_flood_per_minute' => 8,
        'duplicate_window_seconds' => 30,
        'min_cross_room_body_length' => 8,
        'cross_room_window_seconds' => 300,
        'cross_room_min_rooms' => 3,
        'cross_room_ban_days' => 7,
        'suspicious_link_pattern' => '/(?:(?:https?:\/\/)|(?:www\.))[^\s]+/iu',
    ],

];
