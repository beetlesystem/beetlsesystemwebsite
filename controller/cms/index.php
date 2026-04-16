<?php
require_once '../../core/auth_check.php';
require_once '../../core/db.php';

// Fetch all settings
$settings = $pdo->query("SELECT setting_key, setting_value FROM site_settings")->fetchAll(PDO::FETCH_KEY_PAIR);
$about_image = $settings['about_image'] ?? '';
$pk_starter = json_decode($settings['package_starter'] ?? '[]', true);
$pk_premium = json_decode($settings['package_premium'] ?? '[]', true);
$pk_enterprise = json_decode($settings['package_enterprise'] ?? '[]', true);

// Fetch all reviews
$reviews = $pdo->query("SELECT * FROM testimonials ORDER BY created_at DESC")->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <base href="/beetlesystem/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Core Orchestrator | Beetle System CMS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Outfit:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="core/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lenis@latest/dist/lenis.min.js"></script>

    <style>
        :root {
            --cms-bg: #FDFBF5;
            --cms-card: #FFFFFF;
            --cms-border: rgba(0,0,0,0.06);
            --cms-accent: #FF5C00;
        }

        body, html { overflow: hidden; height: 100vh; font-family: 'Inter', sans-serif; }
        .admin-layout { height: 100vh; display: flex; overflow: hidden; background: #FFF; }
        
        .admin-main { 
            background-color: var(--cms-bg); 
            height: 100vh; 
            flex: 1; 
            display: flex; 
            flex-direction: column; 
            padding: 0; 
            overflow: hidden;
        }

        /* Two-Div Structure */
        .orchestration-terminal {
            display: flex;
            flex: 1;
            min-height: 0;
            gap: 1.5rem;
            padding: 1.5rem;
        }

        .terminal-pane {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            min-width: 0;
        }

        /* Sections */
        .cms-card {
            background: var(--cms-card);
            border: 1px solid var(--cms-border);
            border-radius: 24px;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            min-height: 0;
            box-shadow: 0 10px 40px rgba(0,0,0,0.02);
        }

        .flex-grow { flex: 1; }

        .cms-title {
            font-family: var(--font-heading); font-size: 0.75rem; font-weight: 900;
            letter-spacing: 2px; display: flex; align-items: center; gap: 0.8rem;
            color: #111; text-transform: uppercase; margin-bottom: 1.5rem; flex-shrink: 0;
        }
        .cms-title i { color: var(--cms-accent); }

        /* Scrollers */
        .scroll-v { 
            flex: 1; overflow-y: auto; padding-right: 1rem; 
            scrollbar-width: thin; scrollbar-color: rgba(0,0,0,0.1) transparent;
        }
        .scroll-v::-webkit-scrollbar { width: 4px; }
        .scroll-v::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 10px; }

        /* Form Elements */
        .input-group label { display: block; font-size: 0.6rem; font-weight: 900; color: #BBB; letter-spacing: 1.5px; margin-bottom: 0.8rem; text-transform: uppercase; }
        .cms-input {
            width: 100%; padding: 1.1rem; background: #F9F9F9; border: 1px solid transparent;
            border-radius: 14px; font-family: var(--font-main); font-size: 0.9rem; color: #111;
            transition: 0.2s;
        }
        .cms-input:focus { background: #FFF; border-color: var(--cms-accent); outline: none; box-shadow: 0 5px 20px rgba(255,92,0,0.05); }

        /* Package Tabs */
        .tab-row { display: flex; gap: 0.5rem; background: #F0F0F0; padding: 0.4rem; border-radius: 100px; margin-bottom: 1.5rem; align-self: flex-start; }
        .tab-btn {
            padding: 0.6rem 1.4rem; border-radius: 100px; font-size: 0.6rem; font-weight: 900;
            color: #777; cursor: pointer; transition: 0.2s; border: none; background: transparent; text-transform: uppercase; letter-spacing: 1px;
        }
        .tab-btn.active { background: #000; color: #FFF; }

        .feature-item { display: flex; gap: 0.8rem; align-items: center; margin-bottom: 0.8rem; }
        .remove-icon { color: #FF3B30; cursor: pointer; width: 40px; height: 40px; border-radius: 12px; background: rgba(255,59,48,0.05); display: flex; align-items: center; justify-content: center; transition: 0.2s; }
        .remove-icon:hover { background: rgba(255,59,48,0.1); transform: scale(1.1); }

        .add-action { background: #000; color: #FFF; border: none; padding: 1rem; border-radius: 14px; font-size: 0.75rem; font-weight: 800; cursor: pointer; transition: 0.3s; width: 100%; text-transform: uppercase; letter-spacing: 1.5px; }
        .add-action:hover { background: var(--cms-accent); }

        /* Review Specifically */
        .review-cluster { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1rem; }
        .review-mini-card { background: #FBFBFB; border: 1px solid var(--cms-border); padding: 1.5rem; border-radius: 20px; cursor: pointer; position: relative; transition: 0.3s; }
        .review-mini-card:hover { border-color: var(--cms-accent); background: #FFF; box-shadow: 0 10px 30px rgba(0,0,0,0.03); }
        .delete-btn { position: absolute; top: 1rem; right: 1rem; color: #FF3B30; opacity: 0; transition: 0.2s; }
        .review-mini-card:hover .delete-btn { opacity: 0.3; }
        .delete-btn:hover { opacity: 1 !important; }

        .sync-panel { padding: 1rem 1.5rem; background: #FFF; border-top: 1px solid var(--cms-border); display: flex; justify-content: flex-end; align-items: center; gap: 2rem; }

        /* Modal */
        .cms-modal { position: fixed; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(10px); display: none; align-items: center; justify-content: center; z-index: 1000; padding: 2rem; }
        .cms-modal.active { display: flex; animation: fadeModal 0.4s ease; }
        .model-inner { background: #FFF; width: 100%; max-width: 600px; padding: 3rem; border-radius: 32px; position: relative; }
        @keyframes fadeModal { from { opacity: 0; } to { opacity: 1; } }

        .tab-content { display: none; }
        .tab-content.active { display: block; }
    </style>
</head>
<body class="admin-layout">
    <?php include '../../includes/aside.php'; ?>

    <main class="admin-main">
        <div class="orchestration-terminal">
            
            <!-- LEFT PANE: BRAND & PACKAGES -->
            <div class="terminal-pane">
                
                <!-- BRAND -->
                <div class="cms-card">
                    <h3 class="cms-title"><i class="fas fa-id-card"></i> 01 / BRAND IMAGE</h3>
                    <div class="input-group">
                        <label>IMAGE SOURCE</label>
                        <input type="text" name="about_image" form="cms-master-form" class="cms-input" value="<?php echo htmlspecialchars($about_image); ?>" oninput="document.getElementById('about_preview').src = this.value">
                        <img id="about_preview" src="<?php echo htmlspecialchars($about_image); ?>" style="width:100%; height:120px; object-fit:cover; border-radius:16px; margin-top:1.5rem; border:1px solid var(--cms-border);">
                    </div>
                </div>

                <!-- PACKAGES -->
                <div class="cms-card flex-grow">
                    <h3 class="cms-title"><i class="fas fa-box"></i> 05 / PACKAGES</h3>
                    <div class="tab-row">
                        <button type="button" class="tab-btn active" onclick="switchPackageTab('starter', this)">Starter</button>
                        <button type="button" class="tab-btn" onclick="switchPackageTab('premium', this)">Premium</button>
                        <button type="button" class="tab-btn" onclick="switchPackageTab('enterprise', this)">Enterprise</button>
                    </div>
                    
                    <div class="scroll-v">
                        <div id="starter-content" class="tab-content active">
                            <div id="starter-list">
                                <?php foreach($pk_starter as $li): ?>
                                    <div class="feature-item">
                                        <input type="text" name="package_starter[]" form="cms-master-form" class="cms-input" value="<?php echo htmlspecialchars($li); ?>">
                                        <div class="remove-icon" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" class="add-action" onclick="addFeature('starter-list', 'package_starter[]')">+ ADD FEATURE</button>
                        </div>
                        <div id="premium-content" class="tab-content">
                            <div id="premium-list">
                                <?php foreach($pk_premium as $li): ?>
                                    <div class="feature-item">
                                        <input type="text" name="package_premium[]" form="cms-master-form" class="cms-input" value="<?php echo htmlspecialchars($li); ?>">
                                        <div class="remove-icon" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" class="add-action" onclick="addFeature('premium-list', 'package_premium[]')">+ ADD FEATURE</button>
                        </div>
                        <div id="enterprise-content" class="tab-content">
                            <div id="enterprise-list">
                                <?php foreach($pk_enterprise as $li): ?>
                                    <div class="feature-item">
                                        <input type="text" name="package_enterprise[]" form="cms-master-form" class="cms-input" value="<?php echo htmlspecialchars($li); ?>">
                                        <div class="remove-icon" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" class="add-action" onclick="addFeature('enterprise-list', 'package_enterprise[]')">+ ADD FEATURE</button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- RIGHT PANE: REVIEWS -->
            <div class="terminal-pane">
                <div class="cms-card flex-grow">
                    <h3 class="cms-title"><i class="fas fa-star"></i> 06 / REVIEWS</h3>
                    
                    <!-- Injection Box -->
                    <div style="background:#F9F9F9; padding:1.5rem; border-radius:20px; border:1px solid var(--cms-border); margin-bottom:1.5rem;">
                        <span style="font-size:0.6rem; font-weight:900; letter-spacing:1px; color:#999; display:block; margin-bottom:1rem;">INJECTION PORTAL</span>
                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1rem; margin-bottom:1rem;">
                            <input type="text" id="rev_author" class="cms-input" placeholder="Author">
                            <input type="text" id="rev_pos" class="cms-input" placeholder="Title">
                            <select id="rev_rating" class="cms-input" style="grid-column: span 2;">
                                <option value="5">5 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="3">3 Stars</option>
                            </select>
                            <textarea id="rev_content" class="cms-input" placeholder="Content" style="grid-column: span 2; height:80px; resize:none;"></textarea>
                        </div>
                        <button type="button" class="add-action" onclick="injectReview()">INJECT SIGNAL</button>
                    </div>

                    <span style="font-size:0.6rem; font-weight:900; letter-spacing:1px; color:#999; display:block; margin-bottom:1rem;">VERIFIED ARCHIVE</span>
                    <div class="scroll-v">
                        <div class="review-cluster">
                            <?php foreach($reviews as $r): ?>
                                <div class="review-mini-card" onclick="openReviewModal(<?php echo htmlspecialchars(json_encode($r)); ?>)">
                                    <i class="fas fa-trash delete-btn" onclick="event.stopPropagation(); deleteSignal('<?php echo $r['id']; ?>', this.parentElement)"></i>
                                    <div style="font-weight:900; font-size:0.8rem; color:#111;"><?php echo htmlspecialchars($r['author']); ?></div>
                                    <div style="font-size:0.6rem; color:var(--cms-accent); font-weight:900; margin-bottom:0.5rem; letter-spacing:0.5px;"><?php echo htmlspecialchars($r['position']); ?></div>
                                    <p style="font-size:0.75rem; color:#777; font-style:italic; line-height:1.4; height:2.8em; overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;">"<?php echo htmlspecialchars($r['content']); ?>"</p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- SYNC BAR -->
        <div class="sync-panel">
            <span style="font-size:0.6rem; color:#999; font-weight:800; letter-spacing:1px;">COORDINATE SYNC STATUS: READY</span>
            <form id="cms-master-form">
                <button type="submit" class="btn-deploy">SYNC TO CORE</button>
            </form>
        </div>
    </main>

    <!-- Modal -->
    <div id="review-modal" class="cms-modal" onclick="this.classList.remove('active')">
        <div class="model-inner" onclick="event.stopPropagation()">
            <i class="fas fa-times" style="position:absolute; top:2rem; right:2rem; cursor:pointer;" onclick="document.getElementById('review-modal').classList.remove('active')"></i>
            <h2 id="modal-author" style="font-family:var(--font-heading); font-weight:900; margin-bottom:0.5rem;"></h2>
            <div id="modal-pos" style="color:var(--cms-accent); font-weight:900; font-size:0.8rem; text-transform:uppercase; margin-bottom:2rem;"></div>
            <p id="modal-content" style="font-size:1.1rem; line-height:1.8; color:#444; font-style:italic; background:#F9F9F9; padding:2rem; border-radius:20px;"></p>
        </div>
    </div>

    <script>
        function switchPackageTab(pkg, el) {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            el.classList.add('active');
            document.getElementById(pkg + '-content').classList.add('active');
        }

        function addFeature(listId, name) {
            const list = document.getElementById(listId);
            const row = document.createElement('div');
            row.className = 'feature-item';
            row.innerHTML = `<input type="text" name="${name}" form="cms-master-form" class="cms-input" placeholder="Enter feature..."><div class="remove-icon" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></div>`;
            list.appendChild(row);
            row.querySelector('input').focus();
        }

        function injectReview() {
            const author = document.getElementById('rev_author').value;
            const pos = document.getElementById('rev_pos').value;
            const content = document.getElementById('rev_content').value;
            const rating = document.getElementById('rev_rating').value;
            if(!author || !content) return alert('Missing parameters.');
            const fd = new FormData();
            fd.append('author', author); fd.append('position', pos); fd.append('content', content); fd.append('rating', rating); fd.append('status', 'featured');
            fetch('controller/testimonials/submit.php', { method: 'POST', body: fd }).then(() => location.reload());
        }

        function deleteSignal(id, el) {
            if(confirm('Exterminate signal?')) {
                fetch('controller/testimonials/delete.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${id}`
                }).then(() => el.remove());
            }
        }

        function openReviewModal(data) {
            document.getElementById('modal-author').innerText = data.author;
            document.getElementById('modal-pos').innerText = data.position;
            document.getElementById('modal-content').innerText = `"${data.content}"`;
            document.getElementById('review-modal').classList.add('active');
        }

        document.getElementById('cms-master-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            btn.innerText = 'SYNCING...';
            // Collect from other pane
            const allInputs = document.querySelectorAll('input[form="cms-master-form"]');
            const fd = new FormData(this);
            allInputs.forEach(i => fd.append(i.name, i.value));
            
            fetch('controller/cms/save.php', { method: 'POST', body: fd })
            .then(() => {
                btn.innerText = 'SYNCED'; btn.style.background = '#28a745';
                setTimeout(() => { btn.innerText = 'SYNC TO CORE'; btn.style.background = ''; }, 2000);
            });
        });
    </script>
</body>
</html>