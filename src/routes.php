<?php

// 再帰的に.phpを読み込む
$it = new RecursiveDirectoryIterator(__DIR__ . '/Api');
$it = new RecursiveIteratorIterator($it);
$it = new RegexIterator($it, '/\.php\z/');
require_once __DIR__ . '/Api/Model/AbstractModel.php';
require_once __DIR__ . '/Api/Table/AbstractTable.php';
foreach ($it as $file) {
    if ($file->isFile()) {
        require_once $file;
    }
}

$it = new RecursiveDirectoryIterator(__DIR__ . '/App');
$it = new RecursiveIteratorIterator($it);
$it = new RegexIterator($it, '/\.php\z/');
require_once __DIR__ . '/App/Model/AbstractModel.php';
require_once __DIR__ . '/App/Table/AbstractTable.php';
foreach ($it as $file) {
    if ($file->isFile()) {
        require_once $file;
    }
}

// デフォルトのルーティング
$app->group('/', function(){
    $this->any('', function ($request, $response, $args) {
        $indexContoller = new \SuisuiChat\App\Controller\IndexController($this);
        $indexContoller->indexAction();
    });
});

// Appのルーティング
$app->group('/app', function(){
    $this->any('/index/{action:.+}', function ($request, $response, $args) {
        $action = $args['action']. 'Action';
        $indexContoller = new \SuisuiChat\App\Controller\IndexController($this);
        if (method_exists($indexContoller, $action)) {
            $indexContoller->$action();
        } else {
            throw new \Slim\Exception\NotFoundException($request, $response);
        }
    });
});
