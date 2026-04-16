<?php
session_start();
require_once '../core/db.php';
require_once '../model/User.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_id = $_POST['admin_id'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($admin_id) || empty($password)) {
        $error = 'Please enter both Administrator ID and Passphrase.';
    } else {
        $userModel = new User($pdo);
        $user = $userModel->login($admin_id, $password);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['admin_id'] = $user['admin_id'];
            $_SESSION['full_name'] = $user['full_name'];
            
            // Set a persistence cookie if needed (optional, but requested)
            setcookie('auth_beetle', base64_encode($user['admin_id']), time() + (86400 * 30), "/"); // 30 days

            header("Location: /beetlesystem/dashboard");
            exit;
        } else {
            $error = 'Invalid Administrator ID or Passphrase.';
        }
    }
}

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: /beetlesystem/dashboard");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <base href="/beetlesystem/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Beetle System</title>
    <meta name="description" content="Access the Beetle System portal for project tracking and collaboration.">
    <link rel="stylesheet" href="core/style.css">
    <link rel="icon" type="image/svg+xml" href="core/favicon.svg">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&family=Outfit:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        .login-page {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-primary);
            overflow: hidden;
        }

        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 3rem;
            position: relative;
            z-index: 10;
        }

        .login-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .login-logo {
            width: 60px;
            height: 60px;
            background: var(--bg-secondary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: var(--text-secondary);
            animation: floatLogo 4s ease-in-out infinite;
        }

        @keyframes floatLogo {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .login-logo svg {
            width: 32px;
            height: 32px;
        }

        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(0,0,0,0.4);
            transition: color 0.3s;
        }

        .password-toggle:hover {
            color: rgba(0,0,0,0.8);
        }

        .password-toggle svg {
            width: 20px;
            height: 20px;
        }

        .captcha-placeholder {
            margin-top: 1rem;
            width: 100%;
            height: 60px;
            background: rgba(0,0,0,0.02);
            border: 1px dashed rgba(0,0,0,0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            letter-spacing: 2px;
            font-weight: 700;
            opacity: 0.5;
        }

        .login-title {
            font-family: var(--font-heading);
            font-size: 2rem;
            font-weight: 900;
            letter-spacing: -1px;
        }

        .login-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .form-footer {
            margin-top: 2rem;
            text-align: center;
            font-size: 0.9rem;
            opacity: 0.6;
        }

        .form-footer a {
            color: var(--text-primary);
            font-weight: 700;
            text-decoration: none;
        }

        /* Ambient background glow */
        .login-glow {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(255, 122, 0, 0.03) 0%, transparent 70%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
            z-index: 1;
        }
    </style>
</head>

<body class="inner-page">
    <div class="grain"></div>
    <div id="cursor"></div>
    <div id="cursor-follower"></div>

    <section class="login-page">
        <div class="login-glow"></div>
        <div class="login-container reveal-from-bottom">
            <div class="login-header">
                <a href="/beetlesystem/" class="login-logo">
                    <svg viewBox="0 0 100 120" xmlns="http://www.w3.org/2000/svg">
                        <path d="M50 40 C30 40 25 60 25 80 C25 100 40 110 50 110 Z" fill="currentColor" />
                        <path d="M50 40 C70 40 75 60 75 80 C75 100 60 110 50 110 Z" fill="currentColor" />
                        <circle cx="50" cy="30" r="12" fill="currentColor" />
                        <path d="M45 20 C40 10 35 15 30 10 M55 20 C60 10 65 15 70 10" fill="none" stroke="currentColor" stroke-width="2" />
                    </svg>
                </a>
                <h1 class="login-title">WELCOME BACK.</h1>
                <p style="opacity:0.5; font-size: 0.9rem; margin-top: 0.5rem;">ENTER YOUR CREDENTIALS TO ACCESS THE SYSTEM</p>
            </div>

            <form class="login-form" method="POST" action="">
                <?php if ($error): ?>
                    <div class="error-msg" style="background: rgba(255,0,0,0.05); color: #ff4444; padding: 0.8rem; border-radius: 8px; font-size: 0.8rem; border: 1px solid rgba(255,0,0,0.1); text-align: center;">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="font-size:0.6rem; letter-spacing: 2px; font-weight: 900; opacity: 0.5;">ADMINISTRATOR ID</label>
                    <input type="text" name="admin_id" placeholder="AD-000-X" required style="background: rgba(0,0,0,0.02); border: 1px solid rgba(0,0,0,0.1); padding: 1rem; border-radius: 8px; width: 100%; font-family: var(--font-main);">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="font-size:0.6rem; letter-spacing: 2px; font-weight: 900; opacity: 0.5;">SECURE PASSPHRASE</label>
                    <div class="password-wrapper">
                        <input type="password" name="password" id="login-password" placeholder="••••••••" required style="background: rgba(0,0,0,0.02); border: 1px solid rgba(0,0,0,0.1); padding: 1rem; padding-right: 45px; border-radius: 8px; width: 100%; font-family: var(--font-main);">
                        <button type="button" class="password-toggle" id="toggle-password" aria-label="Toggle password visibility">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="eye-icon">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- CAPTCHA Placeholder -->
                <div id="captcha-container" class="captcha-placeholder">
                    [ CAPTCHA PLACEHOLDER ]
                </div>

                <button type="submit" class="submit-btn group" style="width:100%; margin-top: 1rem; padding: 1.2rem;">
                    AUTHORIZE ACCESS
                </button>
            </form>

            <div class="form-footer">
                <p>Lost your credentials? <a href="/beetlesystem/contact">Contact Headquarters</a></p>
                <a href="/beetlesystem/" style="display: block; margin-top: 2rem; font-size: 0.7rem; letter-spacing: 2px;">← BACK TO HOME</a>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/lenis@latest/dist/lenis.min.js"></script>
    <script src="core/main.js"></script>
    <script>
        // Password Visibility Toggle Logic
        const togglePassword = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('login-password');

        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function () {
                // Toggle the type attribute
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                // Toggle the icon
                const eyeIconPath = type === 'password' 
                    ? '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path><circle cx="12" cy="12" r="3"></circle>'
                    : '<path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path><line x1="2" y1="2" x2="22" y2="22"></line>';
                
                this.querySelector('.eye-icon').innerHTML = eyeIconPath;
            });
        }
    </script>
</body>

</html>
