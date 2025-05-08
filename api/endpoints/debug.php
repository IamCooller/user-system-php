<?php
// Debug endpoint

/**
 * Handle debug requests - outputs detailed information about the request
 *
 * @return void
 */
function handleDebug()
{
    echo json_encode([
        'success' => true,
        'request' => [
            'method' => $_SERVER['REQUEST_METHOD'],
            'uri'    => $_SERVER['REQUEST_URI'],
            'source' => $_SERVER['REQUEST_FROM'] ?? 'direct',
        ],
        'server'  => $_SERVER,
        'get'     => $_GET,
        'post'    => $_POST,
        'input'   => getJsonInput(),
        'time'    => date('Y-m-d H:i:s'),
    ], JSON_PRETTY_PRINT);
}
