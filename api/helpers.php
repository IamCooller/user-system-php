<?php
// Common helper functions

/**
 * Get JSON input from request body
 *
 * @return array Decoded JSON data
 */
function getJsonInput()
{
    return json_decode(file_get_contents('php://input'), true);
}

/**
 * Extract API path from request URI
 *
 * @param string $requestUri The request URI
 * @return string Extracted path
 */
function extractApiPath($requestUri)
{
    // Load configuration
    static $appConfig = null;
    if ($appConfig === null) {
        $configFile = __DIR__ . '/../config/app.php';
        $appConfig  = require $configFile;

    }

    $basePath = $appConfig['base_path'];
    $apiPath  = $basePath . 'api';

    // Debug info
    error_log("*** API Path Extraction ***");
    error_log("Request URI: $requestUri");
    error_log("Base path: $basePath");
    error_log("API path: $apiPath");

    // Simplest check for known endpoints
    if (strpos($requestUri, '/debug') !== false) {
        error_log("Debug endpoint detected directly");
        return '/debug';
    }

    // Extract the endpoint after /api/
    if (preg_match('~/api/([^?]*)~i', $requestUri, $matches)) {
        $endpoint = $matches[1];
        if (empty($endpoint)) {
            $endpointPath = '/';
        } else {
            $endpointPath = '/' . ltrim($endpoint, '/');
        }
        error_log("Extracted endpoint from /api/ pattern: $endpointPath");
        return $endpointPath;
    }

    // If we got this far, try the full path pattern
    // Normalize the request URI and API path for consistent matching
    $requestUri     = '/' . ltrim($requestUri, '/');
    $apiPathPattern = '/' . ltrim($apiPath, '/');

    error_log("Normalized request URI: $requestUri");
    error_log("API path pattern: $apiPathPattern");

    // Check if the request URI matches the API path pattern
    if (preg_match('~^' . preg_quote($apiPathPattern, '~') . '(/.*)?$~i', $requestUri, $matches)) {
        $endpointPath = isset($matches[1]) ? $matches[1] : '/';
        error_log("Extracted using full API path pattern: $endpointPath");
        return $endpointPath;
    }

    // Last resort fallback
    error_log("No pattern matched - returning /");
    return '/';
}

/**
 * Establish database connection
 *
 * @return PDO Database connection
 */
function getDatabaseConnection()
{
    $db_config = require_once __DIR__ . '/../config/database.php';

    try {
        $pdo = new PDO(
            "mysql:host={$db_config['host']};dbname={$db_config['dbname']};charset={$db_config['charset']}",
            $db_config['username'],
            $db_config['password'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        return $pdo;
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
        exit();
    }
}
