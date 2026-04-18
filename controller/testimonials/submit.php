<?php
header('Content-Type: application/json');
require_once '../../core/db.php';
require_once '../../core/csrf.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $author = $_POST['author'] ?? '';
    $position = $_POST['position'] ?? '';
    $company = $_POST['company'] ?? '';
    $content = $_POST['content'] ?? '';
    $rating = $_POST['rating'] ?? 5;
    $status = $_POST['status'] ?? 'pending';

    if (empty($author) || empty($content)) {
        echo json_encode(['status' => 'error', 'message' => 'Identity and content required for archiving.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO testimonials (author, position, company, content, rating, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$author, $position, $company, $content, $rating, $status]);
        echo json_encode(['status' => 'success', 'message' => 'Transmission archived. Data will be reviewed by central command.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database failure: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid sequence.']);
}
?>
