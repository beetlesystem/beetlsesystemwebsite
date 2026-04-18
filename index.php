<?php 
require_once 'core/db.php';
require_once 'core/track_visitor.php';
require_once 'core/csrf.php';
trackVisitor($pdo, 'Home');

$home_projects = $pdo->query("SELECT * FROM projects ORDER BY is_featured DESC, created_at DESC LIMIT 3")->fetchAll();
$home_testimonials = $pdo->query("SELECT * FROM testimonials WHERE status = 'featured' ORDER BY created_at DESC LIMIT 6")->fetchAll();

// Fetch CMS Settings
$settings = $pdo->query("SELECT setting_key, setting_value FROM site_settings")->fetchAll(PDO::FETCH_KEY_PAIR);
$about_image = $settings['about_image'] ?? 'https://images.unsplash.com/photo-1497215728101-856f4ea42174?auto=format&fit=crop&q=80&w=1200';
$about_video = $settings['about_video'] ?? '';
$pk_starter = json_decode($settings['package_starter'] ?? '[]', true) ?: ["Custom UI/UX Design","7 days Maintenance Support","Secure Hosting (HTTPS Enabled)","SEO-Friendly Structure"];
$pk_premium = json_decode($settings['package_premium'] ?? '[]', true) ?: ["Dashboard-CMS/Admin Panel","Advanced SEO","Interactive Features","15 Days Maintenance Support"];
$pk_enterprise = json_decode($settings['package_enterprise'] ?? '[]', true) ?: ["E-commerce Platforms","Custom CRM & Business Systems","Billing & Invoicing Automation","Scalable Web Applications"];

$pk_starter = array_unique($pk_starter);
$pk_premium = array_unique($pk_premium);
$pk_enterprise = array_unique($pk_enterprise);

include 'includes/pageheader.php'; 
?>

