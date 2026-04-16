<?php
require_once '../../core/auth_check.php';
require_once '../../core/db.php';

// Handle Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['toggle_featured'])) {
        $id = $_POST['review_id'];
        $stmt = $pdo->prepare("UPDATE testimonials SET status = CASE WHEN status = 'featured' THEN 'pending' ELSE 'featured' END WHERE id = ?");
        $stmt->execute([$id]);
    } elseif (isset($_POST['delete_review'])) {
        $id = $_POST['review_id'];
        $stmt = $pdo->prepare("DELETE FROM testimonials WHERE id = ?");
        $stmt->execute([$id]);
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Analytics
$reviews = $pdo->query("SELECT * FROM testimonials ORDER BY created_at DESC")->fetchAll();
$total_reviews = count($reviews);
$featured_count = $pdo->query("SELECT COUNT(*) FROM testimonials WHERE status = 'featured'")->fetchColumn();
$avg_rating = $pdo->query("SELECT AVG(rating) FROM testimonials")->fetchColumn() ?: 0;
$avg_rating = round($avg_rating, 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <base href="/beetlesystem/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sentiment Nebula | Beetle System</title>
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
            <div>
                <h1 class="admin-page-title">Sentiment Nebula</h1>
                <p style="font-size:0.85rem; opacity:0.6; font-weight:600; letter-spacing:1px; text-transform:uppercase;">Curation of Client Consciousness</p>
            </div>
            <div class="admin-user-badge">
                <i class="fas fa-satellite" style="color:var(--accent);"></i>
                <strong style="margin-left:5px;"><?php echo $total_reviews; ?> SIGNALS CAPTURED</strong>
            </div>
        </div>

        <!-- Sophisticated Analytics Header -->
        <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 1.5rem; margin-bottom: 3rem;">
            <!-- Main Score Board -->
            <div style="background: #F8F6EF; border-radius: 16px; padding: 2.5rem; display: flex; align-items: center; gap: 3rem; box-shadow: 0 10px 40px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.04);">
                <div style="text-align:center;">
                    <div style="font-size: 5rem; font-weight: 900; line-height: 1; color: var(--text-primary); font-family: var(--font-heading);"><?php echo $avg_rating; ?></div>
                    <div style="font-size: 0.65rem; font-weight: 800; color: #888; letter-spacing: 2px; margin-top: 0.5rem; text-transform: uppercase;">System Average</div>
                </div>
                <div style="flex:1; border-left: 1px solid rgba(0,0,0,0.08); padding-left: 3rem;">
                    <div style="color: var(--accent); font-size: 1.8rem; margin-bottom: 0.5rem; display: flex; gap: 0.3rem;">
                        <?php 
                        $full_stars = floor($avg_rating);
                        for($i=0; $i<5; $i++) {
                            if($i < $full_stars) echo '<i class="fas fa-star"></i>';
                            elseif($i == $full_stars && ($avg_rating - $full_stars) >= 0.5) echo '<i class="fas fa-star-half-alt"></i>';
                            else echo '<i class="far fa-star"></i>';
                        }
                        ?>
                    </div>
                    <p style="font-size: 0.95rem; color: #666; font-weight: 400; line-height: 1.6;">Aggregated from <strong style="color:#000;"><?php echo $total_reviews; ?></strong> verified transmissions across the digital landscape.</p>
                </div>
            </div>

            <!-- Fast Stats -->
            <div style="background:#F8F6EF; border-radius:16px; padding:2rem; display:flex; flex-direction:column; justify-content:center; box-shadow: 0 10px 40px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.04);">
                <div style="font-size: 2.5rem; font-weight: 800; color:#111; font-family:var(--font-heading); line-height:1;"><?php echo $total_reviews; ?></div>
                <div style="font-size:0.75rem; color:#888; font-weight:700; letter-spacing:1px; text-transform:uppercase; margin-top:0.5rem;">Vocal Nodes</div>
                <i class="fas fa-satellite-dish" style="font-size:2.5rem; color:rgba(0,0,0,0.05); position:absolute; right:2rem; top:2rem;"></i>
            </div>
            
            <div style="background:#EBE8D8; border-radius:16px; padding:2rem; display:flex; flex-direction:column; justify-content:center; border: 1px solid rgba(0,0,0,0.08);">
                <div style="font-size: 2.5rem; font-weight: 800; color:#111; font-family:var(--font-heading); line-height:1;"><?php echo $featured_count; ?></div>
                <div style="font-size:0.75rem; color:#666; font-weight:800; letter-spacing:1px; text-transform:uppercase; margin-top:0.5rem;">Live Broadcasts</div>
            </div>
        </div>

        <!-- Elegant Signals Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(360px, 1fr)); gap: 1.5rem;">
            <?php if (empty($reviews)): ?>
                <div style="grid-column: 1/-1; text-align:center; padding: 6rem; background:#F8F6EF; border-radius:16px; border: 1px dashed rgba(0,0,0,0.1);">
                    <i class="fas fa-comment-slash fa-3x" style="margin-bottom:1.5rem; color:#ccc;"></i>
                    <p style="font-weight:700; letter-spacing:2px; color:#888;">NO SIGNALS IN QUEUE</p>
                </div>
            <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                    <div style="background: #F8F6EF; border-radius: 12px; padding: 2rem; position: relative; overflow: hidden; display: flex; flex-direction: column; transition: transform 0.3s ease; border: 1px solid rgba(0,0,0,0.06); box-shadow: 0 8px 24px rgba(0,0,0,0.02);">
                        
                        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1.2rem;">
                            <div>
                                <strong style="font-size:1.25rem; color:#111; display:block; margin-bottom:0.2rem; font-family:var(--font-heading);"><?php echo htmlspecialchars($review['author']); ?></strong>
                                <span style="font-size:0.7rem; color:#888; font-weight:700; text-transform:uppercase; letter-spacing:1px;"><?php echo htmlspecialchars($review['position'] ?? 'Client'); ?></span>
                            </div>
                            <div style="background: rgba(0, 0, 0, 0.03); padding: 0.35rem 0.7rem; border-radius: 20px; display:flex; gap:0.2rem; align-items:center;">
                                <?php for($i=0; $i<$review['rating']; $i++): ?>
                                    <i class="fas fa-star" style="color:var(--accent); font-size:0.65rem;"></i>
                                <?php endfor; ?>
                            </div>
                        </div>

                        <div style="flex:1; margin-bottom: 2rem;">
                            <p style="color: #444; font-size: 0.95rem; line-height: 1.6; font-style: italic;">
                                “<?php echo htmlspecialchars($review['content']); ?>”
                            </p>
                        </div>

                        <div style="display:flex; gap:0.8rem; border-top:1px solid rgba(0,0,0,0.04); padding-top:1.2rem;">
                            <button onclick="confirmAction(<?php echo $review['id']; ?>, '<?php echo $review['status'] === 'featured' ? 'Halt Broadcast' : 'Deploy Broadcast'; ?>', 'toggle_featured')" 
                                    style="flex:1; padding:0.8rem; font-weight:700; font-family:var(--font-heading); font-size:0.7rem; letter-spacing:1px; border-radius:6px; cursor:pointer; transition:all 0.2s; 
                                           border:1px solid <?php echo $review['status'] === 'featured' ? 'rgba(0,0,0,0.1)' : '#111'; ?>; 
                                           background:<?php echo $review['status'] === 'featured' ? 'transparent' : '#111'; ?>; 
                                           color:<?php echo $review['status'] === 'featured' ? '#666' : '#F8F6EF'; ?>;">
                                <?php echo $review['status'] === 'featured' ? '<i class="fas fa-eye-slash" style="margin-right:5px;"></i> REMOVE' : '<i class="fas fa-satellite-dish" style="margin-right:5px;"></i> BROADCAST'; ?>
                            </button>
                            
                            <button onclick="confirmAction(<?php echo $review['id']; ?>, 'Data Excision', 'delete_review')" 
                                    style="width:45px; border-radius:6px; border:1px solid rgba(255,59,48,0.2); background:rgba(255,59,48,0.05); color:#ff3b30; cursor:pointer; transition:all 0.2s; display:flex; align-items:center; justify-content:center;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <!-- Confirmation Modal -->
    <div id="confirmModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.4); z-index:9000; backdrop-filter:blur(8px); align-items:center; justify-content:center;">
        <div style="background:#F8F6EF; border:1px solid rgba(0,0,0,0.1); box-shadow:0 20px 40px rgba(0,0,0,0.1); border-radius:12px; max-width:380px; width:90%; text-align:center; padding:2.5rem;">
            <div id="confirmIcon" style="width:60px; height:60px; background:rgba(0,0,0,0.03); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1.5rem; font-size:1.8rem; color:#111;">
                <i class="fas fa-layer-group"></i>
            </div>
            <h3 id="confirmTitle" style="font-family: var(--font-heading); margin-bottom:0.8rem; font-weight:800; color:#111;">Protocol Required</h3>
            <p id="confirmDesc" style="font-size:0.9rem; color:#666; margin-bottom:2rem; line-height:1.5;"></p>
            
            <form method="POST" id="confirmForm">
                <input type="hidden" name="review_id" id="targetId">
                <input type="hidden" name="" id="targetAction" value="1">
                <div style="display:flex; gap:1rem;">
                    <button type="button" onclick="closeConfirm()" style="flex:1; padding:0.8rem; font-weight:700; letter-spacing:1px; font-size:0.75rem; border-radius:6px; cursor:pointer; background:transparent; color:#666; border:1px solid rgba(0,0,0,0.1);">CANCEL</button>
                    <button type="submit" style="flex:1; padding:0.8rem; font-weight:700; letter-spacing:1px; font-size:0.75rem; border-radius:6px; cursor:pointer; background:#111; color:#F8F6EF; border:none;">CONFIRM</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function confirmAction(id, title, action) {
            document.getElementById('targetId').value = id;
            document.getElementById('targetAction').name = action;
            document.getElementById('confirmTitle').innerText = title;
            document.getElementById('confirmDesc').innerText = `Are you sure you want to proceed with this protocol? This action will modify the digital archive.`;
            
            const icon = document.getElementById('confirmIcon');
            if (action === 'delete_review') {
                icon.style.color = '#ff3b30';
                icon.innerHTML = '<i class="fas fa-trash-alt"></i>';
            } else {
                icon.style.color = 'var(--accent)';
                icon.innerHTML = '<i class="fas fa-satellite-dish"></i>';
            }

            document.getElementById('confirmModal').style.display = 'flex';
        }

        function closeConfirm() {
            document.getElementById('confirmModal').style.display = 'none';
        }

        // Close on outside click
        window.onclick = function(event) {
            const modal = document.getElementById('confirmModal');
            if (event.target == modal) closeConfirm();
        }
    </script>
</body>
</html>
