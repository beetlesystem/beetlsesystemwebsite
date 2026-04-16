<?php
header('Content-Type: application/json');
require_once '../../core/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $author = $_POST['author'] ?? '';
    $position = $_POST['position'] ?? '';
    $content = $_POST['content'] ?? '';
    $rating = $_POST['rating'] ?? 5;

    if (empty($author) || empty($content)) {
        echo json_encode(['status' => 'error', 'message' => 'Identity and content required for archiving.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO testimonials (author, position, content, rating, status) VALUES (?, ?, ?, ?, 'pending')");
        $stmt->execute([$author, $position, $content, $rating]);
        echo json_encode(['status' => 'success', 'message' => 'Transmission archived. Data will be reviewed by central command.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database failure: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid sequence.']);
}
?>
