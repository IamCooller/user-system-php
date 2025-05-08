<?php
// Login endpoint

/**
 * Handle user login
 *
 * @param PDO $pdo Database connection
 * @return void
 */
function handleLogin($pdo)
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        return;
    }

    $data = getJsonInput();

    if (! isset($data['email'], $data['password'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing email or password']);
        return;
    }

    $stmt = $pdo->prepare("SELECT id, name, email, dob, password FROM users WHERE email = ?");
    $stmt->execute([$data['email']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($data['password'], $user['password'])) {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Store user ID in session
        $_SESSION['user_id']       = $user['id'];
        $_SESSION['last_activity'] = time();

        // Remove password from response
        unset($user['password']);

        // Return user data
        echo json_encode(['user' => $user, 'message' => 'Login successful']);
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid credentials']);
    }
}
