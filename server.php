<?php
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/src/Server/Chat.php';

// 再帰的に.phpを読み込む
$it = new RecursiveDirectoryIterator(__DIR__ . '/src/Api');
$it = new RecursiveIteratorIterator($it);
$it = new RegexIterator($it, '/\.php\z/');
require_once __DIR__ . '/src/Api/Model/AbstractModel.php';
require_once __DIR__ . '/src/Api/Table/AbstractTable.php';
foreach ($it as $file) {
    if ($file->isFile()) {
        require_once $file;
    }
}

$it = new RecursiveDirectoryIterator(__DIR__ . '/src/App');
$it = new RecursiveIteratorIterator($it);
$it = new RegexIterator($it, '/\.php\z/');
require_once __DIR__ . '/src/App/Model/AbstractModel.php';
require_once __DIR__ . '/src/App/Table/AbstractTable.php';
foreach ($it as $file) {
    if ($file->isFile()) {
        require_once $file;
    }
}

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use SuisuiChat\Server\Chat;


// Slimのcontainerを取得する
$appSettings = require_once __DIR__ . '/src/settings.php';
$dbSettings = require_once __DIR__ . '/src/database.php';
$settings = array('settings' => array_merge($appSettings, $dbSettings));
$container = new \Slim\Container($settings);

// port番号を取得する
$appType = getAppType($container);
$port = $container['settings']['application']['server'][$appType]['port'];

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

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat($container)
        )
    ),
    $port
);

$server->run();

function getAppType($container) {
    $appType = 'local';
    $pathArray = $container['settings']['application']['path'];
    if (rtrim($pathArray['live'], "/") == __DIR__) {
        $appType = 'live';
    } elseif (rtrim($pathArray['staging'], "/") == __DIR__) {
        $appType = 'staging';
    } elseif (rtrim($pathArray['develop'], "/") == __DIR__) {
        $appType = 'develop';
    }
    return $appType;
}
