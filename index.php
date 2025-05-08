<?php
// Front controller to handle all requests

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load application configuration
$appConfig = require_once __DIR__ . '/config/app.php';

// Start session for all requests
if (session_status() === PHP_SESSION_NONE) {
    // Set secure session parameters
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);

    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        ini_set('session.cookie_secure', 1);
    }

    session_start();
}

// Get the requested path
$requestUri = $_SERVER['REQUEST_URI'];
$basePath   = $appConfig['base_path'];

// Check if this is an API request
if (strpos($requestUri, $basePath . 'api') === 0) {
    // Debug info
    $_SERVER['REQUEST_FROM'] = 'index.php';

    // Log the request for debugging
    error_log("API Request: $requestUri");

    // Include the API handler
    require __DIR__ . '/api/index.php';
    exit;
}

// If not an API request, show the main HTML page
include __DIR__ . '/index.html';
