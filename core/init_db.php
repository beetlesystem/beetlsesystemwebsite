<?php
$host = 'localhost';
$username = 'root';
$password = '';

try {
    // Connect to MySQL server without database selected
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS beetle_system");
    $pdo->exec("USE beetle_system");

    // Users table
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        admin_id VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        full_name VARCHAR(100),
        role ENUM('admin', 'manager') DEFAULT 'admin',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Contacts table
    $pdo->exec("CREATE TABLE IF NOT EXISTS contacts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        subject VARCHAR(255),
        phone VARCHAR(20),
        service VARCHAR(50),
        budget VARCHAR(50),
        message TEXT,
        status ENUM('new', 'responded', 'archived') DEFAULT 'new',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS projects (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(100) NOT NULL,
        description TEXT,
        client VARCHAR(100),
        year INT,
        category VARCHAR(50),
        image_url VARCHAR(255),
        video_url VARCHAR(255),
        project_url VARCHAR(255),
        is_featured TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Testimonials table
    $pdo->exec("CREATE TABLE IF NOT EXISTS testimonials (
        id INT AUTO_INCREMENT PRIMARY KEY,
        author VARCHAR(100) NOT NULL,
        position VARCHAR(100),
        company VARCHAR(100),
        content TEXT,
        rating INT DEFAULT 5,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Visitors table
    $pdo->exec("CREATE TABLE IF NOT EXISTS visitors (
        id INT AUTO_INCREMENT PRIMARY KEY,
        ip_address VARCHAR(45),
        user_agent TEXT,
        page_url VARCHAR(255),
        visited_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Insert a default admin if none exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE admin_id = 'AD-001-A'");
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        $password = password_hash('admin123', PASSWORD_DEFAULT);
        $pdo->exec("INSERT INTO users (admin_id, password, full_name, role) VALUES ('AD-001-A', '$password', 'System Administrator', 'admin')");
    }

    echo "Database and tables initialized successfully.";
} catch (PDOException $e) {
    die("Initialization failed: " . $e->getMessage());
}
?>
