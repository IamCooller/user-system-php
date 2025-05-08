<?php
/**
 * Application Configuration
 */

// Dynamically determine base path from the folder name
if (! function_exists('getBasePath')) {
    function getBasePath()
    {
        // Get the absolute path to the project directory
        $projectDir = dirname(__DIR__);

        // Debug info
        error_log("Project directory: " . $projectDir);

        // Get the folder name from the path
        $folderName = basename($projectDir);
        error_log("Folder name detected: " . $folderName);

        // Fallback if folder name is empty or can't be determined
        if (empty($folderName)) {
            error_log("Empty folder name detected, using REQUEST_URI");

            // Try to get from REQUEST_URI
            if (isset($_SERVER['REQUEST_URI'])) {
                $uri = $_SERVER['REQUEST_URI'];
                error_log("REQUEST_URI: " . $uri);

                // Extract first folder from URI
                $parts = explode('/', trim($uri, '/'));
                if (isset($parts[0]) && ! empty($parts[0])) {
                    $folderName = $parts[0];
                    error_log("Folder name from REQUEST_URI: " . $folderName);
                } else {
                    // Use a default if we can't determine
                    $folderName = "user-system-php";
                    error_log("Using default folder name: " . $folderName);
                }
            } else {
                // Last resort fallback
                $folderName = "user-system-php";
                error_log("No REQUEST_URI, using default folder name: " . $folderName);
            }
        }

        // Return the base path with slashes
        $basePath = '/' . $folderName . '/';
        error_log("Final base path: " . $basePath);
        return $basePath;
    }
}

// Create the configuration array
$basePath = getBasePath();

return [
    // Application base path
    'base_path'   => $basePath,

    // Application name
    'app_name'    => 'User Management System',

    // Application environment
    'environment' => 'development',

    // Debug mode
    'debug'       => true,

    // API path
    'api_path'    => $basePath . 'api',
];
