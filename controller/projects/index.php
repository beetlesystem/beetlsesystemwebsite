<?php
require_once '../../core/auth_check.php';
require_once '../../core/db.php';

// Handle CRUD Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create' || $action === 'update') {
        $title = $_POST['title'] ?? '';
        $client = $_POST['client'] ?? '';
        $category = $_POST['category'] ?? '';
        $year = $_POST['year'] ?? date('Y');
        $id = $_POST['id'] ?? null;
        
        $image_url = $_POST['existing_image'] ?? '';

        // Handle File Upload
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../../uploads/projects/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            $file_extension = pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);
            $file_name = uniqid('proj_') . '.' . $file_extension;
            $target_file = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $target_file)) {
                $image_url = 'uploads/projects/' . $file_name;
            }
        }

        if ($action === 'create') {
            $stmt = $pdo->prepare("INSERT INTO projects (title, client, category, year, image_url) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$title, $client, $category, $year, $image_url]);
        } else {
            $stmt = $pdo->prepare("UPDATE projects SET title = ?, client = ?, category = ?, year = ?, image_url = ? WHERE id = ?");
            $stmt->execute([$title, $client, $category, $year, $image_url, $id]);
        }
    } elseif ($action === 'delete') {
        $id = $_POST['id'] ?? null;
        if ($id) {
            $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
            $stmt->execute([$id]);
        }
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$projects = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC")->fetchAll();
$total_projects = count($projects);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <base href="/beetlesystem/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects | Beetle System</title>
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
            <h1 class="admin-page-title">Project Nebula</h1>
            <div style="display:flex; gap:1rem; align-items:center;">
                <button onclick="openModal()" class="submit-btn" style="padding: 0.6rem 1.2rem; font-size: 0.8rem;"><i class="fas fa-plus"></i> NEW REALM</button>
                <div class="admin-user-badge">
                    <i class="fas fa-crown" style="color:var(--accent);"></i>
                    <strong style="margin-left:5px;"><?php echo $total_projects; ?> Realms</strong>
                </div>
            </div>
        </div>

        <div class="admin-card" style="margin-bottom: 2rem;">
            <h3 style="margin-bottom: 1rem; font-family: var(--font-heading);"><i class="fas fa-chart-gantt"></i> Development Pulse</h3>
            <div style="display:flex; justify-content:space-between; margin-bottom: 1rem; font-size: 0.9rem; font-weight:600; color:#444;">
                <span>⚡ Active Ecosystem tracking enabled</span>
                <span>🚀 Syncing with core repositories ...</span>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
            <?php if (empty($projects)): ?>
                <div class="admin-card" style="grid-column: 1/-1; text-align:center; padding: 3rem; opacity:0.5;">
                    <i class="fas fa-box-open fa-3x" style="margin-bottom:1rem;"></i>
                    <p>No projects recorded in the Nebula.</p>
                </div>
            <?php else: ?>
                <?php foreach ($projects as $project): ?>
                    <div class="admin-card project-admin-card" style="margin-bottom: 0; position:relative; overflow:hidden;">
                        <div class="project-admin-thumb" style="width:100%; height:120px; border-radius:8px; overflow:hidden; margin-bottom: 1rem; background:rgba(0,0,0,0.05);">
                            <img src="<?php echo htmlspecialchars($project['image_url'] ?: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&q=80&w=2426'); ?>" 
                                 style="width:100%; height:100%; object-fit:cover; transition:transform 0.5s var(--transition);" 
                                 alt="Thumbnail">
                        </div>
                        <div style="display:flex; justify-content: space-between; align-items:center; margin-bottom: 0.5rem;">
                            <i class="fas fa-bezier-curve" style="font-size:1.1rem; color:#666;"></i>
                            <div style="display:flex; gap:0.5rem;">
                                <button onclick='editProject(<?php echo json_encode($project); ?>)' style="background:none; border:none; cursor:pointer; color:#888;"><i class="fas fa-edit"></i></button>
                                <button onclick="confirmDelete(<?php echo $project['id']; ?>)" style="background:none; border:none; cursor:pointer; color:#ff4444;"><i class="fas fa-trash-alt"></i></button>
                            </div>
                        </div>
                        <h3 style="margin-bottom:0.2rem; font-size:1.1rem;"><?php echo htmlspecialchars($project['title']); ?></h3>
                        <p style="font-size:0.8rem; color:#555; margin-bottom:0.8rem;">Client: <?php echo htmlspecialchars($project['client'] ?? 'Internal'); ?></p>
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <div style="font-size:0.75rem; font-weight:600; color:var(--accent);"><i class="fas fa-tag"></i> <?php echo htmlspecialchars($project['category'] ?? 'General'); ?></div>
                            <div style="background:rgba(0,0,0,0.05); border-radius:40px; padding:0.1rem 0.6rem; font-size:0.7rem; font-weight:700;"><i class="fas fa-calendar"></i> <?php echo $project['year'] ?? date('Y'); ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:1100; backdrop-filter:blur(15px); align-items:center; justify-content:center;">
        <div class="admin-card" style="width:100%; max-width:400px; text-align:center;">
            <i class="fas fa-radiation-alt" style="font-size:3rem; color:#ff4444; margin-bottom:1.5rem;"></i>
            <h3 style="margin-bottom: 1rem; font-family: var(--font-heading);">OBLITERATE REALM?</h3>
            <p style="font-size:0.9rem; opacity:0.7; margin-bottom:2rem;">This action is permanent. The architectural data for this realm will be lost in the void.</p>
            <form id="deleteForm" method="POST">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" id="deleteProjectId">
                <div style="display:flex; gap:1rem;">
                    <button type="button" onclick="closeDeleteModal()" style="flex:1; padding:1rem; background:rgba(0,0,0,0.05); border:none; border-radius:8px; font-weight:700; cursor:pointer;">ABORT</button>
                    <button type="submit" style="flex:1; padding:1rem; background:#ff4444; color:#fff; border:none; border-radius:8px; font-weight:700; cursor:pointer;">CONFIRM</button>
                </div>
            </form>
        </div>
    </div>
    <div id="projectModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:1000; backdrop-filter:blur(10px); align-items:center; justify-content:center;">
        <div class="admin-card" style="width:100%; max-width:500px; margin-bottom:0; background:#fff;">
            <h3 id="modalTitle" style="margin-bottom: 1.5rem; font-family: var(--font-heading);">INITIATE NEW REALM</h3>
            <form id="projectForm" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" id="formAction" value="create">
                <input type="hidden" name="id" id="projectId">
                <input type="hidden" name="existing_image" id="pExistingImage">
                
                <div style="display:flex; flex-direction:column; gap:1rem;">
                    <div>
                        <label style="font-size:0.7rem; font-weight:900; opacity:0.6; display:block; margin-bottom:0.4rem;">REALM TITLE</label>
                        <input type="text" name="title" id="pTitle" required style="width:100%; padding:0.8rem; border:1px solid rgba(0,0,0,0.1); border-radius:8px;">
                    </div>
                    <div>
                        <label style="font-size:0.7rem; font-weight:900; opacity:0.6; display:block; margin-bottom:0.4rem;">CLIENT IDENTITY</label>
                        <input type="text" name="client" id="pClient" style="width:100%; padding:0.8rem; border:1px solid rgba(0,0,0,0.1); border-radius:8px;">
                    </div>
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1rem;">
                        <div>
                            <label style="font-size:0.7rem; font-weight:900; opacity:0.6; display:block; margin-bottom:0.4rem;">SECTOR (CATEGORY)</label>
                            <input type="text" name="category" id="pCategory" style="width:100%; padding:0.8rem; border:1px solid rgba(0,0,0,0.1); border-radius:8px;">
                        </div>
                        <div>
                            <label style="font-size:0.7rem; font-weight:900; opacity:0.6; display:block; margin-bottom:0.4rem;">EPOCH (YEAR)</label>
                            <input type="text" name="year" id="pYear" value="<?php echo date('Y'); ?>" style="width:100%; padding:0.8rem; border:1px solid rgba(0,0,0,0.1); border-radius:8px;">
                        </div>
                    </div>
                    <div>
                        <label style="font-size:0.7rem; font-weight:900; opacity:0.6; display:block; margin-bottom:0.4rem;">ARCHITECTURAL THUMBNAIL</label>
                        <div id="pPreviewContainer" style="display:none; margin-bottom:1rem; border-radius:8px; overflow:hidden; height:100px; border:1px solid rgba(0,0,0,0.1);">
                            <img id="pPreviewImg" src="" style="width:100%; height:100%; object-fit:cover;">
                        </div>
                        <input type="file" name="thumbnail" id="pThumbnail" accept="image/*" style="width:100%; padding:0.8rem; border:1px solid rgba(0,0,0,0.1); border-radius:8px; font-size: 0.8rem;">
                        <p id="imageHint" style="font-size: 0.65rem; color: var(--accent); margin-top: 0.3rem; display:none;">Leave empty to keep existing visual signature.</p>
                    </div>
                </div>

                <div style="display:flex; gap:1rem; margin-top:2rem;">
                    <button type="button" onclick="closeModal()" style="flex:1; padding:1rem; background:rgba(0,0,0,0.05); border:none; border-radius:8px; font-weight:700; cursor:pointer;">ABORT</button>
                    <button type="submit" class="submit-btn" style="flex:1; padding:1rem;">ESTABLISH</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('modalTitle').innerText = 'INITIATE NEW REALM';
            document.getElementById('formAction').value = 'create';
            document.getElementById('projectForm').reset();
            document.getElementById('imageHint').style.display = 'none';
            document.getElementById('pPreviewContainer').style.display = 'none';
            document.getElementById('pExistingImage').value = '';
            document.getElementById('projectModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('projectModal').style.display = 'none';
        }

        function editProject(project) {
            document.getElementById('modalTitle').innerText = 'RECONFIGURE REALM';
            document.getElementById('formAction').value = 'update';
            document.getElementById('projectId').value = project.id;
            document.getElementById('pTitle').value = project.title;
            document.getElementById('pClient').value = project.client;
            document.getElementById('pCategory').value = project.category;
            document.getElementById('pYear').value = project.year;
            document.getElementById('pExistingImage').value = project.image_url;
            
            // Image Preview
            if (project.image_url) {
                document.getElementById('pPreviewImg').src = project.image_url;
                document.getElementById('pPreviewContainer').style.display = 'block';
                document.getElementById('imageHint').style.display = 'block';
            } else {
                document.getElementById('pPreviewContainer').style.display = 'none';
                document.getElementById('imageHint').style.display = 'none';
            }
            
            document.getElementById('projectModal').style.display = 'flex';
        }

        function confirmDelete(id) {
            document.getElementById('deleteProjectId').value = id;
            document.getElementById('deleteModal').style.display = 'flex';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }
    </script>
</body>
</html>
