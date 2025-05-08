<?php
// Main API router
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load application configuration
$appConfig = require_once __DIR__ . '/../config/app.php';

// Start session for all API requests
if (session_status() === PHP_SESSION_NONE) {
    // Set secure session parameters
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);

    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        ini_set('session.cookie_secure', 1);
    }

    session_start();
}

// Set common headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Include helper functions
require_once __DIR__ . '/helpers.php';

// Include authentication middleware
require_once __DIR__ . '/middleware.php';

// Include all endpoint handlers
require_once __DIR__ . '/endpoints/register.php';
require_once __DIR__ . '/endpoints/login.php';
require_once __DIR__ . '/endpoints/logout.php';
require_once __DIR__ . '/endpoints/session.php';
require_once __DIR__ . '/endpoints/users.php';
require_once __DIR__ . '/endpoints/debug.php';

// Include the router
require_once __DIR__ . '/router.php';

// Get database connection
$pdo = getDatabaseConnection();

// Get request URI and extract API path
$requestUri              = $_SERVER['REQUEST_URI'];
$_SERVER['REQUEST_FROM'] = 'api_index';
$path                    = extractApiPath($requestUri);

// Check for session timeout (30 minutes)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    // Session timeout
    session_unset();
    session_destroy();
}

// Update last activity time
if (isset($_SESSION['user_id'])) {
    $_SESSION['last_activity'] = time();
}

// Route the request using the router
if (! routeRequest($path, $pdo)) {
    // No route matched
    http_response_code(404);
    echo json_encode(['error' => 'Endpoint not found', 'path' => $path]);
}
