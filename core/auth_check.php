<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // Check if persistence cookie exists
    if (isset($_COOKIE['auth_beetle'])) {
        require_once 'db.php';
        require_once __DIR__ . '/../model/User.php';
        
        $decoded = base64_decode($_COOKIE['auth_beetle']);
        if (strpos($decoded, '|') !== false) {
            list($admin_id, $provided_signature) = explode('|', $decoded);
            $expected_signature = hash_hmac('sha256', $admin_id, SITE_KEY);
            
            if (hash_equals($expected_signature, $provided_signature)) {
                $pdo_instance = $pdo;
                $stmt = $pdo_instance->prepare("SELECT * FROM users WHERE admin_id = ?");
                $stmt->execute([$admin_id]);
                $user = $stmt->fetch();
                
                if ($user) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['admin_id'] = $user['admin_id'];
                    $_SESSION['full_name'] = $user['full_name'];
                } else {
                    header("Location: /beetlesystem/admin");
                    exit;
                }
            } else {
                header("Location: /beetlesystem/admin");
                exit;
            }
        }
    } else {
        header("Location: /beetlesystem/admin");
        exit;
    }
}
?>
