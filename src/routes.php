<?php

// 再帰的に.phpを読み込む
$it = new RecursiveDirectoryIterator(__DIR__ . '/Api');
$it = new RecursiveIteratorIterator($it);
$it = new RegexIterator($it, '/\.php\z/');
foreach ($it as $file) {
    if ($file->isFile()) {
        require $file;
    }
}

$it = new RecursiveDirectoryIterator(__DIR__ . '/App');
$it = new RecursiveIteratorIterator($it);
$it = new RegexIterator($it, '/\.php\z/');
foreach ($it as $file) {
    if ($file->isFile()) {
        require $file;
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
