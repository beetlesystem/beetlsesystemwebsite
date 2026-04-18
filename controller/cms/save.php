<?php
require_once '../../core/auth_check.php';
require_once '../../core/db.php';
require_once '../../core/csrf.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    
    $uploadDir = '../../uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $updates = [];

    // Handle Image Upload
    if (isset($_FILES['about_image']) && $_FILES['about_image']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['about_image']['name'], PATHINFO_EXTENSION);
        $fileName = 'about_img_' . time() . '.' . $ext;
        if (move_uploaded_file($_FILES['about_image']['tmp_name'], $uploadDir . $fileName)) {
            $updates['about_image'] = 'uploads/' . $fileName;
        }
    }

    // Handle Video Upload
    if (isset($_FILES['about_video']) && $_FILES['about_video']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['about_video']['name'], PATHINFO_EXTENSION);
        $fileName = 'about_vid_' . time() . '.' . $ext;
        if (move_uploaded_file($_FILES['about_video']['tmp_name'], $uploadDir . $fileName)) {
            $updates['about_video'] = 'uploads/' . $fileName;
        }
    }

    // Handle other settings (JSON arrays)
    $updates['package_starter'] = json_encode($_POST['package_starter'] ?? []);
    $updates['package_premium'] = json_encode($_POST['package_premium'] ?? []);
    $updates['package_enterprise'] = json_encode($_POST['package_enterprise'] ?? []);

    $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");

    foreach ($updates as $key => $val) {
        $stmt->execute([$key, $val]);
    }

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid Request']);
}
