<?php
header('Content-Type: application/json');
require_once '../../core/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect data (handling both regular form and multi-step)
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? 'Website Inquiry';
    $message = $_POST['message'] ?? '';
    $phone = $_POST['phone'] ?? null;
    $service = $_POST['service'] ?? null;
    $budget = $_POST['budget'] ?? null;

    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO contacts (name, email, subject, phone, service, budget, message, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'new')");
        $stmt->execute([$name, $email, $subject, $phone, $service, $budget, $message]);

        echo json_encode(['status' => 'success', 'message' => 'Your inquiry has been received.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
