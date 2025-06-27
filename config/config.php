<?php

return [
    'app' => [
        'request' => [
            'route' => 'jaxon',
            'middlewares' => ['web', 'jaxon.ajax'],
        ],
        'directories' => [
            // [
            //     'path' => base_path('ajax'),
            //     'namespace' => '\\Jaxon\\Ajax',
            //     // 'separator' => '', // '.' or '_'
            //     // 'protected' => [],
            // ],
        ],
    ],
    'lib' => [
        'core' => [
            'language' => 'en',
            'encoding' => 'UTF-8',
            'prefix' => [
                'class' => '',
            ],
            'debug' => [
                'on' => false,
                'verbose' => false,
            ],
            'error' => [
                'handle' => false,
            ],
        ],
        'js' => [
            'lib' => [
                // 'uri' => '',
            ],
            'app' => [
                // 'uri' => '',
                // 'dir' => '',
                // 'export' => true,
                // 'minify' => true,
            ],
        ],
    ],
];
