<?php
require_once '../../core/auth_check.php';
require_once '../../core/db.php';

// Verify table exists
$pdo->exec("CREATE TABLE IF NOT EXISTS clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50),
    status VARCHAR(50) DEFAULT 'lead',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Handle Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_client'])) {
        $id = (int)$_POST['client_id'];
        $stmt = $pdo->prepare("DELETE FROM clients WHERE id = ?");
        $stmt->execute([$id]);
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$clients = $pdo->query("SELECT * FROM clients ORDER BY created_at DESC")->fetchAll();
$total_clients = count($clients);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <base href="/beetlesystem/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Matrix | Beetle System</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Outfit:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Centralized CSS -->
    <link rel="stylesheet" href="core/style.css">
    <link rel="icon" type="image/svg+xml" href="core/favicon.svg">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lenis@latest/dist/lenis.min.js"></script>
    <script src="core/main.js"></script>
</head>
<body class="admin-layout">
    
    <!-- Sidebar -->
    <?php include '../../includes/aside.php'; ?>

    <!-- Main Content -->
    <main class="admin-main">
        <div class="admin-header">
            <h1 class="admin-page-title">Client Matrix</h1>
            <div class="admin-user-badge">
                <i class="fas fa-handshake" style="color:var(--accent);"></i>
                <strong style="margin-left:5px;">Lead Network Directory</strong>
            </div>
        </div>

        <div class="admin-stats-grid">
            <div class="admin-stat-card" style="box-shadow: 0 10px 40px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.04);">
                <i class="fas fa-users" style="color:var(--accent);"></i>
                <div class="admin-stat-value"><?php echo number_format($total_clients); ?></div>
                <div style="color:#666; font-size:0.85rem; font-weight:600;">Total Connected Entities</div>
            </div>
        </div>

        <div class="admin-card" style="box-shadow: 0 10px 40px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.04);">
            <h3 style="margin-bottom: 2rem; font-family: var(--font-heading);"><i class="fas fa-address-book"></i> Client Roster</h3>
            <div style="overflow-x: auto;">
                <table style="width:100%; border-collapse: separate; border-spacing: 0 10px;">
                    <thead>
                        <tr style="text-align:left; color:#888; font-size: 0.75rem; letter-spacing: 2px;">
                            <th style="padding:1rem;">IDENTITY REF</th>
                            <th style="padding:1rem;">COMM LOGS</th>
                            <th style="padding:1rem;">SERVICE SCOPE</th>
                            <th style="padding:1rem;">FIRST CONTACT</th>
                            <th style="padding:1rem; text-align:right;">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clients as $c): ?>
                            <tr style="background: rgba(0,0,0,0.02); transition: transform 0.3s ease;">
                                <td style="padding:1.2rem; font-weight:800; border-radius: 12px 0 0 12px; font-size:1.1rem;"><?php echo htmlspecialchars($c['name']); ?></td>
                                <td style="padding:1.2rem;">
                                    <div style="display:flex; flex-direction:column; gap:0.3rem;">
                                        <a href="mailto:<?php echo htmlspecialchars($c['email']); ?>" style="color:var(--accent); font-weight:700; text-decoration:none;"><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($c['email']); ?></a>
                                        <?php if(!empty($c['phone'])): ?>
                                            <a href="tel:<?php echo htmlspecialchars($c['phone']); ?>" style="color:#666; font-size:0.85rem; text-decoration:none;"><i class="fas fa-phone"></i> <?php echo htmlspecialchars($c['phone']); ?></a>
                                        <?php else: ?>
                                            <span style="color:#aaa; font-size:0.8rem; font-style:italic;">No Telecom Ref</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td style="padding:1.2rem;">
                                    <span style="background: #000; color:#fff; padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.7rem; font-weight:900; text-transform:uppercase; letter-spacing:1px;">
                                        <?php echo htmlspecialchars($c['service'] ?? 'General Link'); ?>
                                    </span>
                                </td>
                                <td style="padding:1.2rem; color:#666; font-size:0.85rem; font-weight:600;"><?php echo date('M d, Y', strtotime($c['created_at'])); ?></td>
                                <td style="padding:1.2rem; border-radius: 0 12px 12px 0; text-align:right;">
                                    <div style="display:flex; justify-content:flex-end; gap:0.8rem; align-items:center;">
                                        <?php if(!empty($c['message'])): ?>
                                        <button onclick="viewClientTrace(<?php echo htmlspecialchars(json_encode($c)); ?>)" style="background:transparent; border:none; color:var(--accent); cursor:pointer; font-size:0.85rem; font-weight:700; display:flex; align-items:center; gap:0.4rem; padding:0.5rem;" title="View Full Trace">
                                            <i class="fas fa-eye"></i> VIEW
                                        </button>
                                        <div style="height:14px; width:1px; background:rgba(0,0,0,0.1);"></div>
                                        <?php endif; ?>
                                        <button onclick="confirmClientDelete('<?php echo $c['id']; ?>')" style="background:transparent; border:1px solid rgba(255, 59, 48, 0.2); color:#ff3b30; width:36px; height:36px; border-radius:8px; cursor:pointer; transition:all 0.2s; display:inline-flex; align-items:center; justify-content:center;" title="Delete Client">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($clients)): ?>
                            <tr><td colspan="6" style="padding:4rem; text-align:center; opacity:0.3; font-style:italic;">No client data intercepted.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Detailed Trace Modal -->
    <div id="clientModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:2000; backdrop-filter:blur(15px); align-items:center; justify-content:center;">
        <div class="admin-card" style="width:100%; max-width:600px; margin-bottom:0; background:#fff; padding:3rem;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem;">
                <h2 style="font-family: var(--font-heading); margin-bottom:0; letter-spacing:1px;">IDENTITY OVERVIEW</h2>
                <button onclick="closeClientModal()" style="background:none; border:none; font-size:1.5rem; cursor:pointer; opacity:0.5;">&times;</button>
            </div>
            
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:2rem; margin-bottom:2.5rem;">
                <div>
                    <label style="font-size:0.65rem; font-weight:900; opacity:0.4; display:block; margin-bottom:0.5rem; text-transform:uppercase; letter-spacing:1px;">Agent Identity</label>
                    <div id="viewClientName" style="font-weight:800; font-size:1.3rem;"></div>
                    <div id="viewClientEmail" style="font-size:0.85rem; color:var(--accent); font-weight:600;"></div>
                    <div id="viewClientPhone" style="font-size:0.85rem; opacity:0.7;"></div>
                </div>
                <div>
                    <label style="font-size:0.65rem; font-weight:900; opacity:0.4; display:block; margin-bottom:0.5rem; text-transform:uppercase; letter-spacing:1px;">Target Scope</label>
                    <div id="viewClientService" style="font-weight:700;"></div>
                    <div id="viewClientDate" style="font-size:0.85rem; opacity:0.7; margin-top:0.3rem;"></div>
                </div>
            </div>

            <div style="margin-bottom:3rem;">
                <label style="font-size:0.65rem; font-weight:900; opacity:0.4; display:block; margin-bottom:1rem; text-transform:uppercase; letter-spacing:1px;">Detailed Briefing</label>
                <div id="viewClientMessage" style="font-size:1rem; line-height:1.7; background:rgba(0,0,0,0.03); padding:2rem; border-radius:12px; border-left:4px solid var(--accent); white-space: pre-wrap;"></div>
            </div>

            <div style="display:flex; gap:1.5rem;">
                <a id="clientReplyBtn" href="#" class="submit-btn" style="flex:1; text-align:center; text-decoration:none; display:flex; align-items:center; justify-content:center; gap:0.5rem; background:#000;">
                    <i class="fas fa-reply"></i> INITIATE PROTOCOL
                </a>
                <button onclick="closeClientModal()" class="submit-btn" style="flex:1; background:var(--accent);">
                    DISMISS
                </button>
            </div>
        </div>
    </div>

    <!-- Unified Confirmation Modal for Direct Client Erasure -->
    <div id="confirmModalClient" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9000; backdrop-filter:blur(5px); align-items:center; justify-content:center;">
        <div style="background:var(--bg-primary); border:1px solid rgba(0,0,0,0.05); box-shadow:0 30px 60px rgba(0,0,0,0.15); border-radius:12px; max-width:400px; width:90%; text-align:center; padding:3rem;">
            <div style="width:70px; height:70px; background:rgba(255, 59, 48, 0.1); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1.5rem; font-size:2rem; color:#ff3b30;">
                <i class="fas fa-user-times"></i>
            </div>
            <h3 style="font-family: var(--font-heading); margin-bottom:0.8rem; font-weight:800; color:#111; letter-spacing: 1px;">WARNING: IDENTITY DESTRUCT</h3>
            <p style="font-size:0.95rem; color:#666; margin-bottom:2.5rem; line-height:1.5;">Are you sure you want to permanently erase this client identity? All linked data will be lost.</p>
            
            <form method="POST">
                <input type="hidden" name="client_id" id="targetClientId">
                <input type="hidden" name="delete_client" value="1">
                <div style="display:flex; gap:1rem;">
                    <button type="button" onclick="document.getElementById('confirmModalClient').style.display='none'" style="flex:1; padding:0.8rem; font-weight:700; letter-spacing:1px; font-size:0.75rem; border-radius:6px; cursor:pointer; background:transparent; color:#666; border:1px solid rgba(0,0,0,0.1); transition: 0.3s;">CANCEL</button>
                    <button type="submit" style="flex:1; padding:0.8rem; font-weight:700; letter-spacing:1px; font-size:0.75rem; border-radius:6px; cursor:pointer; background:#ff3b30; color:#fff; border:none; transition: 0.3s;">ERASE IDENTITY</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function confirmClientDelete(id) {
            document.getElementById('targetClientId').value = id;
            document.getElementById('confirmModalClient').style.display = 'flex';
        }
        
        function viewClientTrace(client) {
            document.getElementById('viewClientName').innerText = client.name;
            document.getElementById('viewClientEmail').innerText = client.email;
            document.getElementById('viewClientPhone').innerText = client.phone || 'NO SECURE LINE';
            document.getElementById('viewClientService').innerText = client.service || 'General Link';
            document.getElementById('viewClientDate').innerText = client.created_at;
            document.getElementById('viewClientMessage').innerText = client.message || 'No direct operative message attached.';
            
            const subject = encodeURIComponent(`RE: ${client.service || 'Beetle System Services'}`);
            document.getElementById('clientReplyBtn').href = `mailto:${client.email}?subject=${subject}`;
            
            document.getElementById('clientModal').style.display = 'flex';
        }

        function closeClientModal() {
            document.getElementById('clientModal').style.display = 'none';
        }
    </script>
</body>
</html>
