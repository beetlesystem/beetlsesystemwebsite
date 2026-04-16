<?php
session_start();
require_once '../core/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_id = $_POST['admin_id'] ?? '';
    $password = $_POST['password'] ?? '';
    $full_name = $_POST['full_name'] ?? '';

    if (empty($admin_id) || empty($password) || empty($full_name)) {
        $error = 'Please fill in all fields.';
    } else {
        // Check if admin_id exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE admin_id = ?");
        $stmt->execute([$admin_id]);
        if ($stmt->fetchColumn() > 0) {
            $error = 'Administrator ID already exists.';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (admin_id, password, full_name) VALUES (?, ?, ?)");
            if ($stmt->execute([$admin_id, $hashed_password, $full_name])) {
                $success = 'Successfully registered. You can now login.';
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <base href="/beetlesystem/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Beetle System</title>
    <link rel="stylesheet" href="core/style.css">
    <link rel="icon" type="image/svg+xml" href="core/favicon.svg">
    <style>
        .login-page { height: 100vh; display: flex; align-items: center; justify-content: center; background: var(--bg-primary); }
        .login-container { width: 100%; max-width: 450px; padding: 3rem; }
        .login-header { text-align: center; margin-bottom: 2rem; }
        .login-title { font-family: var(--font-heading); font-size: 2rem; font-weight: 900; }
        .login-form { display: flex; flex-direction: column; gap: 1.2rem; }
    </style>
</head>
<body class="inner-page">
    <section class="login-page">
        <div class="login-container reveal-from-bottom">
            <div class="login-header">
                <h1 class="login-title">NEW RECRUIT.</h1>
                <p style="opacity:0.5; font-size: 0.9rem;">CREATE YOUR ACCESS CREDENTIALS</p>
            </div>

            <form class="login-form" method="POST" action="">
                <?php if ($error): ?>
                    <div style="background: rgba(255,0,0,0.05); color: #ff4444; padding: 0.8rem; border-radius: 8px; font-size: 0.8rem; border: 1px solid rgba(255,0,0,0.1); text-align: center;"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div style="background: rgba(0,255,0,0.05); color: #44ff44; padding: 0.8rem; border-radius: 8px; font-size: 0.8rem; border: 1px solid rgba(0,255,0,0.1); text-align: center;"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label style="font-size:0.6rem; letter-spacing: 2px; font-weight: 900; opacity: 0.5;">FULL NAME</label>
                    <input type="text" name="full_name" placeholder="Agent Name" required style="background: rgba(0,0,0,0.02); border: 1px solid rgba(0,0,0,0.1); padding: 1rem; border-radius: 8px; width: 100%;">
                </div>
                <div class="form-group">
                    <label style="font-size:0.6rem; letter-spacing: 2px; font-weight: 900; opacity: 0.5;">ADMINISTRATOR ID</label>
                    <input type="text" name="admin_id" placeholder="AD-XXX-X" required style="background: rgba(0,0,0,0.02); border: 1px solid rgba(0,0,0,0.1); padding: 1rem; border-radius: 8px; width: 100%;">
                </div>
                <div class="form-group">
                    <label style="font-size:0.6rem; letter-spacing: 2px; font-weight: 900; opacity: 0.5;">SECURE PASSPHRASE</label>
                    <input type="password" name="password" placeholder="••••••••" required style="background: rgba(0,0,0,0.02); border: 1px solid rgba(0,0,0,0.1); padding: 1rem; border-radius: 8px; width: 100%;">
                </div>

                <button type="submit" class="submit-btn" style="width:100%; margin-top: 1rem; padding: 1.2rem;">INITIATE ACCESS</button>
            </form>

            <div style="margin-top: 2rem; text-align: center; font-size: 0.9rem; opacity: 0.6;">
                Already have access? <a href="login" style="color: var(--text-primary); font-weight: 700; text-decoration: none;">Login instead</a>
            </div>
        </div>
    </section>
</body>
</html>
