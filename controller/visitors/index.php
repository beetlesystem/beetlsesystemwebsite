<?php
require_once '../../core/auth_check.php';
require_once '../../core/db.php';

// Handle Deletion Protocols
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_visitor'])) {
        $id = (int)$_POST['visitor_id'];
        $stmt = $pdo->prepare("DELETE FROM visitors WHERE id = ?");
        $stmt->execute([$id]);
    } elseif (isset($_POST['clear_all'])) {
        $pdo->query("DELETE FROM visitors");
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Fetch stats
$total_visits = $pdo->query("SELECT COUNT(*) FROM visitors")->fetchColumn();
$today_visits = $pdo->query("SELECT COUNT(*) FROM visitors WHERE DATE(visited_at) = CURDATE()")->fetchColumn();
$unique_ips = $pdo->query("SELECT COUNT(DISTINCT ip_address) FROM visitors")->fetchColumn();

// Fetch recent traffic
$visitors = $pdo->query("SELECT * FROM visitors ORDER BY visited_at DESC LIMIT 100")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <base href="/beetlesystem/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitors | Beetle System</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Outfit:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Centralized CSS -->
    <link rel="stylesheet" href="core/style.css">
    <link rel="icon" type="image/svg+xml" href="core/favicon.svg">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

    <script src="core/main.js"></script>
</head>
<body class="admin-layout">
    
    <!-- Sidebar -->
    <?php include '../../includes/aside.php'; ?>

    <!-- Main Content -->
    <main class="admin-main">
        <div class="admin-header">
            <h1 class="admin-page-title">Visitors</h1>
            <div class="admin-user-badge">
                <i class="fas fa-signal" style="color:var(--accent);"></i>
                <strong style="margin-left:5px;">Recent Visits</strong>
            </div>
        </div>

        <div class="admin-stats-grid">
            <div class="admin-stat-card">
                <i class="fas fa-users" style="color:var(--accent);"></i>
                <div class="admin-stat-value"><?php echo number_format($total_visits); ?></div>
                <div style="color:#666; font-size:0.85rem; font-weight:600;">Total Visits</div>
            </div>
            <div class="admin-stat-card">
                <i class="fas fa-bolt" style="color:var(--accent);"></i>
                <div class="admin-stat-value"><?php echo number_format($today_visits); ?></div>
                <div style="color:#666; font-size:0.85rem; font-weight:600;">Active Today</div>
            </div>
            <div class="admin-stat-card">
                <i class="fas fa-fingerprint" style="color:var(--accent);"></i>
                <div class="admin-stat-value"><?php echo number_format($unique_ips); ?></div>
                <div style="color:#666; font-size:0.85rem; font-weight:600;">Unique Visitors</div>
            </div>
        </div>

        <div class="admin-card">
            <div style="display:flex; flex-wrap: wrap; gap:1rem; justify-content:space-between; align-items:center; margin-bottom: 2rem;">
                <h3 style="font-family: var(--font-heading); margin: 0;"><i class="fas fa-satellite-dish"></i> Visitor Logs</h3>
                <?php if (!empty($visitors)): ?>
                    <button onclick="confirmVisitorAction('all', 'clear_all')" class="submit-btn" style="background: rgba(255, 59, 48, 0.1); color: #ff3b30; border: 1px solid rgba(255, 59, 48, 0.2); font-size: 0.75rem; padding: 0.8rem 1.5rem;  letter-spacing: 1px; border-radius: 8px;">
                        <i class="fas fa-eraser" style="margin-right: 8px;"></i> DELETE ALL RECORDS
                    </button>
                <?php endif; ?>
            </div>
            <div style="overflow-x: auto;">
                <table style="width:100%; border-collapse: separate; border-spacing: 0 10px;">
                    <thead>
                        <tr style="text-align:left; color:#888; font-size: 0.75rem; letter-spacing: 2px;">
                            <th style="padding:1rem;">IP ADDRESS</th>
                            <th style="padding:1rem;">PAGE VISITED</th>
                            <th style="padding:1rem;">TIMESTAMP</th>
                            <th style="padding:1rem;">OS / BROWSER</th>
                            <th style="padding:1rem; text-align:right;">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($visitors as $v): ?>
                            <tr style="background: rgba(0,0,0,0.02); transition: transform 0.3s ease;">
                                <td style="padding:1.2rem; font-family: monospace; font-weight:700; border-radius: 12px 0 0 12px;"><?php echo htmlspecialchars($v['ip_address']); ?></td>
                                <td style="padding:1.2rem;">
                                    <span style="background:var(--accent); color:#fff; padding: 0.2rem 0.8rem; border-radius: 20px; font-size: 0.7rem; font-weight:900;">
                                        <?php echo strtoupper(htmlspecialchars($v['page_url'])); ?>
                                    </span>
                                </td>
                                <td style="padding:1.2rem; color:#666; font-size:0.85rem;"><?php echo date('M d, H:i:s', strtotime($v['visited_at'])); ?></td>
                                <td style="padding:1.2rem;">
                                    <div style="display:flex; align-items:center; gap:0.8rem;">
                                        <div style="font-size:1.1rem; color:#444;">
                                            <?php 
                                                $os = $v['os'] ?? 'Unknown';
                                                $browser = $v['browser'] ?? 'Unknown';
                                                $device = $v['device_type'] ?? 'Desktop';
                                                
                                                $os_icon = 'fa-question-circle';
                                                if (stripos($os, 'Windows') !== false) $os_icon = 'fa-windows';
                                                elseif (stripos($os, 'MacOS') !== false) $os_icon = 'fa-apple';
                                                elseif (stripos($os, 'Android') !== false) $os_icon = 'fa-android';
                                                elseif (stripos($os, 'iOS') !== false) $os_icon = 'fa-mobile-screen-button';
                                                elseif (stripos($os, 'Linux') !== false) $os_icon = 'fa-linux';

                                                $browser_icon = 'fa-globe';
                                                if (stripos($browser, 'Chrome') !== false) $browser_icon = 'fa-chrome';
                                                elseif (stripos($browser, 'Firefox') !== false) $browser_icon = 'fa-firefox-browser';
                                                elseif (stripos($browser, 'Safari') !== false) $browser_icon = 'fa-safari';
                                                elseif (stripos($browser, 'Edge') !== false) $browser_icon = 'fa-edge';
                                            ?>
                                            <i class="fab <?php echo $os_icon; ?> fa-fw" title="<?php echo $os; ?>"></i>
                                            <i class="fab <?php echo $browser_icon; ?> fa-fw" style="font-size:0.9rem; opacity:0.6;" title="<?php echo $browser; ?>"></i>
                                        </div>
                                        <div style="line-height:1;">
                                            <div style="font-size:0.8rem; font-weight:700;"><?php echo $os; ?></div>
                                            <div style="font-size:0.65rem; opacity:0.5; font-weight:600; text-transform:uppercase;"><?php echo $browser; ?> • <?php echo $device; ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding:1.2rem; border-radius: 0 12px 12px 0; text-align:right;">
                                    <button onclick="confirmVisitorAction('<?php echo $v['id']; ?>', 'delete_visitor')" style="background:transparent; border:1px solid rgba(255, 59, 48, 0.2); color:#ff3b30; width:32px; height:32px; border-radius:6px; cursor:pointer; transition:all 0.2s; display:inline-flex; align-items:center; justify-content:center;" title="Delete Record">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($visitors)): ?>
                            <tr><td colspan="5" style="padding:4rem; text-align:center; opacity:0.3; font-style:italic;">No visitors found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Unified Confirmation Modal -->
    <div id="confirmModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9000; backdrop-filter:blur(5px); align-items:center; justify-content:center;">
        <div style="background:var(--bg-primary); border:1px solid rgba(0,0,0,0.05); box-shadow:0 30px 60px rgba(0,0,0,0.15); border-radius:12px; max-width:400px; width:90%; text-align:center; padding:3rem;">
            <div id="confirmIcon" style="width:70px; height:70px; background:rgba(255, 59, 48, 0.1); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1.5rem; font-size:2rem; color:#ff3b30;">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 id="confirmTitle" style="font-family: var(--font-heading); margin-bottom:0.8rem; font-weight:800; color:#111; letter-spacing: 1px;">DELETE LOGS</h3>
            <p id="confirmDesc" style="font-size:0.95rem; color:#666; margin-bottom:2.5rem; line-height:1.5;">Are you sure you want to delete this log? This action cannot be undone.</p>
            
            <form method="POST" id="confirmForm">
                <input type="hidden" name="visitor_id" id="targetId">
                <input type="hidden" name="" id="targetAction" value="1">
                <div style="display:flex; gap:1rem;">
                    <button type="button" onclick="closeConfirm()" style="flex:1; padding:0.8rem; font-weight:700; letter-spacing:1px; font-size:0.75rem; border-radius:6px; cursor:pointer; background:transparent; color:#666; border:1px solid rgba(0,0,0,0.1); transition: 0.3s;">CANCEL</button>
                    <button type="submit" id="confirmSubmitBtn" style="flex:1; padding:0.8rem; font-weight:700; letter-spacing:1px; font-size:0.75rem; border-radius:6px; cursor:pointer; background:#ff3b30; color:#fff; border:none; transition: 0.3s;">DELETE</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function confirmVisitorAction(id, action) {
            const modal = document.getElementById('confirmModal');
            document.getElementById('targetId').value = id;
            document.getElementById('targetAction').name = action;
            
            const title = document.getElementById('confirmTitle');
            const desc = document.getElementById('confirmDesc');
            
            if (action === 'clear_all') {
                title.innerText = 'DELETE ALL LOGS';
                desc.innerText = 'Are you sure you want to delete ALL visitor logs? This cannot be undone.';
            } else {
                title.innerText = 'DELETE LOG';
                desc.innerText = 'Are you sure you want to delete this visitor log?';
            }
            
            modal.style.display = 'flex';
        }

        function closeConfirm() {
            document.getElementById('confirmModal').style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('confirmModal');
            if (event.target == modal) {
                closeConfirm();
            }
        }
    </script>
</body>
</html>
