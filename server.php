<?php
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/src/Server/Chat.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use SuisuiChat\Chat;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat()
        )
    ),
    4502
);

$server->run();