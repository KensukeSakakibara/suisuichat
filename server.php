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

// Service factory for the ORM
foreach ($container['settings']['db'] as $key => $dbSetting) {
    $container['db_'. $key] = function ($container) use ($key) {
        $capsule = new \Illuminate\Database\Capsule\Manager;
        $capsule->addConnection($container['settings']['db'][$key]);
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
    4502
);

$server->run();