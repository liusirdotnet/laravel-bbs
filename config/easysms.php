<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SMS Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timeout.
    |
    */
    'timeout' => 5.0,

    'default' => [
        'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,
        'gateways' => [
            'yunpian',
        ],
    ],

    'gateways' => [
        'errorlog' => [
            'file' => storage_path('logs') . 'easysms.log',
        ],
        'yunpian' => [
            'api_key' => env('YUNPIAN_API_KEY'),
        ]
    ],

];
