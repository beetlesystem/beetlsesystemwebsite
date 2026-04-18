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
        <!-- Editorial About Hero -->
        <section class="section about-editorial-hero">
            <div class="container">
                <div class="about-hero-branding reveal-from-bottom">
                    <span class="subheading"><span>OUR STORY</span></span>
                    <h1>We build digital tools that <br><span class="accent-text">help your business grow.</span></h1>
                </div>
            </div>
        </section>

        <!-- The Story Section -->
        <section class="section editorial-story-section">
            <div class="container">
                <div class="story-layout">
                    <div class="story-image reveal-from-left">
                        <div class="img-reveal-wrapper">
                            <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80&w=2301" alt="Studio Architecture">
                        </div>
                    </div>
                    
                    <div class="story-content reveal-from-right">
                        <div class="about-body">
                            <h2>The Philosophy</h2>
                            <p>At Beetle System, we do more than just build websites. We create digital tools that help your business grow. We believe that good design should be simple, and good code should be powerful.</p>
                            <p>We started with a passion for clean design. Today, we help brands all over the world make a great impression online with confidence.</p>
                        </div>

                        <div class="about-stats-grid-simple">
                            <div class="stat-item magnetic">
                                <span class="stat-num">10+</span>
                                <span class="stat-label">YEARS EXPERIENCE</span>
                            </div>
                            <div class="stat-item magnetic">
                                <span class="stat-num">50+</span>
                                <span class="stat-label">HAPPY CLIENTS</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section values-section">
            <div class="container">
                <span class="subheading"><span>OUR CORE VALUES</span></span>
                <div class="values-grid">
                    <div class="value-card reveal-from-bottom magnetic">
                        <span class="value-num">01</span>
                        <h3>Precision</h3>
                        <p>We sweat the small stuff. From pixel-perfect layouts to optimized server response times, quality is non-negotiable.</p>
                    </div>
                    <div class="value-card reveal-from-bottom delay-1 magnetic">
                        <span class="value-num">02</span>
                        <h3>Innovation</h3>
                        <p>We stay at the bleeding edge of web technology, ensuring your project is built for the future.</p>
                    </div>
                    <div class="value-card reveal-from-bottom delay-2 magnetic">
                        <span class="value-num">03</span>
                        <h3>Empathy</h3>
                        <p>We build for humans. Our user-centric design process ensures that your audience feels heard and understood.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Methodology Section: The Blueprint -->
        <section class="section blueprint-section">
            <div class="container">
                <div class="blueprint-header reveal-from-bottom">
                    <span class="subheading"><span>OUR PROCESS</span></span>
                    <h2>How we <br><span class="accent-text">Work.</span></h2>
                </div>

                <div class="blueprint-grid">
                    <div class="blueprint-item reveal-from-bottom">
                        <span class="b-step">01.</span>
                        <div class="b-content">
                            <h3>PLANNING</h3>
                            <p>We talk about your goals and create a clear plan for your project.</p>
                        </div>
                    </div>
                    <div class="blueprint-item reveal-from-bottom delay-1">
                        <span class="b-step">02.</span>
                        <div class="b-content">
                            <h3>STRATEGY</h3>
                            <p>We build the technical foundation to make sure everything runs smoothly.</p>
                        </div>
                    </div>
                    <div class="blueprint-item reveal-from-bottom delay-2">
                        <span class="b-step">03.</span>
                        <div class="b-content">
                            <h3>DESIGN</h3>
                            <p>We create beautiful and easy-to-use interfaces for your audience.</p>
                        </div>
                    </div>
                    <div class="blueprint-item reveal-from-bottom delay-3">
                        <span class="b-step">04.</span>
                        <div class="b-content">
                            <h3>LAUNCH</h3>
                            <p>We test everything carefully and launch your new website to the world.</p>
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
                    <div class="form-group">
                        <label>BRIEF PROJECT OVERVIEW</label>
                        <textarea id="ms-project" rows="3" placeholder="What are you looking to build?"></textarea>
                    </div>
                    
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
                        <!-- Social Media -->
                        <div class="sub-options-group" data-parent="social">
                            <div class="cat-item" data-value="Instagram"><span class="cat-icon">📸</span> Instagram</div>
                            <div class="cat-item" data-value="LinkedIn"><span class="cat-icon">💼</span> LinkedIn</div>
                            <div class="cat-item" data-value="Facebook"><span class="cat-icon">👥</span> Facebook</div>
                            <div class="cat-item" data-value="Others"><span class="cat-icon">➕</span> Others</div>
                            <div class="custom-detail-field" id="ms-social-custom">
                                <label>PLEASE SPECIFY PLATFORM</label>
                                <input type="text" placeholder="...">
                            </div>
                        </div>

                        <!-- Websites -->
                        <div class="sub-options-group" data-parent="websites">
                            <div class="cat-item" data-value="pwclimate"><span class="cat-icon">🌐</span> pwclimate</div>
                            <div class="cat-item" data-value="dryium"><span class="cat-icon">✨</span> dryium</div>
                            <div class="cat-item" data-value="portfolios"><span class="cat-icon">🎨</span> Portfolios</div>
                            <div class="cat-item" data-value="Others"><span class="cat-icon">➕</span> Others</div>
                            <div class="custom-detail-field" id="ms-web-custom">
                                <label>PLEASE SPECIFY WEBSITE</label>
                                <input type="text" placeholder="...">
                            </div>
                        </div>

                        <!-- Referrals -->
                        <div class="sub-options-group" data-parent="referrals">
                            <div class="cat-item" data-value="Client Referral"><span class="cat-icon">🤝</span> Client</div>
                            <div class="cat-item" data-value="Partner Referral"><span class="cat-icon">🏢</span> Partner</div>
                            <div class="cat-item" data-value="Others"><span class="cat-icon">➕</span> Others</div>
                            <div class="custom-detail-field" id="ms-ref-custom">
                                <label>WHO REFERRED YOU?</label>
                                <input type="text" placeholder="...">
                            </div>
                        </div>

                        <!-- Physical Interaction -->
                        <div class="sub-options-group" data-parent="physical">
                            <div class="cat-item" data-value="Events"><span class="cat-icon">🎪</span> Tech Events</div>
                            <div class="cat-item" data-value="Office Visit"><span class="cat-icon">🏢</span> Office Visit</div>
                            <div class="cat-item" data-value="Others"><span class="cat-icon">➕</span> Others</div>
                            <div class="custom-detail-field" id="ms-physical-custom">
                                <label>SPECIFY INTERACTION</label>
                                <input type="text" placeholder="...">
                            </div>
                        </div>

                        <!-- Offline Ads -->
                        <div class="sub-options-group" data-parent="offline">
                            <div class="cat-item" data-value="Billboard"><span class="cat-icon">🖼️</span> Billboard</div>
                            <div class="cat-item" data-value="Print Media"><span class="cat-icon">📰</span> Print Media</div>
                            <div class="cat-item" data-value="Others"><span class="cat-icon">➕</span> Others</div>
                            <div class="custom-detail-field" id="ms-offline-custom">
                                <label>SPECIFY AD SOURCE</label>
                                <input type="text" placeholder="...">
                            </div>
                        </div>

                        <!-- Others -->
                        <div class="sub-options-group" data-parent="others">
                            <div class="custom-detail-field active" style="grid-column: span 2;">
                                <label>PLEASE DESCRIBE</label>
                                <input type="text" id="ms-other-detail-final" placeholder="How did you find us?">
                            </div>
                        </div>

                        <div id="sub-placeholder" style="text-align:center; opacity:0.3; font-size:0.8rem; padding-top:1rem;">
                            Select a category above
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

    <script src="https://cdn.jsdelivr.net/npm/lenis@latest/dist/lenis.min.js"></script>
    <script src="core/main.js"></script>
</body>

</html>
