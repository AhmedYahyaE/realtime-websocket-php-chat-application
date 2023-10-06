<?php
// This file is used to start the WebSocket Server from the terminal/command-line (just like when you start the PHP's built-in development web server using the "php -S localhost:8000" command).

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use MyApp\Chat;

    require dirname(__DIR__) . '/vendor/autoload.php';

    $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new Chat() // This is our custom WebSocket handler Chat.php class
            )
        ),
        8080 // the port number of the WebSocket Server (i.e. Port on which the server will listen)
    );

    $server->run();
?>