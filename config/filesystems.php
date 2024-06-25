<?php

return [

    'disks' => [

        'ovh' => [
            'driver' => 's3',
            'key' => env('OVH_ACCESS_KEY_ID'),
            'secret' => env('OVH_SECRET_ACCESS_KEY'),
            'region' => env('OVH_DEFAULT_REGION'),
            'bucket' => env('OVH_BUCKET'),
            'url' => env('OVH_URL'),
            'endpoint' => env('OVH_ENDPOINT'),
            'use_path_style_endpoint' => env('OVH_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'visibility' => 'public',
        ],

        'contabo' => [
            'driver' => 's3',
            'key' => env('CONTABO_ACCESS_KEY_ID'),
            'secret' => env('CONTABO_SECRET_ACCESS_KEY'),
            'region' => env('CONTABO_DEFAULT_REGION'),
            'bucket' => env('CONTABO_BUCKET'),
            'url' => env('CONTABO_URL'),
            'endpoint' => env('CONTABO_ENDPOINT'),
            'use_path_style_endpoint' => true,
            'throw' => true,
        ],
    ],

];
