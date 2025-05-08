<?php
/**
 * Script to update .htaccess with the correct RewriteBase
 * Run this script after changing the folder name or moving the project
 */

// Load the app configuration to get the base path
require_once __DIR__ . '/config/app.php';
$basePath = getBasePath();

// Path to .htaccess file
$htaccessPath = __DIR__ . '/.htaccess';

// Read the current .htaccess content
$content = file_get_contents($htaccessPath);
if ($content === false) {
    die("Error: Could not read .htaccess file.\n");
}

// Check if RewriteBase directive exists
if (strpos($content, 'RewriteBase') !== false) {
    // Replace the RewriteBase line with the updated one
    $updatedContent = preg_replace(
        '/RewriteBase\s+[^\r\n]+/',
        "RewriteBase $basePath",
        $content
    );
} else {
    // Add the RewriteBase directive after RewriteEngine On
    $updatedContent = preg_replace(
        '/(RewriteEngine\s+On\s*)\n/',
        "$1\n    RewriteBase $basePath\n",
        $content
    );
}

// Write the updated content back to .htaccess
if (file_put_contents($htaccessPath, $updatedContent) === false) {
    die("Error: Could not write to .htaccess file.\n");
}

echo "Success: .htaccess updated with RewriteBase set to '$basePath'.\n";
