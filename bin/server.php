<?php
// This file is used to start the WebSocket Server from the terminal/command-line (just like when you start the PHP's built-in development web server using the "php -S localhost:8000" command).
// This file is copied from: http://socketo.me/docs/hello-world#next_steps
// Check the browser's console to know the status of the WebSocket connection (the WebSocket connection was initiated/started from inside the <script> HTML tag in chatroom.php)

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use MyApp\Chat;

    require dirname(__DIR__) . '/vendor/autoload.php';

    $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new Chat()
            )
        ),
        8080 // the port number of the WebSocket Server (i.e. Port on which the server will listen)
    );

    $server->run();
?>