<?php
// Detect environment
if (isset($_SERVER['HTTP_HOST']) && ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['REMOTE_ADDR'] === '127.0.0.1')) {
    // LOCAL DEVELOPMENT
    $host = 'localhost';
    $db_name = 'beetle_system';
    $username = 'root';
    $password = '';
} else {
    // PRODUCTION SETTINGS
    $host = 'localhost'; // Usually remains localhost on most cPanel/VPS hosts
    $db_name = 'beetle_system_live';
    $username = 'live_user';
    $password = 'live_password_here';
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Security Keys
define('SITE_KEY', 'beetle_secret_2026_xyz'); 
?>
