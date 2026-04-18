<?php
require_once '../../core/auth_check.php';
require_once '../../core/db.php';
require_once '../../core/csrf.php';

// Handle Protocols
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify(false);
    if (isset($_POST['mark_read_id'])) {
        $stmt = $pdo->prepare("UPDATE contacts SET status = 'responded' WHERE id = ?");
        $stmt->execute([$_POST['mark_read_id']]);
    } elseif (isset($_POST['delete_contact_id'])) {
        $stmt = $pdo->prepare("DELETE FROM contacts WHERE id = ?");
        $stmt->execute([$_POST['delete_contact_id']]);
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$inquiries = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC")->fetchAll();
$total_inquiries = count($inquiries);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <base href="<?php echo BASE_URL; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages | Beetle System</title>
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
            <h1 class="admin-page-title">Messages</h1>
            <div class="admin-user-badge">
                <i class="fas fa-paper-plane" style="color:var(--accent);"></i>
                <strong style="margin-left:5px;"><?php echo $total_inquiries; ?> Total Messages</strong>
            </div>
        </div>

        <div class="admin-stats-grid">
            <div class="admin-stat-card">
                <i class="fas fa-circle" style="color:#ff3b30; font-size:0.6rem;"></i>
                <div class="admin-stat-value"><?php echo count(array_filter($inquiries, fn($i) => $i['status'] === 'new')); ?></div>
                <div style="color:#666; font-size:0.85rem; font-weight:600;">Unread Messages</div>
            </div>
            <div class="admin-stat-card">
                <i class="fas fa-check-circle" style="color:#28a745;"></i>
                <div class="admin-stat-value"><?php echo count(array_filter($inquiries, fn($i) => $i['status'] !== 'new')); ?></div>
                <div style="color:#666; font-size:0.85rem; font-weight:600;">Replied Messages</div>
            </div>
        </div>

        <div class="admin-card">
            <h3 style="margin-bottom: 2rem; font-family: var(--font-heading);"><i class="fas fa-envelope-open-text"></i> Recent Messages</h3>
            
            <?php if (empty($inquiries)): ?>
                <div style="text-align:center; padding: 2rem; opacity:0.5;">
                    <i class="fas fa-satellite-dish fa-3x" style="margin-bottom:1rem;"></i>
                    <p>No messages found.</p>
                </div>
            <?php else: ?>
                <div style="display:grid; gap:1.5rem;">
                <?php foreach ($inquiries as $row): ?>
                    <div class="admin-card" onclick='viewInquiry(<?php echo json_encode($row); ?>)' style="cursor:pointer; position:relative; padding:1rem; transition: transform 0.3s; border: 1px solid rgba(0,0,0,0.05);">
                        <?php if ($row['status'] === 'new'): ?>
                            <div class="new-indicator" style="position:absolute; top:1.2rem; right:1.2rem;"></div>
                        <?php endif; ?>
                        
                        <div style="margin-bottom:1rem;">
                            <strong style="font-size:1.1rem; display:block;"><?php echo htmlspecialchars($row['name']); ?></strong>
                            <span style="font-size:0.8rem; color:var(--accent); font-weight:700;"><?php echo htmlspecialchars($row['subject'] ?? 'NEW MESSAGE'); ?></span>
                        </div>
                        
                        <p style="font-size:0.85rem; color:#666; line-height:1.5; height:3em; overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; margin-bottom:1rem;">
                            <?php echo htmlspecialchars($row['message']); ?>
                        </p>
                        
                        <div style="display:flex; justify-content:space-between; align-items:center; font-size:0.75rem; border-top:1px solid rgba(0,0,0,0.05); pt:0.8rem; margin-top:auto; padding-top:0.8rem;">
                            <span style="opacity:0.6;"><i class="fas fa-calendar"></i> <?php echo date('M d, H:i', strtotime($row['created_at'])); ?></span>
                            <div style="display:flex; gap:0.5rem; align-items:center;">
                                <span style="font-weight:700;">VIEW DETAILS <i class="fas fa-arrow-right" style="font-size:0.6rem;"></i></span>
                                <div style="height:12px; width:1px; background:rgba(0,0,0,0.1);"></div>
                                <button type="button" onclick="event.stopPropagation(); confirmContactDelete('<?php echo $row['id']; ?>')" style="background:transparent; border:none; color:#ff3b30; cursor:pointer;" title="Delete Record">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Inquiry Detail Modal -->
    <div id="inquiryModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:2000; backdrop-filter:blur(15px); align-items:center; justify-content:center;">
        <div class="admin-card" style="width:100%; max-width:600px; margin-bottom:0; background:#fff; padding:2rem; margin:2rem;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem;">
                <h2 style="font-family: var(--font-heading); margin-bottom:0; letter-spacing:1px;">MESSAGE DETAILS</h2>
                <button onclick="closeInquiryModal()" style="background:none; border:none; font-size:1.5rem; cursor:pointer; opacity:0.5;">&times;</button>
            </div>
            
            <div style="display:grid; gap:2rem; margin-bottom:2.5rem;">
                <div>
                    <label style="font-size:0.65rem; font-weight:900; opacity:0.4; display:block; margin-bottom:0.5rem; text-transform:uppercase; letter-spacing:1px;">Contact Information</label>
                    <div id="inqName" style="font-weight:800; font-size:1.2rem;"></div>
                    <div id="inqEmail" style="font-size:0.85rem; color:var(--accent); font-weight:600;"></div>
                    <div id="inqPhone" style="font-size:0.85rem; opacity:0.7;"></div>
                </div>
                <div>
                    <label style="font-size:0.65rem; font-weight:900; opacity:0.4; display:block; margin-bottom:0.5rem; text-transform:uppercase; letter-spacing:1px;">Date & Service</label>
                    <div id="inqDate" style="font-weight:700;"></div>
                    <div id="inqService" style="font-size:0.85rem; opacity:0.7; margin-top:0.2rem;"></div>
                </div>
            </div>

            <div style="margin-bottom:2rem;">
                <label style="font-size:0.65rem; font-weight:900; opacity:0.4; display:block; margin-bottom:1rem; text-transform:uppercase; letter-spacing:1px;">Message</label>
                <div id="inqMessage" style="font-size:1rem; line-height:1.7; background:rgba(0,0,0,0.03); padding:2rem; border-radius:12px; border-left:4px solid var(--accent); white-space: pre-wrap;"></div>
            </div>

            <form method="POST" id="readForm">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="mark_read_id" id="readInquiryId">
                <div style="display:flex; flex-direction:column-reverse; gap:1.5rem;">
                    <a id="replyBtn" href="#" class="submit-btn" style="flex:1; text-align:center; text-decoration:none; display:flex; align-items:center; justify-content:center; gap:0.5rem; background:#000;">
                        <i class="fas fa-reply"></i> REPLY
                    </a>
                    <button type="submit" id="readBtn" class="submit-btn" style="flex:1; background:var(--accent);">
                        <i class="fas fa-check-double"></i> MARK AS READ
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Unified Confirmation Modal -->
    <div id="confirmModaContact" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9000; backdrop-filter:blur(5px); align-items:center; justify-content:center;">
        <div style="background:var(--bg-primary); border:1px solid rgba(0,0,0,0.05); box-shadow:0 30px 60px rgba(0,0,0,0.15); border-radius:12px; max-width:400px; width:90%; text-align:center; padding:2rem;">
            <div style="width:70px; height:70px; background:rgba(255, 59, 48, 0.1); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1.5rem; font-size:2rem; color:#ff3b30;">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 style="font-family: var(--font-heading); margin-bottom:0.8rem; font-weight:800; color:#111; letter-spacing: 1px;">DELETE MESSAGE</h3>
            <p style="font-size:0.95rem; color:#666; margin-bottom:2.5rem; line-height:1.5;">Are you sure you want to permanently delete this message? This action cannot be undone.</p>
            
            <form method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="delete_contact_id" id="targetContactId">
                <div style="display:flex; gap:1rem;">
                    <button type="button" onclick="closeContactDelete()" style="flex:1; padding:0.8rem; font-weight:700; letter-spacing:1px; font-size:0.75rem; border-radius:6px; cursor:pointer; background:transparent; color:#666; border:1px solid rgba(0,0,0,0.1); transition: 0.3s;">CANCEL</button>
                    <button type="submit" style="flex:1; padding:0.8rem; font-weight:700; letter-spacing:1px; font-size:0.75rem; border-radius:6px; cursor:pointer; background:#ff3b30; color:#fff; border:none; transition: 0.3s;">DELETE</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function confirmContactDelete(id) {
            document.getElementById('targetContactId').value = id;
            document.getElementById('confirmModaContact').style.display = 'flex';
        }
        function closeContactDelete() {
            document.getElementById('confirmModaContact').style.display = 'none';
        }

        const inquiries = <?php echo json_encode($inquiries); ?>;
        
        function viewInquiry(inq) {
            document.getElementById('inqName').innerText = inq.name;
            document.getElementById('inqEmail').innerText = inq.email;
            document.getElementById('inqPhone').innerText = inq.phone || 'No Phone Number';
            document.getElementById('inqDate').innerText = inq.created_at;
            document.getElementById('inqService').innerText = inq.service || 'General Message';
            document.getElementById('inqMessage').innerText = inq.message;
            document.getElementById('readInquiryId').value = inq.id;
            
            const subject = encodeURIComponent(`RE: ${inq.subject || 'Beetle System Inquiry'}`);
            document.getElementById('replyBtn').href = `mailto:${inq.email}?subject=${subject}`;
            
            const readBtn = document.getElementById('readBtn');
            if (inq.status !== 'new') {
                readBtn.style.display = 'none';
            } else {
                readBtn.style.display = 'block';
            }
            
            document.getElementById('inquiryModal').style.display = 'flex';
        }

        function closeInquiryModal() {
            document.getElementById('inquiryModal').style.display = 'none';
            // Clean URL
            const url = new URL(window.location);
            url.searchParams.delete('view_id');
            window.history.pushState({}, '', url);
        }

        // Auto-open if view_id in URL
        window.addEventListener('load', () => {
            const params = new URLSearchParams(window.location.search);
            const viewId = params.get('view_id');
            if (viewId) {
                const inq = inquiries.find(i => i.id == viewId);
                if (inq) viewInquiry(inq);
            }
        });
    </script>
</body>
</html>
