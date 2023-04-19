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
        'enabled' => env('ADDRESS_FINDER_CACHE', false),
        'ttl'=> 1440,
        'store' => env('ADDRESS_FINDER_CACHE_DRIVER', env('CACHE_DRIVER'))
    ],

    'loqate' =>[
        'api' => [
            'key' => env('LOQATE_API_KEY'),
            'base_uri' => env('LOQATE_API_BASE_URI', 'https://api.addressy.com/'),
            'endpoints' => [
                'suggestions' => 'Capture/Interactive/Find/v1.10/json3.ws',
                'details' => 'Capture/Interactive/Retrieve/v1.10/json3.ws',
                'postzon' => 'GovernmentData/Postzon/RetrieveByPostcode/v1.50/json3ex.ws',
            ],
        ],
    ]
];
