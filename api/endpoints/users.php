<?php
// Users endpoints

/**
 * Handle GET request for user details
 *
 * @param PDO $pdo Database connection
 * @param int $userId User ID
 * @return void
 */
function handleGetUser($pdo, $userId)
{
    // Require authentication and ownership of the resource
    if (! requireOwnership($userId)) {
        return;
    }

    $stmt = $pdo->prepare("SELECT id, name, email, dob FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo json_encode(['user' => $user]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'User not found']);
    }
}

/**
 * Handle PUT request to update user
 *
 * @param PDO $pdo Database connection
 * @param int $userId User ID
 * @return void
 */
function handleUpdateUser($pdo, $userId)
{
    // Require authentication and ownership of the resource
    if (! requireOwnership($userId)) {
        return;
    }

    $data = getJsonInput();

    if (! isset($data['name'], $data['email'], $data['dob'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        return;
    }

    // Check if email exists for other users
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmt->execute([$data['email'], $userId]);
    if ($stmt->fetch()) {
        http_response_code(400);
        echo json_encode(['error' => 'Email already exists']);
        return;
    }

    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, dob = ? WHERE id = ?");
    try {
        $stmt->execute([$data['name'], $data['email'], $data['dob'], $userId]);
        echo json_encode(['message' => 'Update successful']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Update failed: ' . $e->getMessage()]);
    }
}

/**
 * Handle user endpoints
 *
 * @param PDO $pdo Database connection
 * @param int $userId User ID
 * @return void
 */
function handleUserEndpoint($pdo, $userId)
{
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            handleGetUser($pdo, $userId);
            break;
        case 'PUT':
            handleUpdateUser($pdo, $userId);
            break;
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            break;
    }
}
