<?php

use OpenSwoole\Http\Server;
use OpenSwoole\Http\Request;
use OpenSwoole\Http\Response;

// Create the server
$server = new Server("0.0.0.0", 8105);

// Event to handle server start
$server->on("Start", function ($server) {
    echo "OpenSwoole HTTP Server Started @ 127.0.0.1:8105\n";
});

// Handling WorkerStart event to dynamically load files and configuration
$server->on('WorkerStart', function($server, $workerId) {
    // Dynamically load RCON configuration
    $rconConfig = require __DIR__ . '/core/rconConfig.php'; // Load the updated RCON config
    require_once __DIR__ . '/core/rcon.php'; // Include the RCON client logic
    require_once __DIR__ . '/core/requestHandler.php'; // Includes the request handling logic
});

// Swoole request handler - moved outside of WorkerStart
$server->on('Request', function (Request $request, Response $response) {
    $rconConfig = require __DIR__ . '/core/rconConfig.php'; // Dynamically load config in each request
    // Your handler logic goes here
    handleRequest($request, $response, $rconConfig); // Use dynamic $rconConfig
});

// Start the server
$server->start();
