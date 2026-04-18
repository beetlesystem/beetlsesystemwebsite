<?php
// Detect environment
if (isset($_SERVER['HTTP_HOST']) && ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['REMOTE_ADDR'] === '127.0.0.1')) {
    // LOCAL DEVELOPMENT
    $host = 'localhost';
    $db_name = 'beetle_system';
    $username = 'root';
    $password = '';
    define('BASE_URL', '/beetlesystem/');
} else {
    // PRODUCTION SETTINGS
    $host = 'localhost'; // Usually remains localhost on most cPanel/VPS hosts
    $db_name = 'u167160735_beetlesystem';
    $username = 'u167160735_beetlesystem';
    $password = 'Admin@Bs123';
    define('BASE_URL', '/');
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Silent Migration: Ensure 'status' column exists and populate it
    try {
        // 1. Check if column exists
        $pdo->query("SELECT status FROM testimonials LIMIT 1");
    } catch (Exception $e) {
        // 2. Add column if missing
        $pdo->exec("ALTER TABLE testimonials ADD COLUMN status ENUM('pending', 'featured') DEFAULT 'featured'");
    }

    // 3. Ensure we have at least some "featured" content if any exist
    $check = $pdo->query("SELECT count(*) FROM testimonials WHERE status = 'featured'")->fetchColumn();
    if ($check == 0) {
        $pdo->exec("UPDATE testimonials SET status = 'featured' ORDER BY created_at DESC LIMIT 6");
    }
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Security Keys
define('SITE_KEY', 'beetle_secret_2026_xyz'); 
?>
