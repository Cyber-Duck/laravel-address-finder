<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Address finder Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default address finder "driver" that will be
    | used.
    |
    | Supported: "mock", "loqate"
    |
    */

    'driver' => env('ADDRESS_FINDER_DRIVER', 'mock'),

    'cache' => [
        'enabled' => false,
        'ttl'=> 1440,
        'store' => env('ADDRESS_FINDER_CACHE_DRIVER', env('CACHE_DRIVER'))
    ],

    'loqate' =>[
        'api' => [
            'key' => env('LOQATE_API_KEY'),
            'base_uri' => env('LOQATE_API_BASE_URI', 'https://api.addressy.com/Capture/Interactive/'),
            'endpoints' => [
                'suggestions' => 'Find/v1.10/json3.ws',
                'details' => 'Retrieve/v1.10/json3.ws',
            ],
        ],
    ]
];
