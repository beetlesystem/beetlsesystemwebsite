<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // Check if persistence cookie exists
    if (isset($_COOKIE['auth_beetle'])) {
        require_once 'db.php';
        require_once __DIR__ . '/../model/User.php';
        
        $admin_id = base64_decode($_COOKIE['auth_beetle']);
        $pdo_instance = $pdo; // from db.php
        
        $stmt = $pdo_instance->prepare("SELECT * FROM users WHERE admin_id = ?");
        $stmt->execute([$admin_id]);
        $user = $stmt->fetch();
        
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['admin_id'] = $user['admin_id'];
            $_SESSION['full_name'] = $user['full_name'];
        } else {
            header("Location: /beetlesystem/login");
            exit;
        }
    } else {
        header("Location: /beetlesystem/login");
        exit;
    }
}
?>
