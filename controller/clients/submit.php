<?php
require_once '../../core/db.php';

// Create table if not exists
$pdo->exec("CREATE TABLE IF NOT EXISTS clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50),
    service VARCHAR(255),
    message TEXT,
    status VARCHAR(50) DEFAULT 'lead',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Safely ensure columns exist if retrofitting older databases
try {
    $pdo->exec("ALTER TABLE clients ADD COLUMN service VARCHAR(255) AFTER phone");
    $pdo->exec("ALTER TABLE clients ADD COLUMN message TEXT AFTER service");
} catch (Exception $e) {
    // Columns already exist
}

header('Content-Type: application/json');
require_once '../../core/csrf.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $service = trim($_POST['service'] ?? 'General Link');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email)) {
        echo json_encode(['success' => false, 'message' => 'Name and Email are required.']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO clients (name, email, phone, service, message) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$name, $email, $phone, $service, $message])) {
        // We'll return 'status' instead of 'success' for main.js compatibility
        echo json_encode(['status' => 'success', 'success' => true, 'message' => 'Your details have been successfully transmitted.']);
    } else {
        echo json_encode(['status' => 'error', 'success' => false, 'message' => 'System error. Could not process transmission.']);
    }
}
