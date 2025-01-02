<?php

use Dotenv\Dotenv;

// Ensure environment variables are loaded
if (!isset($_ENV['RCON_HOST']) || !isset($_ENV['RCON_PORT']) || !isset($_ENV['RCON_PASSWORD'])) {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

return [
    'host' => $_ENV['RCON_HOST'],
    'port' => $_ENV['RCON_PORT'],
    'password' => $_ENV['RCON_PASSWORD'],
];