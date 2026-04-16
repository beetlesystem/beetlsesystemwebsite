<?php
require_once '../../core/auth_check.php';
require_once '../../core/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $about_image = $_POST['about_image'] ?? '';
    $pk_starter = json_encode($_POST['package_starter'] ?? []);
    $pk_premium = json_encode($_POST['package_premium'] ?? []);
    $pk_enterprise = json_encode($_POST['package_enterprise'] ?? []);

    $updates = [
        'about_image' => $about_image,
        'package_starter' => $pk_starter,
        'package_premium' => $pk_premium,
        'package_enterprise' => $pk_enterprise
    ];

    $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");

    foreach ($updates as $key => $val) {
        $stmt->execute([$key, $val]);
    }

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid Request']);
}
