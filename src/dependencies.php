<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['view'] = function ($c) {
    $settings = $c->get('settings');
    $view = new Slim\Views\Twig($settings['view']['template_path'], $settings['view']['twig']);

    // Add extensions
    $view->addExtension(new Slim\Views\TwigExtension($c->get('router'), $c->get('request')->getUri()));
    $view->addExtension(new Twig_Extension_Debug());
    return $view;
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$appType = getAppType($container);

// Service factory for the ORM
foreach ($container['settings']['db'][$appType] as $key => $dbSetting) {
    $container['db_'. $key] = function ($container) use ($key) {
        $appType = getAppType($container);
        $capsule = new \Illuminate\Database\Capsule\Manager;
        $capsule->addConnection($container['settings']['db'][$appType][$key]);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
        return $capsule;
    };
}

// $appTypeを取得
function getAppType($container) {
    $appType = 'local';
    $domainArray = $container['settings']['application']['domain'];
    if ($domainArray['live'] == $_SERVER['HTTP_HOST']) {
        $appType = 'live';
    } elseif ($domainArray['staging'] == $_SERVER['HTTP_HOST']) {
        $appType = 'staging';
    } elseif ($domainArray['develop'] == $_SERVER['HTTP_HOST']) {
        $appType = 'develop';
    }
    return $appType;
}
