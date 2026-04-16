<?php
require_once '../../core/auth_check.php';
require_once '../../core/db.php';

// Fetch stats
$active_projects = $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();
$incoming_leads = $pdo->query("SELECT COUNT(*) FROM contacts WHERE status = 'new'")->fetchColumn();
$total_visitors = $pdo->query("SELECT COUNT(*) FROM visitors")->fetchColumn();

// Fetch recent reviews
$recent_reviews = $pdo->query("SELECT * FROM testimonials ORDER BY created_at DESC LIMIT 3")->fetchAll();

// Fetch recent inquiries
$recent_inquiries = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC LIMIT 3")->fetchAll();

// Fetch chart data (last 7 days)
$chart_data = $pdo->query("
    SELECT DATE(visited_at) as visit_date, COUNT(*) as count 
    FROM visitors 
    WHERE visited_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY DATE(visited_at)
    ORDER BY visit_date ASC
")->fetchAll();

// Calculate satisfaction index
$satisfaction_index = $pdo->query("SELECT AVG(rating) FROM testimonials")->fetchColumn();
$satisfaction_index = $satisfaction_index ? number_format($satisfaction_index, 1) : '5.0';

$labels = [];
$counts = [];
foreach ($chart_data as $row) {
    $labels[] = date('D', strtotime($row['visit_date']));
    $counts[] = $row['count'];
}

// Ensure at least some data for the chart if empty
if (empty($labels)) {
    $labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    $counts = [0, 0, 0, 0, 0, 0, 0];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <base href="/beetlesystem/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Beetle System</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Outfit:wght@400;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Centralized CSS -->
    <link rel="stylesheet" href="core/style.css">
    <link rel="icon" type="image/svg+xml" href="core/favicon.svg">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

    <!-- Chart JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>

<body class="admin-layout">

    <!-- Sidebar -->
    <?php include '../../includes/aside.php'; ?>

    <!-- Main Content -->
    <main class="admin-main">
        <div class="admin-header">
            <h1 class="admin-page-title">Dashboard Overview</h1>
            <div class="admin-user-badge">
                <div class="admin-user-avatar"><?php echo substr($_SESSION['full_name'], 0, 1); ?></div>
                <div>
                    <strong style="display:block; font-size:0.9rem;"><?php echo $_SESSION['full_name']; ?></strong>
                    <span style="font-size:0.75rem; color:#666;">Controller
                        (<?php echo $_SESSION['admin_id']; ?>)</span>
                </div>
            </div>
        </div>

        <div class="admin-stats-grid">
            <div class="admin-stat-card">
                <i class="fas fa-diagram-project" style="color:var(--accent);"></i>
                <div class="admin-stat-value"><?php echo $active_projects; ?></div>
                <div style="color:#666; font-size:0.85rem; font-weight:600;">Active Projects</div>
            </div>
            <div class="admin-stat-card">
                <i class="fas fa-message" style="color:var(--accent);"></i>
                <div class="admin-stat-value"><?php echo $pdo->query("SELECT COUNT(*) FROM contacts")->fetchColumn(); ?></div>
                <div style="color:#666; font-size:0.85rem; font-weight:600;">Total Transmissions</div>
            </div>
            <div class="admin-stat-card">
                <i class="fas fa-users" style="color:var(--accent);"></i>
                <div class="admin-stat-value"><?php echo number_format($total_visitors); ?></div>
                <div style="color:#666; font-size:0.85rem; font-weight:600;">Total Website Visitors</div>
            </div>
            <div class="admin-stat-card">
                <i class="fas fa-star" style="color:var(--accent);"></i>
                <div class="admin-stat-value"><?php echo $satisfaction_index; ?></div>
                <div style="color:#666; font-size:0.85rem; font-weight:600;">Satisfaction Index</div>
            </div>
        </div>

        <!-- Analytics -->
        <div class="admin-card">
            <h3 style="margin-bottom: 1.5rem; font-family: var(--font-heading);">Traffic Analytics</h3>
            <div style="height: 250px; width: 100%;">
                <canvas id="dashboardChart"></canvas>
            </div>
        </div>

        <div class="admin-grid-2">
            <!-- Testimonials -->
            <div class="admin-card">
                <h3 style="margin-bottom: 1rem; font-family: var(--font-heading);">Ethereal Reviews</h3>
                <div class="minimal-list">
                    <?php if (empty($recent_reviews)): ?>
                        <p style="font-size: 0.8rem; opacity: 0.5;">No reviews yet.</p>
                    <?php else: ?>
                        <?php foreach ($recent_reviews as $review): ?>
                            <div class="minimal-item">
                                <strong class="new-indicator"><?php echo htmlspecialchars($review['author']); ?></strong>
                                <span
                                    style="color:#666; font-size:0.85rem;"><?php echo date('M d, Y', strtotime($review['created_at'])); ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Inquiries -->
            <div class="admin-card">
                <h3 style="margin-bottom: 1rem; font-family: var(--font-heading);">Incoming Signals</h3>
                <div class="minimal-list">
                    <?php if (empty($recent_inquiries)): ?>
                        <p style="font-size: 0.8rem; opacity: 0.5;">No inquiries yet.</p>
                    <?php else: ?>
                        <?php foreach ($recent_inquiries as $inquiry): ?>
                            <div class="minimal-item" onclick="viewInquiry(<?php echo $inquiry['id']; ?>)" style="cursor:pointer; transition: background 0.3s; border-radius:8px; padding: 0.8rem;">
                                <div style="display:flex; flex-direction:column;">
                                    <strong class="<?php echo $inquiry['status'] === 'new' ? 'new-indicator' : ''; ?>" style="<?php echo $inquiry['status'] === 'new' ? 'color:var(--accent); font-weight:800;' : ''; ?>">
                                        <?php echo htmlspecialchars($inquiry['name']); ?>
                                    </strong>
                                    <span style="font-size:0.7rem; opacity:0.6;"><?php echo htmlspecialchars($inquiry['subject'] ?? 'Signal'); ?></span>
                                </div>
                                <span style="color:#666; font-size:0.75rem;"><?php echo date('M d', strtotime($inquiry['created_at'])); ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/lenis@latest/dist/lenis.min.js"></script>
    <script src="core/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('dashboardChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: <?php echo json_encode($labels); ?>,
                        datasets: [{
                            label: 'Website Traffic',
                            data: <?php echo json_encode($counts); ?>,
                            borderColor: '#000000',
                            backgroundColor: 'rgba(0, 0, 0, 0.05)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#000000',
                            pointRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(0,0,0,0.05)' }
                            },
                            x: {
                                grid: { display: false }
                            }
                        }
                    }
                });
            }
        });

        function viewInquiry(id) {
            console.log('Redirecting to inquiry trace:', id);
            window.location.href = '/beetlesystem/controller/contacts/index.php?view_id=' + id;
        }
    </script>
</body>

</html>