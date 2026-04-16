<?php 
require_once '../core/db.php';
require_once '../core/track_visitor.php';
trackVisitor($pdo, 'Projects');

$projects = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC")->fetchAll();
include '../includes/pageheader.php'; 
?>

<body class="inner-page">
    <div class="grain"></div>
    <div id="cursor"></div>
    <div id="cursor-follower"></div>

    <?php include '../includes/loader.php'; ?>
    <?php include '../includes/navigation.php'; ?>

    <main>
        <section class="page-hero">
            <div class="container">
                <span class="subheading"><span>PORTFOLIO</span></span>
                <h1 class="reveal-from-left">Visionary work for <br><span class="accent-text">Visionary Brands.</span></h1>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <div class="projects-grid-detailed">
                    <?php if (empty($projects)): ?>
                        <div style="text-align:center; padding: 5rem; width:100%; opacity:0.5;">
                            <h2>The Nebula is currently silent.</h2>
                            <p>Check back soon for new architectural wonders.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($projects as $project): ?>
                            <div class="project-card-full reveal-from-bottom">
                                <div class="project-visual">
                                    <div class="img-reveal-wrapper">
                                        <img src="<?php echo htmlspecialchars($project['image_url'] ?: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&q=80&w=2426'); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>">
                                    </div>
                                </div>
                                <div class="project-details">
                                    <span class="p-category"><?php echo htmlspecialchars($project['category'] ?? 'Design & Development'); ?></span>
                                    <h2><?php echo htmlspecialchars($project['title']); ?></h2>
                                    <p><?php echo htmlspecialchars($project['client'] ?? 'Independent Venture'); ?> · <?php echo htmlspecialchars($project['year'] ?? date('Y')); ?></p>
                                    <a href="#" class="view-project-link">VIEW PROJECT <span>→</span></a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>

    <!-- Multi-Step Inquiry Modal -->
    <div id="contact-modal" class="modal multi-step-modal">
        <div class="modal-content">
            <button class="modal-close" id="contact-modal-close" aria-label="Close modal">&times;</button>
            <div class="modal-progress"><div class="progress-bar" id="form-progress" style="width: 33.33%;"></div></div>
            <form id="multi-step-form">
                <div class="step active" data-step="1">
                    <h2>TELL US ABOUT YOU</h2>
                    <div class="form-group"><label>FULL NAME</label><input type="text" id="ms-name" placeholder="John Doe" required></div>
                    <div class="form-group"><label>COMPANY / ORGANIZATION</label><input type="text" id="ms-company" placeholder="TechCorp Inc."></div>
                    <div class="form-navigation"><button type="button" class="next-btn">CONTINUE</button></div>
                </div>
                <div class="step" data-step="2">
                    <h2>HOW TO REACH YOU</h2>
                    <div class="form-group"><label>WORK EMAIL</label><input type="email" id="ms-email" placeholder="john@techcorp.com" required></div>
                    <div class="form-group"><label>PHONE NUMBER (OPTIONAL)</label><input type="tel" id="ms-phone" placeholder="+1 (555) 000-0000"></div>
                    <div class="form-navigation"><button type="button" class="back-btn">BACK</button><button type="button" class="next-btn">CONTINUE</button></div>
                </div>
                <!-- Step 3: Additional Discovery & Source -->
                <div class="step" data-step="3">
                    <h2>PROJECT DISCOVERY</h2>
                    <div class="form-group"><label>BRIEF PROJECT OVERVIEW</label><textarea id="ms-project" rows="3" placeholder="What are you looking to build?"></textarea></div>
                    <div class="source-header">Where did you hear about us?</div>
                    <div class="main-category-grid" id="main-cat-grid">
                        <div class="main-cat-item" data-category="social">Social Media</div>
                        <div class="main-cat-item" data-category="websites">Web Platforms</div>
                        <div class="main-cat-item" data-category="referrals">Referrals</div>
                        <div class="main-cat-item" data-category="physical">Physical</div>
                        <div class="main-cat-item" data-category="offline">Offline Ads</div>
                        <div class="main-cat-item" data-category="others">Others</div>
                    </div>
                    <div class="sub-options-container">
                        <div class="sub-options-group" data-parent="social">
                            <div class="cat-item" data-value="Instagram"><span class="cat-icon">📸</span> Instagram</div>
                            <div class="cat-item" data-value="LinkedIn"><span class="cat-icon">💼</span> LinkedIn</div>
                            <div class="cat-item" data-value="Facebook"><span class="cat-icon">👥</span> Facebook</div>
                            <div class="cat-item" data-value="Others"><span class="cat-icon">➕</span> Others</div>
                            <div class="custom-detail-field" id="ms-social-custom"><label>SPECIFY PLATFORM</label><input type="text" placeholder="..."></div>
                        </div>
                        <div class="sub-options-group" data-parent="websites">
                            <div class="cat-item" data-value="pwclimate"><span class="cat-icon">🌐</span> pwclimate</div>
                            <div class="cat-item" data-value="dryium"><span class="cat-icon">✨</span> dryium</div>
                            <div class="cat-item" data-value="portfolios"><span class="cat-icon">🎨</span> Portfolios</div>
                            <div class="cat-item" data-value="Others"><span class="cat-icon">➕</span> Others</div>
                            <div class="custom-detail-field" id="ms-web-custom"><label>SPECIFY WEBSITE</label><input type="text" placeholder="..."></div>
                        </div>
                        <div class="sub-options-group" data-parent="referrals">
                            <div class="cat-item" data-value="Client Referral"><span class="cat-icon">🤝</span> Client</div>
                            <div class="cat-item" data-value="Partner Referral"><span class="cat-icon">🏢</span> Partner</div>
                            <div class="cat-item" data-value="Others"><span class="cat-icon">➕</span> Others</div>
                            <div class="custom-detail-field" id="ms-ref-custom"><label>WHO REFERRED YOU?</label><input type="text" placeholder="..."></div>
                        </div>
                        <div class="sub-options-group" data-parent="physical">
                            <div class="cat-item" data-value="Events"><span class="cat-icon">🎪</span> Tech Events</div>
                            <div class="cat-item" data-value="Office Visit"><span class="cat-icon">🏢</span> Office Visit</div>
                            <div class="cat-item" data-value="Others"><span class="cat-icon">➕</span> Others</div>
                            <div class="custom-detail-field" id="ms-physical-custom"><label>SPECIFY INTERACTION</label><input type="text" placeholder="..."></div>
                        </div>
                        <div class="sub-options-group" data-parent="offline">
                            <div class="cat-item" data-value="Billboard"><span class="cat-icon">🖼️</span> Billboard</div>
                            <div class="cat-item" data-value="Print Media"><span class="cat-icon">📰</span> Print Media</div>
                            <div class="cat-item" data-value="Others"><span class="cat-icon">➕</span> Others</div>
                            <div class="custom-detail-field" id="ms-offline-custom"><label>SPECIFY AD SOURCE</label><input type="text" placeholder="..."></div>
                        </div>
                        <div class="sub-options-group" data-parent="others">
                            <div class="custom-detail-field active" style="grid-column: span 2;"><label>PLEASE DESCRIBE</label><input type="text" id="ms-other-detail-final" placeholder="..."></div>
                        </div>
                        <div id="sub-placeholder" style="text-align:center; opacity:0.3; font-size:0.8rem; padding-top:1rem;">Select a category above</div>
                    </div>
                    <input type="hidden" id="ms-source" name="source">
                    <div class="form-navigation">
                        <button type="button" class="back-btn">BACK</button>
                        <button type="submit" class="submit-btn group">FINALIZE</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/lenis@latest/dist/lenis.min.js"></script>
    <script src="core/main.js"></script>
</body>

</html>
