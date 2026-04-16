<?php
require_once __DIR__ . '/db.php';

function trackVisitor($pdo, $page = 'General') {
    try {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        
        // 1. Identify Unique Visitor via Cookie (1-year persistence)
        if (!isset($_COOKIE['beetle_vid'])) {
            $vid = bin2hex(random_bytes(16));
            setcookie('beetle_vid', $vid, time() + (3600 * 24 * 365), "/");
        } else {
            $vid = $_COOKIE['beetle_vid'];
        }

        // 2. Parse User Agent for Browser/OS/Device
        $browser = "Unknown";
        if (preg_match('/Edge/i', $ua)) $browser = 'Edge';
        elseif (preg_match('/Firefox/i', $ua)) $browser = 'Firefox';
        elseif (preg_match('/Chrome/i', $ua)) $browser = 'Chrome';
        elseif (preg_match('/Safari/i', $ua)) $browser = 'Safari';
        elseif (preg_match('/OPR/i', $ua) || preg_match('/Opera/i', $ua)) $browser = 'Opera';

        $os = "Unknown OS";
        if (preg_match('/Windows/i', $ua)) $os = 'Windows';
        elseif (preg_match('/Macintosh|Mac OS/i', $ua)) $os = 'MacOS';
        elseif (preg_match('/Linux/i', $ua)) $os = 'Linux';
        elseif (preg_match('/iPhone|iPad/i', $ua)) $os = 'iOS';
        elseif (preg_match('/Android/i', $ua)) $os = 'Android';

        $device = (preg_match('/Mobile|Android|iPhone|iPad/i', $ua)) ? 'Mobile' : 'Desktop';

        // 3. Rate Limiting (Ignore same visitor on same page for 10 minutes)
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM visitors WHERE ip_address = ? AND page_url = ? AND visited_at > DATE_SUB(NOW(), INTERVAL 10 MINUTE)");
        $stmt->execute([$ip, $page]);
        if ($stmt->fetchColumn() == 0) {
            $stmt = $pdo->prepare("INSERT INTO visitors (ip_address, user_agent, device_type, browser, os, page_url) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$ip, $ua, $device, $browser, $os, $page]);
        }
    } catch (Exception $e) {
        // Log error to file if needed: error_log($e->getMessage());
    }
}
?>