<body>
    <div class="grain"></div>


   <?php include 'includes/loader.php'; ?>
   <?php include 'includes/navigation.php'; ?>


    <main>
        <!-- Hero Section with Split-Screen Animation -->
        <section class="hero-wrapper" id="hero">
            <div class="hero-sticky">
                <!-- Content background -->
                <div class="hero-bg-content">
                    <div class="hero-cycle-wrapper" id="cycle-wrapper">
                        <div class="cycle-track" id="cycle-track">
                            <h1 class="reveal-h1 cycle-phrase">CREATIVE<br><span style="color: var(--accent);">AGENCY</span></h1>
                            <h1 class="reveal-h1 cycle-phrase">WEB<br><span style="color: var(--accent);">EXPERTS</span></h1>
                            <h1 class="reveal-h1 cycle-phrase">SOFTWARE<br><span style="color: var(--accent);">SOLUTIONS</span></h1>
                            <h1 class="reveal-h1 cycle-phrase">YOUR<br><span style="color: var(--accent);">PARTNER</span></h1>
                        </div>
                    </div>
                </div>

                <!-- Curtain Masks -->
                <div class="curtain-top" id="curtain-top">
                    <div class="curtain-content">
                        <div class="split-text">BEETLE SYSTEM</div>
                    </div>
                </div>
                <div class="curtain-bottom" id="curtain-bottom">
                    <div class="curtain-content">
                        <div class="split-text">BEETLE SYSTEM</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="section about-section">
            <div class="container">
                <span class="subheading"><span>01 / WHO WE ARE</span></span>
                <div class="about-grid">
                    <div class="about-text reveal-from-left">
                        <h2><span>Architecting the next generation of digital identity.</span></h2>
                        <p>Beetle System is a high-performance digital forge dedicated to engineering the future of web experiences. We don't just build websites; we craft digital ecosystems that combine technical precision with radical, avant-garde aesthetics to dominate the digital landscape.</p>
                        <div class="about-stats">
                            <div class="stat-item">
                                <span class="stat-num">99%</span>
                                <span class="stat-label">Performance Score</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-num">50+</span>
                                <span class="stat-label">Projects Launched</span>
                            </div>
                        </div>
                    </div>
                    <div class="about-image reveal-from-right">
                        <div class="img-reveal-wrapper">
                            <img src="<?php echo htmlspecialchars($about_image); ?>" alt="Office Space">
                            <?php if (!empty($about_video)): ?>
                                <div class="video-overlay">
                                    <video autoplay muted loop playsinline preload="auto" class="hover-video">
                                        <source src="<?php echo htmlspecialchars($about_video); ?>" type="video/mp4">
                                    </video>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section id="services" class="section services-section">
            <div class="container">
                <span class="subheading"><span>02 / WHAT WE DO</span></span>
                <div class="services-grid">
                    <div class="service-card">
                        <span class="service-num">01</span>
                        <h3>Web Design</h3>
                        <p>High-fidelity designs that capture attention and provide seamless user journeys.</p>
                    </div>
                    <div class="service-card">
                        <span class="service-num">02</span>
                        <h3>Development</h3>
                        <p>Robust, scalable, and high-performance applications built with modern tools.</p>
                    </div>
                    <div class="service-card">
                        <span class="service-num">03</span>
                        <h3>Digital Strategy</h3>
                        <p>Data-driven approaches to help your brand grow and succeed in the digital landscape.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Process Section -->
        <section class="section process-section">
            <div class="container">
                <span class="subheading"><span>03 / OUR PROCESS</span></span>
                <div class="process-grid">
                    <div class="process-step">
                        <div class="step-num">01</div>
                        <h4>Discovery</h4>
                        <p>We dive deep into your brand, objectives, and audience to build a solid strategy.</p>
                    </div>
                    <div class="process-step">
                        <div class="step-num">02</div>
                        <h4>Design</h4>
                        <p>Crafting visual identities and high-fidelity interfaces that wow your users.</p>
                    </div>
                    <div class="process-step">
                        <div class="step-num">03</div>
                        <h4>Development</h4>
                        <p>Translating designs into clean, performant, and scalable code.</p>
                    </div>
                    <div class="process-step">
                        <div class="step-num">04</div>
                        <h4>Launch</h4>
                        <p>Deploying your project to the world with rigorous testing and optimization.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Team Section -->
        <section class="section team-section">
            <div class="container">
                <span class="subheading"><span>04 / THE TEAM</span></span>
                <div class="team-grid">
                    <div class="team-card reveal-from-left">
                        <div class="team-monogram reveal-scale-in delay-2">
                            <span>AR</span>
                        </div>
                        <div class="team-info">
                            <h3 class="line-grow">Ateeb Ramzan</h3>
                            <span class="team-role">Co-Founder & Developer</span>
                            <p>Creative developer with a deep focus on UI/UX and frontend engineering. Brings designs to
                                life with precision animations and modern web standards.</p>
                        </div>
                    </div>
                    <div class="team-card reveal-from-right delay-2">
                        <div class="team-monogram reveal-scale-in delay-3">
                            <span>IM</span>
                        </div>
                        <div class="team-info">
                            <h3 class="line-grow">Irfan Manzoor</h3>
                            <span class="team-role">Co-Founder & Developer</span>
                            <p>Full-stack developer passionate about building high-performance digital products. Focused
                                on clean code, smart architecture, and pixel-perfect interfaces.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Projects Section -->
        <section id="projects" class="section projects-section">
            <div class="container">
                <div class="section-header">
                    <span class="subheading"><span>05 / SELECTED WORKS</span></span>
                    <a href="projects" class="btn-text-arrow">
                        EXPLORE ARCHIVE
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </a>
                </div>
                <div class="projects-list">
                    <?php if (empty($home_projects)): ?>
                        <div style="opacity:0.5; padding: 2rem 0;">No realms active in the Nebula.</div>
                    <?php else: ?>
                        <?php foreach ($home_projects as $hp): ?>
                            <div class="project-item">
                                <div class="project-img">
                                    <img src="<?php echo htmlspecialchars($hp['image_url'] ?: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&q=80&w=2426'); ?>"
                                        alt="<?php echo htmlspecialchars($hp['title']); ?>">
                                    <?php if (!empty($hp['video_url'])): ?>
                                        <div class="video-overlay">
                                            <video autoplay muted loop playsinline preload="auto" class="hover-video">
                                                <source src="<?php echo htmlspecialchars($hp['video_url']); ?>" type="video/mp4">
                                            </video>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="project-info">
                                    <h3><?php echo htmlspecialchars($hp['title']); ?></h3>
                                    <span><?php echo htmlspecialchars($hp['category'] ?? 'Design & Development'); ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Pricing Section -->
        <section class="section pricing-section">
            <div class="container">
                <span class="subheading"><span>06 / PACKAGES</span></span>
                <div class="pricing-grid">
                    <div class="price-card">
                        <h3>Starter</h3>
                        <div class="price">₹14,999<span>/project</span></div>
                        <ul class="price-features">
                            <?php foreach($pk_starter as $feature): ?>
                                <li><span class="tick">✓</span> <?php echo htmlspecialchars($feature); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="price-actions">
                            <a href="contact" class="btn-outline">Get Started</a>
                            <button class="btn-text-only view-details" data-package="starter">VIEW PLAN</button>
                        </div>
                    </div>
                    <div class="price-card featured">
                        <div class="badge">Popular</div>
                        <h3>Premium</h3>
                        <div class="price">₹29,999<span>/project</span></div>
                        <ul class="price-features">
                            <?php foreach($pk_premium as $feature): ?>
                                <li><span class="tick">✓</span> <?php echo htmlspecialchars($feature); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="price-actions">
                            <a href="contact" class="btn-solid">Get Started</a>
                            <button class="btn-text-only view-details" data-package="premium">VIEW PLAN</button>
                        </div>
                    </div>
                    <div class="price-card">
                        <h3>Enterprise</h3>
                        <div class="price">Custom<span>/quote</span></div>
                        <ul class="price-features">
                            <?php foreach($pk_enterprise as $feature): ?>
                                <li><span class="tick">✓</span> <?php echo htmlspecialchars($feature); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="price-actions">
                            <a href="contact" class="btn-outline">Contact Us</a>
                            <button class="btn-text-only view-details" data-package="enterprise">VIEW PLAN</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonial Section -->
        <section class="section testimonial-section">
            <div class="container">
                <span class="subheading"><span>07 / REVIEWS</span></span>

                <div class="testimonial-slider">
                    <!-- DEBUG: Testimonials Count: <?php echo count($home_testimonials); ?> -->
                    <div class="testimonial-track" id="testimonial-track">
                        <?php if (empty($home_testimonials)): ?>
                            <div class="testimonial-slide">
                                <p class="quote">"Whispers of the Nebula are still forming. Join the conversation."</p>
                                <div class="client">
                                    <strong>Beetle System</strong>
                                    <span>Creative Void</span>
                                </div>
                            </div>
                        <?php else: ?>
                            <?php foreach ($home_testimonials as $ht): ?>
                                <div class="testimonial-slide">
                                    <p class="quote">"<?php echo htmlspecialchars($ht['content']); ?>"</p>
                                    <div class="client">
                                        <strong><?php echo htmlspecialchars($ht['author']); ?></strong>
                                        <span><?php echo htmlspecialchars($ht['position'] ?? 'Client'); ?>, <?php echo htmlspecialchars($ht['company'] ?? 'Internal'); ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div class="testimonial-controls">
                        <button class="t-arrow t-prev" id="t-prev" aria-label="Previous testimonial">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="15 18 9 12 15 6" />
                            </svg>
                        </button>
                        <div class="t-dots" id="t-dots">
                            <?php 
                            $dots_count = !empty($home_testimonials) ? count($home_testimonials) : 1;
                            for($i=0; $i<$dots_count; $i++): 
                            ?>
                                <button class="t-dot <?php echo $i === 0 ? 'active' : ''; ?>" data-index="<?php echo $i; ?>"></button>
                            <?php endfor; ?>
                        </div>
                        <button class="t-arrow t-next" id="t-next" aria-label="Next testimonial">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="9 18 15 12 9 6" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Review Submission Section -->
        <section id="submit-review" class="section review-submission-section">
            <div class="container">
                <div class="review-layout">
                    <div class="review-header reveal-from-left">
                        <span class="subheading"><span>08 / YOUR VOICE</span></span>
                        <h2><span>Help us grow with your valuable feedback.</span></h2>
                        <p class="review-intro">Your insights drive our creative evolution. Share your experience
                            working with Beetle System.</p>

                        <div class="review-guidelines">
                            <div class="guide-item">
                                <span class="guide-dot"></span>
                                <p>Be authentic and specific</p>
                            </div>
                            <div class="guide-item">
                                <span class="guide-dot"></span>
                                <p>Highlight key project wins</p>
                            </div>
                        </div>
                        <div class="payment-flex-teaser">
                            <div class="payment-heading">
                                Now you can pay in parts <span class="new-tag">New</span>
                            </div>
                            <button class="payment-info-btn" id="open-payment-modal">How it works →</button>
                        </div>
                    </div>

                    <div class="review-form-container reveal-from-right">
                        <form id="review-form" class="modern-form">
                            <div class="rating-group">
                                <label>OVERALL EXPERIENCE</label>
                                <div class="star-rating" id="star-rating">
                                    <input type="radio" name="rating" value="5" id="star5"><label for="star5">★</label>
                                    <input type="radio" name="rating" value="4" id="star4"><label for="star4">★</label>
                                    <input type="radio" name="rating" value="3" id="star3"><label for="star3">★</label>
                                    <input type="radio" name="rating" value="2" id="star2"><label for="star2">★</label>
                                    <input type="radio" name="rating" value="1" id="star1"><label for="star1">★</label>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="review-name">FULL NAME</label>
                                    <input type="text" id="review-name" placeholder="Your Name" required>
                                </div>
                                <div class="form-group">
                                    <label for="review-role">TITLE / COMPANY</label>
                                    <input type="text" id="review-role" placeholder="Your Title / Company" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="review-quote">YOUR REVIEW</label>
                                <textarea id="review-quote" rows="4"
                                    placeholder="Describe your experience with our services..." required></textarea>
                            </div>

                            <button type="submit" class="submit-btn transmit-btn magnetic">
                                <span class="btn-content">
                                    SUBMIT FEEDBACK
                                </span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>

    <!-- Multi-Step Inquiry Modal -->
    <div id="contact-modal" class="modal multi-step-modal">
        <div class="modal-content">
            <button class="modal-close" id="contact-modal-close" aria-label="Close modal">&times;</button>

            <div class="modal-progress">
                <div class="progress-bar" id="form-progress" style="width: 33.33%;"></div>
            </div>

            <form id="multi-step-form">
                <!-- Step 1: Personal Details -->
                <div class="step active" data-step="1">
                    <h2>TELL US ABOUT YOU</h2>
                    <div class="form-group">
                        <label>FULL NAME</label>
                        <input type="text" id="ms-name" placeholder="John Doe" required>
                    </div>
                    <div class="form-group">
                        <label>COMPANY / ORGANIZATION</label>
                        <input type="text" id="ms-company" placeholder="TechCorp Inc.">
                    </div>
                    <div class="form-navigation">
                        <button type="button" class="next-btn">CONTINUE</button>
                    </div>
                </div>

                <!-- Step 2: Contact Details -->
                <div class="step" data-step="2">
                    <h2>HOW TO REACH YOU</h2>
                    <div class="form-group">
                        <label>WORK EMAIL</label>
                        <input type="email" id="ms-email" placeholder="john@techcorp.com" required>
                    </div>
                    <div class="form-group">
                        <label>PHONE NUMBER (OPTIONAL)</label>
                        <input type="tel" id="ms-phone" placeholder="+1 (555) 000-0000">
                    </div>
                    <div class="form-navigation">
                        <button type="button" class="back-btn">BACK</button>
                        <button type="button" class="next-btn">CONTINUE</button>
                    </div>
                </div>

                <!-- Step 3: Additional Discovery & Source -->
                <div class="step" data-step="3">
                    <h2>PROJECT DISCOVERY</h2>
                    <div class="form-group">
                        <label>BRIEF PROJECT OVERVIEW</label>
                        <textarea id="ms-project" rows="3" placeholder="What are you looking to build?"></textarea>
                    </div>

                    <div class="source-header">Where did you hear about us?</div>

                    <!-- Main Categories -->
                    <div class="main-category-grid" id="main-cat-grid">
                        <div class="main-cat-item" data-category="social">Social Media</div>
                        <div class="main-cat-item" data-category="websites">Web Platforms</div>
                        <div class="main-cat-item" data-category="referrals">Referrals</div>
                        <div class="main-cat-item" data-category="physical">Physical</div>
                        <div class="main-cat-item" data-category="offline">Offline Ads</div>
                        <div class="main-cat-item" data-category="others">Others</div>
                    </div>

                    <!-- Sub Options Container -->
                    <div class="sub-options-container">
                        <!-- Social Media -->
                        <div class="sub-options-group" data-parent="social">
                            <div class="cat-item" data-value="Instagram"><span class="cat-icon">📸</span> Instagram
                            </div>
                            <div class="cat-item" data-value="LinkedIn"><span class="cat-icon">💼</span> LinkedIn</div>
                            <div class="cat-item" data-value="Facebook"><span class="cat-icon">👥</span> Facebook</div>
                            <div class="cat-item" data-value="Others"><span class="cat-icon">➕</span> Others</div>
                            <div class="custom-detail-field" id="ms-social-custom">
                                <label>PLEASE SPECIFY PLATFORM</label>
                                <input type="text" placeholder="e.g. TikTok, Twitter...">
                            </div>
                        </div>

                        <!-- Websites -->
                        <div class="sub-options-group" data-parent="websites">
                            <div class="cat-item" data-value="pwclimate"><span class="cat-icon">🌐</span> pwclimate
                            </div>
                            <div class="cat-item" data-value="dryium"><span class="cat-icon">✨</span> dryium</div>
                            <div class="cat-item" data-value="portfolios"><span class="cat-icon">🎨</span> Portfolios
                            </div>
                            <div class="cat-item" data-value="Others"><span class="cat-icon">➕</span> Others</div>
                            <div class="custom-detail-field" id="ms-web-custom">
                                <label>PLEASE SPECIFY WEBSITE</label>
                                <input type="text" placeholder="URL or Name...">
                            </div>
                        </div>

                        <!-- Referrals -->
                        <div class="sub-options-group" data-parent="referrals">
                            <div class="cat-item" data-value="Client Referral"><span class="cat-icon">🤝</span> Client
                            </div>
                            <div class="cat-item" data-value="Partner Referral"><span class="cat-icon">🏢</span> Partner
                            </div>
                            <div class="cat-item" data-value="Others"><span class="cat-icon">➕</span> Others</div>
                            <div class="custom-detail-field" id="ms-ref-custom">
                                <label>WHO REFERRED YOU?</label>
                                <input type="text" placeholder="Enter name or company...">
                            </div>
                        </div>

                        <!-- Physical Interaction -->
                        <div class="sub-options-group" data-parent="physical">
                            <div class="cat-item" data-value="Tech Events"><span class="cat-icon">🎪</span> Tech Events
                            </div>
                            <div class="cat-item" data-value="Office Visit"><span class="cat-icon">🏢</span> Office
                                Visit</div>
                            <div class="cat-item" data-value="Others"><span class="cat-icon">➕</span> Others</div>
                            <div class="custom-detail-field" id="ms-physical-custom">
                                <label>SPECIFY INTERACTION</label>
                                <input type="text" placeholder="Meeting location, event name...">
                            </div>
                        </div>

                        <!-- Offline Ads -->
                        <div class="sub-options-group" data-parent="offline">
                            <div class="cat-item" data-value="Billboard"><span class="cat-icon">🖼️</span> Billboard
                            </div>
                            <div class="cat-item" data-value="Print Media"><span class="cat-icon">📰</span> Print Media
                            </div>
                            <div class="cat-item" data-value="Others"><span class="cat-icon">➕</span> Others</div>
                            <div class="custom-detail-field" id="ms-offline-custom">
                                <label>SPECIFY AD SOURCE</label>
                                <input type="text" placeholder="Magazine, Billboard location...">
                            </div>
                        </div>

                        <!-- Others -->
                        <div class="sub-options-group" data-parent="others">
                            <div class="custom-detail-field active" style="grid-column: span 2;">
                                <label>PLEASE DESCRIBE</label>
                                <input type="text" id="ms-other-detail-final" placeholder="How did you find us?">
                            </div>
                        </div>

                        <div id="sub-placeholder"
                            style="text-align:center; opacity:0.3; font-size:0.8rem; padding-top:1rem;">
                            Select a category above to see options
                        </div>
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

    <div id="package-modal" class="modal">
        <div class="modal-content">
            <button class="modal-close modal-close-btn">&times;</button>
            <div class="modal-body">
                <h2 id="modal-title">Package Details</h2>
                <p id="modal-desc"></p>
                <ul id="modal-list" class="modal-list"></ul>
                <div class="modal-footer">
                    <span id="modal-price" class="modal-price-teaser"></span>
                    <a href="contact" class="modal-cta">GET STARTED</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Info Modal -->
    <div id="payment-modal" class="modal">
        <div class="modal-content info-modal">
            <button class="modal-close" id="payment-modal-close" aria-label="Close modal">&times;</button>
            <h2>PAYMENT FLEXIBILITY</h2>
            <p>We understand that big projects require smart financial planning. That's why we now offer split payments
                to make high-end development accessible.</p>
            <div class="payment-steps">
                <div class="p-step">
                    <span class="p-num">01</span>
                    <h3>Infrastructure First</h3>
                    <p>Get started by only paying for domain registration, hosting, and initial setup to get your
                        project live.</p>
                </div>
                <div class="p-step">
                    <span class="p-num">02</span>
                    <h3>Milestone Delivery</h3>
                    <p>Pay the remaining balance in flexible installments as we achieve key development milestones.</p>
                </div>
            </div>
            <button class="cta-btn" style="margin-top: 2rem; width: 100%;">Limited Time Offer</button>
        </div>
    </div>

    <script>
        // CMS Orchestrated Data
        window.beetleCMS = {
            csrfToken: '<?php echo csrf_token_js(); ?>',
            starterFeatures: <?php echo json_encode($pk_starter); ?>,
            premiumFeatures: <?php echo json_encode($pk_premium); ?>,
            enterpriseFeatures: <?php echo json_encode($pk_enterprise); ?>
        };
    </script>
    <script src="core/main.js"></script>
</body>

</html>