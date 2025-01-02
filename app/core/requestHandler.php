<?php

use OpenSwoole\Http\Request;
use OpenSwoole\Http\Response;
use Theislemanager\RconClient;

// Handle requests
function parseResult($str) {
    $details = [];
    // Regular expression to match ParamName: param_value
    preg_match_all('/([a-zA-Z0-9\-_]+):\s*([^,]+)/', $str, $matches, PREG_OFFSET_CAPTURE);

    // Loop through matches and format as key-value pairs
    foreach ($matches[1] as $index => $match) {
        $key = trim($match[0]);
        $value = trim($matches[2][$index][0]);

        // Store the key-value pair in the associative array
        $details[$key] = $value;
    }

    return $details;
}

function handleRequest(Request $request, Response $response, $rconConfig)
{
    $path = $request->server['request_uri'] ?? '/';

    if ($path === '/rcon') {
        $params = $request->get ?? [];
        $command = $params['command'] ?? null;
        $data = $params['data'] ?? '';

        if ($command) {
            $rcon = new RconClient($rconConfig['host'], $rconConfig['port'], $rconConfig['password']);
            if ($rcon->connect()) {
                $result = $rcon->sendCommand($command, $data);
                $response->header("Content-Type", "application/json");
                $formattedResult = parseResult($result);
                $response->end(json_encode([
                    'status' => 'success',
                    'message' => $formattedResult//$result
                ]));
            } else {
                $response->header("Content-Type", "application/json");
                $response->end(json_encode([
                    'status' => 'error',
                    'message' => 'Could not connect to RCON server.'
                ]));
            }
        } else {
            $response->status(400);
            $response->end(json_encode([
                'status' => 'error',
                'message' => 'Command is required.'
            ]));
        }
    } else {
        // Default route to serve the HTML page
        $html = file_get_contents(__DIR__ . '/../index.html');
        $response->header("Content-Type", "text/html");
        $response->end($html);
    }
}