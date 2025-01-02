<?php

use OpenSwoole\Http\Server;
use OpenSwoole\Http\Request;
use OpenSwoole\Http\Response;
use Dotenv\Dotenv;

require_once __DIR__ . '/vendor/autoload.php';

// Load .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Create the server
$server = new Server("0.0.0.0", 8105);

// Event to handle server start
$server->on("Start", function ($server) {
    echo "OpenSwoole HTTP Server Started @ 127.0.0.1:8105\n";
});

// Handling WorkerStart event to dynamically load files
$server->on('WorkerStart', function ($server, $workerId) {
    require_once __DIR__ . '/core/rcon.php'; // Include RCON logic
    require_once __DIR__ . '/core/requestHandler.php'; // Include request handler
});

// Swoole request handler
$server->on('Request', function (Request $request, Response $response) {
    $rconConfig = require __DIR__ . '/core/rconConfig.php'; // Dynamically load RCON config
    handleRequest($request, $response, $rconConfig); // Handle the request with the config
});

// Start the server
$server->start();
