<?php
// API Middleware functions

/**
 * Check if user is authenticated, return error if not
 *
 * @return bool True if authenticated, false otherwise
 */
function requireAuth()
{
    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Check if user is logged in
    if (! isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Authentication required']);
        return false;
    }

    return true;
}

/**
 * Check if the authenticated user owns the resource
 *
 * @param int $resourceUserId The user ID that owns the resource
 * @return bool True if authorized, false otherwise
 */
function requireOwnership($resourceUserId)
{
    // First check if authenticated
    if (! requireAuth()) {
        return false;
    }

    // Check if user owns the resource
    if ($_SESSION['user_id'] != $resourceUserId) {
        http_response_code(403);
        echo json_encode(['error' => 'Not authorized to access this resource']);
        return false;
    }

    return true;
}
