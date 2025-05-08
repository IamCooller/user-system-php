<?php
// Register endpoint

/**
 * Handle user registration
 *
 * @param PDO $pdo Database connection
 * @return void
 */
function handleRegister($pdo)
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        return;
    }

    $data = getJsonInput();

    // Validate input
    if (! isset($data['name'], $data['email'], $data['dob'], $data['password'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        return;
    }

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$data['email']]);
    if ($stmt->fetch()) {
        http_response_code(400);
        echo json_encode(['error' => 'Email already exists']);
        return;
    }

    // Hash password
    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

    // Insert user
    $stmt = $pdo->prepare("INSERT INTO users (name, email, dob, password) VALUES (?, ?, ?, ?)");
    try {
        $stmt->execute([$data['name'], $data['email'], $data['dob'], $hashedPassword]);
        http_response_code(201);
        echo json_encode(['message' => 'Registration successful']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Registration failed: ' . $e->getMessage()]);
    }
}
