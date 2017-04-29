<?php

return array(
    'app' => array(
        'request' => array(
            // 'route' => 'jaxon',
        ),
        'controllers' => array(
            array(
                'directory' => app_path('Jaxon/Controllers'),
                'namespace' => '\\Jaxon\\App',
                // 'separator' => '', // '.' or '_'
                // 'protected' => array(),
            ),
        ),
    ),
    'lib' => array(
        'core' => array(
            'language' => 'en',
            'encoding' => 'UTF-8',
            'prefix' => array(
                'class' => '',
            ),
            'debug' => array(
                'on' => false,
                'verbose' => false,
            ),
            'error' => array(
                'handle' => false,
            ),
        ),
        'js' => array(
            'lib' => array(
                // 'uri' => '',
            ),
            'app' => array(
                // 'uri' => '',
                // 'dir' => '',
                // 'extern' => true,
                // 'minify' => true,
            ),
        ),
    ),
);
