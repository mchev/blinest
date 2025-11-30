<?php

return [

    'connections' => [
        'imports' => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => 'imports',
            'retry_after' => 90,
            'block_for' => null,
            'after_commit' => false,
        ],
        'level-calculations' => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => 'level-calculations',
            'retry_after' => 300,
            'block_for' => null,
            'after_commit' => false,
        ],
    ],

];
