<?php include '../includes/pageheader.php'; ?>

<body class="inner-page">
    <div class="grain"></div>
    <div id="cursor"></div>
    <div id="cursor-follower"></div>

    <?php include '../includes/loader.php'; ?>
    <?php include '../includes/navigation.php'; ?>

    <main>
        <section class="page-hero">
            <div class="container">
                <span class="subheading"><span>CAPABILITIES</span></span>
                <h1 class="reveal-from-left">Digital craft that <br><span class="accent-text">Drives Results.</span></h1>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <div class="services-list-detailed">
                    <!-- Service 01 -->
                    <div class="service-item-detail reveal-from-bottom">
                        <div class="service-meta">
                            <span class="service-index">01</span>
                            <h2>UI/UX Design</h2>
                        </div>
                        <div class="service-desc">
                            <p>We create beautiful, functional interfaces that prioritize the user journey. Our design philosophy combines minimalist aesthetics with intuitive navigation to reduce friction and increase engagement.</p>
                            <ul class="feature-tags">
                                <li>Interface Design</li>
                                <li>User Research</li>
                                <li>Prototyping</li>
                                <li>Design Systems</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Service 02 -->
                    <div class="service-item-detail reveal-from-bottom">
                        <div class="service-meta">
                            <span class="service-index">02</span>
                            <h2>Web Development</h2>
                        </div>
                        <div class="service-desc">
                            <p>Building fast, responsive, and SEO-optimized websites using the latest technologies. We focus on clean code and performance to ensure your digital home is as robust as it is beautiful.</p>
                            <ul class="feature-tags">
                                <li>React / Next.js</li>
                                <li>Performance Optimization</li>
                                <li>Headless CMS</li>
                                <li>Responsive Frameworks</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Service 03 -->
                    <div class="service-item-detail reveal-from-bottom">
                        <div class="service-meta">
                            <span class="service-index">03</span>
                            <h2>SaaS Solutions</h2>
                        </div>
                        <div class="service-desc">
                            <p>Developing scalable cloud-based applications that solve real-world business problems. From multi-tenant architectures to seamless API integrations, we build for growth.</p>
                            <ul class="feature-tags">
                                <li>Cloud Architecture</li>
                                <li>Database Design</li>
                                <li>Auth Systems</li>
                                <li>Subscription Flows</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Service 04 -->
                    <div class="service-item-detail reveal-from-bottom">
                        <div class="service-meta">
                            <span class="service-index">04</span>
                            <h2>Mobile Apps</h2>
                        </div>
                        <div class="service-desc">
                            <p>Cross-platform mobile experiences that feel native. We build high-performance applications that keep your users connected on the go.</p>
                            <ul class="feature-tags">
                                <li>React Native</li>
                                <li>iOS & Android</li>
                                <li>App Store Optimization</li>
                                <li>Biometric Auth</li>
                            </ul>
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
