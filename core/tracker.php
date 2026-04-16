<?php
require_once 'db.php';

function trackVisitor($pdo) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    $page = $_SERVER['REQUEST_URI'] ?? '/';
    
    // Prevent tracking admin paths to avoid data skewing
    if (strpos($page, '/controller/') !== false) return;

    // Basic Browser/OS Detection
    $browser = "Unknown Browser";
    if (preg_match('/MSIE/i', $ua) && !preg_match('/Opera/i', $ua)) $browser = 'Internet Explorer';
    elseif (preg_match('/Firefox/i', $ua)) $browser = 'Firefox';
    elseif (preg_match('/Chrome/i', $ua)) $browser = 'Chrome';
    elseif (preg_match('/Safari/i', $ua)) $browser = 'Safari';
    elseif (preg_match('/Opera/i', $ua)) $browser = 'Opera';
    elseif (preg_match('/Netscape/i', $ua)) $browser = 'Netscape';
    
    $os = "Unknown OS";
    if (preg_match('/windows|win32/i', $ua)) $os = 'Windows';
    elseif (preg_match('/macintosh|mac os x/i', $ua)) $os = 'Mac OS';
    elseif (preg_match('/linux/i', $ua)) $os = 'Linux';
    elseif (preg_match('/iphone/i', $ua)) $os = 'iOS';
    elseif (preg_match('/android/i', $ua)) $os = 'Android';

    $device = (preg_match('/mobile|android|iphone|ipad/i', $ua)) ? 'Mobile' : 'Desktop';

    // Anti-Spam: Don't log if the same IP visited same page in last 5 minutes
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM visitors WHERE ip_address = ? AND page_url = ? AND visited_at > DATE_SUB(NOW(), INTERVAL 5 MINUTE)");
    $stmt->execute([$ip, $page]);
    if ($stmt->fetchColumn() == 0) {
        $stmt = $pdo->prepare("INSERT INTO visitors (ip_address, user_agent, device_type, browser, os, page_url) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$ip, $ua, $device, $browser, $os, $page]);
    }
}

// Execute Tracking
trackVisitor($pdo);
?>
