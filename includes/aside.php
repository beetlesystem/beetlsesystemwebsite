<?php
$current_file = $_SERVER['PHP_SELF'];
function isActiveNav($path) {
    global $current_file;
    return strpos($current_file, $path) !== false ? 'active' : '';
}
?>

<!-- Mobile Admin Header -->
<div class="mobile-admin-bar">
    <div class="mobile-admin-logo">BEETLE</div>
    <button id="admin-sidebar-toggler" class="admin-toggler">
        <i class="fas fa-bars"></i>
    </button>
</div>

<aside class="admin-sidebar" id="admin-sidebar">
    <div class="sidebar-header">
        <div class="admin-logo">BEETLE ADMIN</div>
        <button id="admin-sidebar-close" class="admin-close-btn"><i class="fas fa-times"></i></button>
    </div>
    <nav>
        <a href="dashboard" class="admin-nav-item <?php echo isActiveNav('dashboard'); ?>"><i class="fas fa-home"></i> Dashboard</a>
        <a href="admin/projects" class="admin-nav-item <?php echo isActiveNav('projects'); ?>"><i class="fas fa-cubes"></i> Projects</a>
        <a href="admin/contacts" class="admin-nav-item <?php echo isActiveNav('contacts'); ?>"><i class="fas fa-paper-plane"></i> Messages</a>
        <a href="admin/testimonials" class="admin-nav-item <?php echo isActiveNav('testimonials'); ?>"><i class="fas fa-gem"></i> Reviews</a>
        <a href="admin/visitors" class="admin-nav-item <?php echo isActiveNav('visitors'); ?>"><i class="fas fa-chart-line"></i> Visitors</a>
        <a href="admin/clients" class="admin-nav-item <?php echo isActiveNav('clients'); ?>"><i class="fas fa-handshake"></i> Clients</a>
        <a href="admin/cms" class="admin-nav-item <?php echo isActiveNav('cms'); ?>"><i class="fas fa-sliders-h"></i> Edit Website</a>
    </nav>
    <div style="margin-top: auto;">
        <a href="./" target="_blank" class="admin-nav-item" style="border-bottom: 1px solid rgba(0,0,0,0.05); padding-bottom: 1rem; margin-bottom: 1rem;"><i class="fas fa-external-link-alt"></i> Live Website</a>
        <a href="controller/logout.php" class="admin-nav-item"><i class="fas fa-arrow-right-from-bracket"></i> Logout</a>
    </div>
</aside>

<div class="sidebar-overlay" id="sidebar-overlay"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('admin-sidebar');
    const toggler = document.getElementById('admin-sidebar-toggler');
    const closer = document.getElementById('admin-sidebar-close');
    const overlay = document.getElementById('sidebar-overlay');

    if (toggler) {
        toggler.addEventListener('click', () => {
            sidebar.classList.add('mobile-active');
            overlay.classList.add('active');
        });
    }

    if (closer) {
        closer.addEventListener('click', () => {
            sidebar.classList.remove('mobile-active');
            overlay.classList.remove('active');
        });
    }

    if (overlay) {
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('mobile-active');
            overlay.classList.remove('active');
        });
    }
});
</script>