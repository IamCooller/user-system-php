<?php
// API Router - connects URL paths to handlers

/**
 * Maps URL paths to handler functions
 *
 * Add new routes here when adding endpoints
 *
 * @param string $path API path
 * @param PDO $pdo Database connection
 * @return boolean True if route was handled, false otherwise
 */
function routeRequest($path, $pdo)
{
    // Debug the input path
    error_log("Router received path: " . $path);

    // Extract route parameters
    $userId = null;
    if (preg_match('/^\/users\/(\d+)$/', $path, $matches)) {
        $userId = $matches[1];
    }

    // Route the request based on path
    switch (true) {
        case $path === '/register':
            error_log("Routing to: register handler");
            handleRegister($pdo);
            return true;

        case $path === '/login':
            error_log("Routing to: login handler");
            handleLogin($pdo);
            return true;

        case $path === '/logout':
            error_log("Routing to: logout handler");
            handleLogout();
            return true;

        case $path === '/session':
            error_log("Routing to: session handler");
            handleCheckSession($pdo);
            return true;

        case $path === '/debug':
            error_log("Routing to: debug handler");
            handleDebug();
            return true;

        case $userId !== null:
            error_log("Routing to: user endpoint with ID: " . $userId);
            handleUserEndpoint($pdo, $userId);
            return true;

        default:
            error_log("No route matched for path: " . $path);
            return false;
    }
}
