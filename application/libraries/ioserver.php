<?php

use App\Chat;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

require __DIR__ . "../../../vendor/autoload.php";

$server = IoServer::factory(

	new HttpServer(

		new WsServer(
			
			new Chat()
		)
	),
	3000
);

$server->run();
