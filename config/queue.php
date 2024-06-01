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
    ],

];
