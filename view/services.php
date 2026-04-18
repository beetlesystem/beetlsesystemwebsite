<?php 
require_once '../core/db.php';
include '../includes/pageheader.php'; 
?>

<body class="inner-page">
    <div class="grain"></div>

    <?php include '../includes/loader.php'; ?>
    <?php include '../includes/navigation.php'; ?>

    <!-- Page Curtain Reveal -->
    <div class="page-curtain-top"></div>
    <div class="page-curtain-bottom"></div>

    <main>
        <!-- Modular Services Hero -->
        <section class="section services-modern-hero">
            <div class="container">
                <div class="services-hero-branding reveal-from-bottom">
                    <span class="subheading"><span>OUR EXPERTISE</span></span>
                    <h1>Digital <br><span class="accent-text">Architecture.</span></h1>
                    <p class="hero-lead">We combine surgical technical precision with visionary design to build digital products that define industries.</p>
                </div>

                <div class="blueprint-capabilities-grid">
                    <div class="blueprint-card reveal-from-bottom magnetic">
                        <div class="bp-icon"><i class="fas fa-bolt"></i></div>
                        <div class="bp-content">
                            <h3>High-Velocity <br>Engineering</h3>
                            <p>Sub-second load times and optimized runtime performance across all digital touchpoints.</p>
                        </div>
                    </div>
                    <div class="blueprint-card reveal-from-bottom delay-1 magnetic">
                        <div class="bp-icon"><i class="fas fa-bezier-curve"></i></div>
                        <div class="bp-content">
                            <h3>Surgical UI/UX <br>Design</h3>
                            <p>Interface architectures that prioritize intuitive human interaction and aesthetic clarity.</p>
                        </div>
                    </div>
                    <div class="blueprint-card reveal-from-bottom delay-2 magnetic">
                        <div class="bp-icon"><i class="fas fa-microchip"></i></div>
                        <div class="bp-content">
                            <h3>Scalable Cloud <br>Infrastructures</h3>
                            <p>Resilient backend systems built to sustain exponential growth and complex data logic.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section service-stack-details">
            <div class="container">
                <div class="service-stack-grid">
                    <!-- Service 01 -->
                    <div class="stack-item reveal-from-bottom">
                        <div class="stack-numeric">01</div>
                        <div class="stack-info">
                            <h2>Strategic Product <br>Design</h2>
                            <p>We don't just design screens; we build ecosystems. Our process begins with deep user research and ends with a cohesive design system that scales with your ambition.</p>
                            <div class="stack-tags">
                                <span>Design Systems</span>
                                <span>Interaction Design</span>
                                <span>Prototyping</span>
                            </div>
                        </div>
                    </div>

                    <!-- Service 02 -->
                    <div class="stack-item reveal-from-bottom">
                        <div class="stack-numeric">02</div>
                        <div class="stack-info">
                            <h2>Next-Gen Web <br>Development</h2>
                            <p>Leveraging cutting-edge technologies like Next.js, headless CMS architectures, and global edge networks to deliver blazingly fast web experiences.</p>
                            <div class="stack-tags">
                                <span>Next.js / React</span>
                                <span>Node.js</span>
                                <span>Performance Audit</span>
                            </div>
                        </div>
                    </div>

                    <!-- Service 03 -->
                    <div class="stack-item reveal-from-bottom">
                        <div class="stack-numeric">03</div>
                        <div class="stack-info">
                            <h2>Full-Stack <br>App Solutions</h2>
                            <p>From complex SaaS platforms to cross-platform mobile applications, we engineer solutions that bridge the gap between business goals and user needs.</p>
                            <div class="stack-tags">
                                <span>SaaS Architecture</span>
                                <span>API Engineering</span>
                                <span>Mobile (Native/Hybrid)</span>
                            </div>
                        </div>
                    </div>
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
