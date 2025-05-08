<?php
// Session endpoint

/**
 * Validate the current user session
 *
 * @param PDO $pdo Database connection
 * @return void
 */
function handleCheckSession($pdo)
{
    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Check if session contains user ID
    if (! isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Not authenticated']);
        return;
    }

    // Verify user exists in database
    $stmt = $pdo->prepare("SELECT id, name, email, dob FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (! $user) {
        // User no longer exists, clear session
        session_destroy();
        http_response_code(401);
        echo json_encode(['error' => 'Invalid session']);
        return;
    }

    // Return current user data
    echo json_encode(['user' => $user]);
}
