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
        $video_url = $_POST['existing_video'] ?? '';
        $project_url = $_POST['project_url'] ?? '';

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

        // Handle Video Upload
        if (isset($_FILES['video_file']) && $_FILES['video_file']['name'] !== '') {
            if ($_FILES['video_file']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = '../../uploads/projects/videos/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                
                $file_extension = strtolower(pathinfo($_FILES['video_file']['name'], PATHINFO_EXTENSION));
                $allowed_extensions = ['mp4', 'webm', 'mov'];
                
                if (in_array($file_extension, $allowed_extensions)) {
                    $file_name = uniqid('vid_') . '.' . $file_extension;
                    $target_file = $upload_dir . $file_name;

                    if (move_uploaded_file($_FILES['video_file']['tmp_name'], $target_file)) {
                        $video_url = 'uploads/projects/videos/' . $file_name;
                    } else {
                        $error_msg = "Failed to move uploaded video file.";
                    }
                } else {
                    $error_msg = "Invalid video format. Allowed: " . implode(', ', $allowed_extensions);
                }
            } else {
                // Specific error messages for the user
                switch ($_FILES['video_file']['error']) {
                    case UPLOAD_ERR_INI_SIZE:
                        $error_msg = "The video file is too large (exceeds PHP limits).";
                        break;
                    case UPLOAD_ERR_FORM_SIZE:
                        $error_msg = "The video file is too large (exceeds form limit).";
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        $error_msg = "The video was only partially uploaded.";
                        break;
                    default:
                        $error_msg = "An error occurred during video upload. Code: " . $_FILES['video_file']['error'];
                }
            }
        }

        $is_featured = isset($_POST['is_featured']) ? 1 : 0;

        if ($action === 'create') {
            $stmt = $pdo->prepare("INSERT INTO projects (title, client, category, year, image_url, video_url, project_url, is_featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $client, $category, $year, $image_url, $video_url, $project_url, $is_featured]);
        } else {
            $stmt = $pdo->prepare("UPDATE projects SET title = ?, client = ?, category = ?, year = ?, image_url = ?, video_url = ?, project_url = ?, is_featured = ? WHERE id = ?");
            $stmt->execute([$title, $client, $category, $year, $image_url, $video_url, $project_url, $is_featured, $id]);
        }
        if (isset($error_msg)) {
            $_SESSION['error'] = $error_msg;
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

    <script src="core/main.js"></script>
</head>
<body class="admin-layout">
    
    <!-- Sidebar -->
    <?php include '../../includes/aside.php'; ?>

    <!-- Main Content -->
    <main class="admin-main">
        <div class="admin-header">
            <h1 class="admin-page-title">Projects</h1>
            <div style="display:flex; flex-wrap:wrap; gap:1rem; align-items:center;">
                <button onclick="openModal()" class="submit-btn" style="padding: 0.6rem 1.2rem; font-size: 0.8rem;"><i class="fas fa-plus"></i> ADD PROJECT</button>
                <div class="admin-user-badge" style="padding: 0.6rem 1.2rem; font-size: 1rem;">
                    <i class="fas fa-crown" style="color:var(--accent);"></i>
                    <strong style="margin-left:5px;"><?php echo $total_projects; ?> Total</strong>
                </div>
            </div>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="admin-card" style="background: rgba(255, 68, 68, 0.1); border: 1px solid #ff4444; color: #ff4444; margin-bottom: 2rem; padding: 1rem; border-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
                <span><i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?></span>
                <i class="fas fa-times" style="cursor:pointer;" onclick="this.parentElement.style.display='none'"></i>
            </div>
        <?php endif; ?>

        <div class="admin-card" style="margin-bottom: 2rem;">
            <h3 style="margin-bottom: 1rem; font-family: var(--font-heading);"><i class="fas fa-chart-gantt"></i> Project List</h3>
            <div style="display:flex; justify-content:space-between; margin-bottom: 1rem; font-size: 0.9rem; font-weight:600; color:#444;">
                <span>Status: Connected</span>
                <span>Website: Live</span>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
            <?php if (empty($projects)): ?>
                <div class="admin-card" style="grid-column: 1/-1; text-align:center; padding: 3rem; opacity:0.5;">
                    <i class="fas fa-box-open fa-3x" style="margin-bottom:1rem;"></i>
                    <p>No projects found.</p>
                </div>
            <?php else: ?>
                <?php foreach ($projects as $project): ?>
                    <div class="admin-card project-admin-card" style="margin-bottom: 0; position:relative; overflow:hidden;">
                        <div class="project-admin-thumb" style="width:100%; height:120px; border-radius:8px; overflow:hidden; margin-bottom: 1rem; background:rgba(0,0,0,0.05); position:relative;">
                            <img src="<?php echo htmlspecialchars($project['image_url'] ?: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&q=80&w=2426'); ?>" 
                                 style="width:100%; height:100%; object-fit:cover; transition:transform 0.5s var(--transition);" 
                                 alt="Thumbnail">
                            <?php if ($project['is_featured']): ?>
                                <div style="position:absolute; top:8px; left:8px; background:var(--accent); color:#fff; font-size:0.6rem; font-weight:900; padding:2px 8px; border-radius:100px; box-shadow:0 4px 10px rgba(0,0,0,0.1);"><i class="fas fa-star" style="font-size:0.5rem; margin-right:3px;"></i> FEATURED</div>
                            <?php endif; ?>
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
    <div id="deleteModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:1100; backdrop-filter:blur(15px); align-items:center; justify-content:center; padding:1rem; overflow-y:auto;">
        <div class="admin-card" style="width:100%; max-width:400px; text-align:center; margin-top:auto; margin-bottom:auto;">
            <i class="fas fa-exclamation-triangle" style="font-size:3rem; color:#ff4444; margin-bottom:1.5rem;"></i>
            <h3 style="margin-bottom: 1rem; font-family: var(--font-heading);">DELETE PROJECT?</h3>
            <p style="font-size:0.9rem; opacity:0.7; margin-bottom:2rem;">This action cannot be undone. All project data will be removed.</p>
            <form id="deleteForm" method="POST">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" id="deleteProjectId">
                <div style="display:flex; gap:1rem;">
                    <button type="button" onclick="closeDeleteModal()" style="flex:1; padding:1rem; background:rgba(0,0,0,0.05); border:none; border-radius:8px; font-weight:700; cursor:pointer;">CANCEL</button>
                    <button type="submit" style="flex:1; padding:1rem; background:#ff4444; color:#fff; border:none; border-radius:8px; font-weight:700; cursor:pointer;">DELETE</button>
                </div>
            </form>
        </div>
    </div>
    <div id="projectModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:1000; backdrop-filter:blur(10px); align-items:flex-start; justify-content:center; padding:2rem 1rem; overflow-y:auto;">
        <div class="admin-card project-modal-card" style="width:100%; max-width:600px; background:#fff; margin: auto 0;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 2rem;">
                <h3 id="modalTitle" style="margin: 0; font-family: var(--font-heading);">ADD NEW PROJECT</h3>
                <button type="button" onclick="closeModal()" style="background:none; border:none; font-size:1.5rem; cursor:pointer; opacity:0.5;">&times;</button>
            </div>
            
            <form id="projectForm" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" id="formAction" value="create">
                <input type="hidden" name="id" id="projectId">
                <input type="hidden" name="existing_image" id="pExistingImage">
                <input type="hidden" name="existing_video" id="pExistingVideo">
                
                <div style="display:flex; flex-direction:column; gap:1.5rem;">
                    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap:1.5rem;">
                        <div>
                            <label style="font-size:0.7rem; font-weight:900; opacity:0.6; display:block; margin-bottom:0.5rem;">PROJECT TITLE</label>
                            <input type="text" name="title" id="pTitle" required style="width:100%; padding:0.9rem; border:1px solid rgba(0,0,0,0.1); border-radius:8px; font-family:inherit;">
                        </div>
                        <div>
                            <label style="font-size:0.7rem; font-weight:900; opacity:0.6; display:block; margin-bottom:0.5rem;">CLIENT NAME</label>
                            <input type="text" name="client" id="pClient" style="width:100%; padding:0.9rem; border:1px solid rgba(0,0,0,0.1); border-radius:8px; font-family:inherit;">
                        </div>
                    </div>

                    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:1.5rem;">
                        <div>
                            <label style="font-size:0.7rem; font-weight:900; opacity:0.6; display:block; margin-bottom:0.5rem;">CATEGORY</label>
                            <input type="text" name="category" id="pCategory" style="width:100%; padding:0.9rem; border:1px solid rgba(0,0,0,0.1); border-radius:8px; font-family:inherit;">
                        </div>
                        <div>
                            <label style="font-size:0.7rem; font-weight:900; opacity:0.6; display:block; margin-bottom:0.5rem;">PROJECT LINK</label>
                            <input type="url" name="project_url" id="pProjectUrl" placeholder="https://..." style="width:100%; padding:0.9rem; border:1px solid rgba(0,0,0,0.1); border-radius:8px; font-family:inherit;">
                        </div>
                        <div>
                            <label style="font-size:0.7rem; font-weight:900; opacity:0.6; display:block; margin-bottom:0.5rem;">YEAR</label>
                            <input type="text" name="year" id="pYear" value="<?php echo date('Y'); ?>" style="width:100%; padding:0.9rem; border:1px solid rgba(0,0,0,0.1); border-radius:8px; font-family:inherit;">
                        </div>
                    </div>

                    <div style="display:flex; align-items:center; gap:0.8rem; background:rgba(0,0,0,0.03); padding:1rem; border-radius:8px; border:1px dashed rgba(0,0,0,0.1);">
                        <input type="checkbox" name="is_featured" id="pIsFeatured" style="width:20px; height:20px; accent-color:var(--accent); cursor:pointer;">
                        <label for="pIsFeatured" style="font-size:0.8rem; font-weight:700; cursor:pointer;">SHOW ON HOMEPAGE</label>
                    </div>

                    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap:1.5rem;">
                        <div>
                            <label style="font-size:0.7rem; font-weight:900; opacity:0.6; display:block; margin-bottom:0.5rem;">PROJECT VIDEO (MP4)</label>
                            <div id="pVideoPreviewContainer" style="display:none; margin-bottom:1rem; border-radius:8px; overflow:hidden; aspect-ratio:16/9; background:#000; border:1px solid rgba(0,0,0,0.1);">
                                <video id="pVideoPreviewVid" controls style="width:100%; height:100%; object-fit:contain;"></video>
                            </div>
                            <input type="file" name="video_file" id="pVideoFile" accept="video/mp4,video/webm" style="width:100%; font-size: 0.75rem;">
                            <p id="videoHint" style="font-size: 0.65rem; color: var(--accent); margin-top: 0.5rem; display:none;">Existing video detected. Upload new to replace.</p>
                        </div>
                        <div>
                            <label style="font-size:0.7rem; font-weight:900; opacity:0.6; display:block; margin-bottom:0.5rem;">PROJECT IMAGE</label>
                            <div id="pPreviewContainer" style="display:none; margin-bottom:1rem; border-radius:8px; overflow:hidden; aspect-ratio:16/9; border:1px solid rgba(0,0,0,0.1);">
                                <img id="pPreviewImg" src="" style="width:100%; height:100%; object-fit:cover; background:#f5f5f5;">
                            </div>
                            <input type="file" name="thumbnail" id="pThumbnail" accept="image/*" style="width:100%; font-size: 0.75rem;">
                            <p id="imageHint" style="font-size: 0.65rem; color: var(--accent); margin-top: 0.5rem; display:none;">Leave empty to keep existing image.</p>
                        </div>
                    </div>
                </div>

                <div style="display:flex; gap:1rem; margin-top:3rem; flex-wrap:wrap;">
                    <button type="button" onclick="closeModal()" style="flex:1; min-width:140px; padding:1.1rem; background:rgba(0,0,0,0.05); border:none; border-radius:8px; font-weight:700; cursor:pointer; font-size:0.8rem;">CANCEL</button>
                    <button type="submit" class="submit-btn" style="flex:2; min-width:140px; padding:1.1rem; font-size:0.8rem;">ADD PROJECT</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('modalTitle').innerText = 'ADD NEW PROJECT';
            document.getElementById('formAction').value = 'create';
            document.getElementById('projectForm').reset();
            document.getElementById('imageHint').style.display = 'none';
            document.getElementById('videoHint').style.display = 'none';
            document.getElementById('pPreviewContainer').style.display = 'none';
            document.getElementById('pVideoPreviewContainer').style.display = 'none';
            document.getElementById('pVideoPreviewVid').src = '';
            document.getElementById('pExistingImage').value = '';
            document.getElementById('pExistingVideo').value = '';
            document.getElementById('pProjectUrl').value = '';
            document.getElementById('pIsFeatured').checked = false;
            document.getElementById('projectModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('projectModal').style.display = 'none';
        }

        function editProject(project) {
            document.getElementById('modalTitle').innerText = 'EDIT PROJECT';
            document.getElementById('formAction').value = 'update';
            document.getElementById('projectId').value = project.id;
            document.getElementById('pTitle').value = project.title;
            document.getElementById('pClient').value = project.client;
            document.getElementById('pCategory').value = project.category;
            document.getElementById('pYear').value = project.year;
            document.getElementById('pProjectUrl').value = project.project_url || '';
            document.getElementById('pExistingImage').value = project.image_url;
            document.getElementById('pExistingVideo').value = project.video_url || '';
            document.getElementById('pIsFeatured').checked = project.is_featured == 1;
            
            if (project.video_url) {
                document.getElementById('videoHint').style.display = 'block';
                document.getElementById('pVideoPreviewVid').src = project.video_url;
                document.getElementById('pVideoPreviewContainer').style.display = 'block';
            } else {
                document.getElementById('videoHint').style.display = 'none';
                document.getElementById('pVideoPreviewVid').src = '';
                document.getElementById('pVideoPreviewContainer').style.display = 'none';
            }
            
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

        // Live Preview Logic
        document.getElementById('pThumbnail').onchange = function(evt) {
            const [file] = this.files;
            if (file) {
                document.getElementById('pPreviewImg').src = URL.createObjectURL(file);
                document.getElementById('pPreviewContainer').style.display = 'block';
            }
        };

        document.getElementById('pVideoFile').onchange = function(evt) {
            const [file] = this.files;
            if (file) {
                document.getElementById('pVideoPreviewVid').src = URL.createObjectURL(file);
                document.getElementById('pVideoPreviewContainer').style.display = 'block';
            }
        };
    </script>
</body>
</html>
