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
];
