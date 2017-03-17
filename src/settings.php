<?php
return [
    'displayErrorDetails' => true, // set to false in production
    'addContentLengthHeader' => false, // Allow the web server to send the content-length header
    'determineRouteBeforeAppMiddleware' => false,

    // View settings
    'view' => [
        'template_path' => __DIR__ . '/../twig/',
        'twig' => [
            'cache' => __DIR__ . '/../twig/caches/',
            'debug' => true,
            'auto_reload' => true,
        ],
    ],
    
    // Monolog settings
    'logger' => [
        'name' => 'slim-app',
        'path' => __DIR__ . '/../logs/app.log',
        'level' => \Monolog\Logger::DEBUG,
    ],
    
    // SuisuiChat settings
    'application' => [
        'domain' => [
            'live'    => 'suisuichat.sakakick.com',
            'staging' => 'stg.suisuichat.sakakick.com',
            'develop' => 'dev.suisuichat.sakakick.com',
            'local'   => [
                'admin.sakakick.com',
            ],
        ],
        'path' => [
            'live'    => '/var/source/suisuichat/',
            'staging' => '/var/source/suisuichat_stg/',
            'develop' => '/var/source/suisuichat_dev/',
            'local'   => [
                '/home/admin/source/suisuichat/',
            ],
        ],
        'server' => [
            'live' => [
                'port' => 4502,
            ],
            'staging' => [
                'port' => 4503,
            ],
            'develop' => [
                'port' => 4504,
            ],
            'local' => [
                'port' => 4505,
            ],
        ],
    ],
];
