<?php 
require_once '../core/db.php';
require_once '../core/track_visitor.php';
trackVisitor($pdo, 'Contact');
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
        <!-- Visionary Hero & Contact Portal -->
        <section class="section contact-visionary-section">
            <div class="container-fluid">
                <!-- Page Main Title -->
                <div class="contact-main-header reveal-from-bottom">
                    <h1>CONTACT US</h1>
                </div>

                <div class="contact-split-layout">
                    <!-- Left: Large Typography & Info -->
                    <div class="contact-branding-column">
                        <div class="vertical-title">CONTACT US</div>
                        
                        <!-- Ghost Image Parallax -->
                        <div class="contact-hero-image">
                            <div class="img-reveal-wrapper">
                                <img src="https://images.unsplash.com/photo-1497215728101-856f4ea42174?auto=format&fit=crop&q=80&w=2426" alt="Digital Craft">
                            </div>
                        </div>

                        <div class="contact-branding-content reveal-from-left">
                            <div class="hero-label">GET IN TOUCH</div>
                            <h1>Let's build <br><span class="accent-text">Together.</span></h1>
                            
                            <div class="branding-details">
                                <div class="detail-group magnetic">
                                    <span class="detail-label">EMAIL</span>
                                    <a href="mailto:beetlesystem@gmail.com" class="detail-link">beetlesystem@gmail.com</a>
                                </div>
                                <div class="detail-group magnetic">
                                    <span class="detail-label">OFFICE</span>
                                    <p class="detail-text">Srinagar, J&K, India</p>
                                    <a href="tel:+916005958161" class="detail-link">+91 6005958161</a> <br>
                                    <a href="tel:+916006801960" class="detail-link">+91 6006801960</a>
                                </div>
                                <div class="detail-group">
                                    <span class="detail-label">SOCIAL</span>
                                    <div class="social-mini-flex">
                                        <a href="#" class="magnetic">Instagram</a>
                                        <a href="#" class="magnetic">LinkedIn</a>
                                        <a href="#" class="magnetic">Twitter</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Scroll Indicator -->
                        <div class="contact-scroll-hint">
                            <div class="scroll-line"></div>
                            <span>SCROLL TO DISCOVER</span>
                        </div>
                    </div>

                    <!-- Right: Minimalist Form -->
                    <div class="contact-form-column reveal-from-right">
                        <div class="form-instruction">
                            <span class="step-num">01</span>
                            <p>TELL US ABOUT YOUR PROJECT GOALS AND WE'LL GET BACK TO YOU AS SOON AS POSSIBLE.</p>
                        </div>

                        <form id="main-contact-form" class="visionary-form">
                            <div class="form-grid-inputs">
                                <div class="v-form-group">
                                    <label>YOUR NAME</label>
                                    <input type="text" id="contact-name" placeholder="John Doe" required>
                                </div>
                                
                                <div class="v-form-group">
                                    <label>YOUR EMAIL</label>
                                    <input type="email" id="contact-email" placeholder="john@example.com" required>
                                </div>

                                <div class="v-form-group">
                                    <label>PHONE NUMBER (OPTIONAL)</label>
                                    <input type="tel" id="contact-phone" placeholder="+91 00000 00000">
                                </div>

                                <div class="v-form-group">
                                    <label>SUBJECT</label>
                                    <input type="text" id="contact-subject" placeholder="Project Inquiry" required>
                                </div>
                            </div>

                            <div class="v-form-group">
                                <label>MESSAGE</label>
                                <textarea id="contact-message" rows="4" placeholder="Tell us about your project..." required></textarea>
                            </div>

                            <div class="form-action-zone">
                                <button type="submit" class="transmit-btn submit-btn magnetic">
                                    <span>SEND MESSAGE</span>
                                    <div class="btn-arrow">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                            <polyline points="12 5 19 12 12 19"></polyline>
                                        </svg>
                                    </div>
                                </button>
                            </div>
                        </form>
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
            <div class="modal-progress">
                <div class="progress-bar" id="form-progress" style="width: 33.33%;"></div>
            </div>
            <form id="multi-step-form">
                <div class="step active" data-step="1">
                    <h2>TELL US ABOUT YOU</h2>
                    <div class="form-group"><label>FULL NAME</label><input type="text" id="ms-name"
                            placeholder="John Doe" required></div>
                    <div class="form-group"><label>COMPANY / ORGANIZATION</label><input type="text" id="ms-company"
                            placeholder="TechCorp Inc."></div>
                    <div class="form-navigation"><button type="button" class="next-btn">CONTINUE</button></div>
                </div>
                <div class="step" data-step="2">
                    <h2>HOW TO REACH YOU</h2>
                    <div class="form-group"><label>WORK EMAIL</label><input type="email" id="ms-email"
                            placeholder="john@techcorp.com" required></div>
                    <div class="form-group"><label>PHONE NUMBER (OPTIONAL)</label><input type="tel" id="ms-phone"
                            placeholder="+1 (555) 000-0000"></div>
                    <div class="form-navigation"><button type="button" class="back-btn">BACK</button><button
                            type="button" class="next-btn">CONTINUE</button></div>
                </div>
                <!-- Step 3: Additional Discovery & Source -->
                <div class="step" data-step="3">
                    <h2>PROJECT DISCOVERY</h2>
                    <div class="form-group"><label>BRIEF PROJECT OVERVIEW</label><textarea id="ms-project" rows="3"
                            placeholder="What are you looking to build?"></textarea></div>
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
                            <div class="cat-item" data-value="Instagram"><span class="cat-icon">📸</span> Instagram
                            </div>
                            <div class="cat-item" data-value="LinkedIn"><span class="cat-icon">💼</span> LinkedIn</div>
                            <div class="cat-item" data-value="Facebook"><span class="cat-icon">👥</span> Facebook</div>
                            <div class="cat-item" data-value="Others"><span class="cat-icon">➕</span> Others</div>
                            <div class="custom-detail-field" id="ms-social-custom"><label>SPECIFY PLATFORM</label><input
                                    type="text" placeholder="..."></div>
                        </div>
                        <div class="sub-options-group" data-parent="websites">
                            <div class="cat-item" data-value="pwclimate"><span class="cat-icon">🌐</span> pwclimate
                            </div>
                            <div class="cat-item" data-value="dryium"><span class="cat-icon">✨</span> dryium</div>
                            <div class="cat-item" data-value="portfolios"><span class="cat-icon">🎨</span> Portfolios
                            </div>
                            <div class="cat-item" data-value="Others"><span class="cat-icon">➕</span> Others</div>
                            <div class="custom-detail-field" id="ms-web-custom"><label>SPECIFY WEBSITE</label><input
                                    type="text" placeholder="..."></div>
                        </div>
                        <div class="sub-options-group" data-parent="referrals">
                            <div class="cat-item" data-value="Client Referral"><span class="cat-icon">🤝</span> Client
                            </div>
                            <div class="cat-item" data-value="Partner Referral"><span class="cat-icon">🏢</span> Partner
                            </div>
                            <div class="cat-item" data-value="Others"><span class="cat-icon">➕</span> Others</div>
                            <div class="custom-detail-field" id="ms-ref-custom"><label>WHO REFERRED YOU?</label><input
                                    type="text" placeholder="..."></div>
                        </div>
                        <div class="sub-options-group" data-parent="physical">
                            <div class="cat-item" data-value="Events"><span class="cat-icon">🎪</span> Tech Events</div>
                            <div class="cat-item" data-value="Office Visit"><span class="cat-icon">🏢</span> Office
                                Visit</div>
                            <div class="cat-item" data-value="Others"><span class="cat-icon">➕</span> Others</div>
                            <div class="custom-detail-field" id="ms-physical-custom"><label>SPECIFY
                                    INTERACTION</label><input type="text" placeholder="..."></div>
                        </div>
                        <div class="sub-options-group" data-parent="offline">
                            <div class="cat-item" data-value="Billboard"><span class="cat-icon">🖼️</span> Billboard
                            </div>
                            <div class="cat-item" data-value="Print Media"><span class="cat-icon">📰</span> Print Media
                            </div>
                            <div class="cat-item" data-value="Others"><span class="cat-icon">➕</span> Others</div>
                            <div class="custom-detail-field" id="ms-offline-custom"><label>SPECIFY AD
                                    SOURCE</label><input type="text" placeholder="..."></div>
                        </div>
                        <div class="sub-options-group" data-parent="others">
                            <div class="custom-detail-field active" style="grid-column: span 2;"><label>PLEASE
                                    DESCRIBE</label><input type="text" id="ms-other-detail-final" placeholder="...">
                            </div>
                        </div>
                        <div id="sub-placeholder"
                            style="text-align:center; opacity:0.3; font-size:0.8rem; padding-top:1rem;">Select a
                            category above</div>
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