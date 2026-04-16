document.addEventListener('DOMContentLoaded', () => {
    // --- Initialize Lenis ---
    const lenis = new Lenis({
        autoRaf: true,
    });

    // Elements
    const curtainTop = document.getElementById('curtain-top');
    const curtainBottom = document.getElementById('curtain-bottom');
    const heroWrapper = document.getElementById('hero');
    const navbar = document.getElementById('navbar');
    const cursor = document.getElementById('cursor');
    const follower = document.getElementById('cursor-follower');
    const revealH1 = document.querySelector('.reveal-h1');
    const cycleWrapper = document.querySelector('.hero-cycle-wrapper');
    const cyclePhrases = document.querySelectorAll('.cycle-phrase');
    const images = document.querySelectorAll('.img-reveal-wrapper img');
    const projectImages = document.querySelectorAll('.project-img img');
    const scrollProgress = document.getElementById('scroll-progress');
    const navToggle = document.getElementById('nav-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const backToTop = document.getElementById('back-to-top');

    // --- Mobile Menu Toggle ---
    const openMenu = () => {
        lenis.stop();
        navToggle.classList.add('active');
        mobileMenu.classList.add('active');
        document.body.classList.add('menu-open');
    };

    const closeMenu = () => {
        lenis.start();
        navToggle.classList.remove('active');
        mobileMenu.classList.remove('active');
        document.body.classList.remove('menu-open');
    };

    if (navToggle) {
        navToggle.addEventListener('click', () => {
            mobileMenu.classList.contains('active') ? closeMenu() : openMenu();
        });
    }

    const mobileClose = document.getElementById('mobile-close');
    if (mobileClose) mobileClose.addEventListener('click', closeMenu);

    document.querySelectorAll('.mobile-links a').forEach(link => {
        link.addEventListener('click', closeMenu);
    });

    // --- Custom Cursor with Trail ---
    const cursorDots = [];
    const dotCount = 8;
    for (let i = 0; i < dotCount; i++) {
        const dot = document.createElement('div');
        dot.classList.add('cursor-dot');
        // Make dots smaller and more transparent as they trail
        const size = 6 - (i * 0.5);
        dot.style.width = `${size}px`;
        dot.style.height = `${size}px`;
        dot.style.opacity = (1 - (i / dotCount)).toString();
        document.body.appendChild(dot);
        cursorDots.push({ el: dot, x: 0, y: 0 });
    }

    let mouseX = 0, mouseY = 0;
    document.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
        if (cursor) {
            cursor.style.left = `${mouseX}px`;
            cursor.style.top = `${mouseY}px`;
        }
        if (follower) {
            follower.style.left = `${mouseX}px`;
            follower.style.top = `${mouseY}px`;
        }
    });

    const renderCursor = () => {
        let x = mouseX;
        let y = mouseY;

        cursorDots.forEach((dot, index) => {
            // Easing / Lerp for the trail dots
            dot.x += (x - dot.x) * 0.35;
            dot.y += (y - dot.y) * 0.35;
            dot.el.style.left = `${dot.x}px`;
            dot.el.style.top = `${dot.y}px`;

            // The next dot follows the current one
            x = dot.x;
            y = dot.y;
        });

        requestAnimationFrame(renderCursor);
    };
    renderCursor();


    const addCursorHover = () => {
        const hoverElements = document.querySelectorAll('a, button, .project-item, .service-card, .social-icon, .back-to-top');
        hoverElements.forEach(el => {
            el.addEventListener('mouseenter', () => document.body.classList.add('cursor-hover'));
            el.addEventListener('mouseleave', () => document.body.classList.remove('cursor-hover'));
        });
    };
    addCursorHover();

    // --- Smooth Scroll ---
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                lenis.scrollTo(targetElement);
            }
        });
    });

    // --- Back to Top ---
    if (backToTop) {
        backToTop.addEventListener('click', () => {
            lenis.scrollTo(0);
        });
    }

    // --- Scroll-Linked Animations ---
    function updateOnScroll() {
        const scrollY = window.scrollY;
        const viewportHeight = window.innerHeight;

        // 1. Curtains Reveal
        if (curtainTop && curtainBottom) {
            const progress = Math.min(scrollY / (viewportHeight * 1.2), 1);
            curtainTop.style.transform = `translateY(${-progress * 105}%)`;
            curtainBottom.style.transform = `translateY(${progress * 105}%)`;
        }

        // 2. Headline Scaling
        if (cycleWrapper) {
            const progress = Math.min(scrollY / (viewportHeight * 1.2), 1);
            const scaleProgress = 0.8 + (progress * 0.3);
            cycleWrapper.style.transform = `scale(${scaleProgress})`;
        }

        // 3. Navbar Styling
        if (navbar) {
            if (scrollY > viewportHeight * 0.5) {
                navbar.style.background = 'rgba(231, 228, 211, 0.9)';
                navbar.style.backdropFilter = 'blur(10px)';
                navbar.style.padding = '1rem 5%';
            } else {
                navbar.style.background = 'transparent';
                navbar.style.backdropFilter = 'none';
                navbar.style.padding = '2rem 5%';
            }
        }

        // 4. Image Parallax
        document.querySelectorAll('.img-reveal-wrapper img, .project-img img').forEach(img => {
            const rect = img.parentElement.getBoundingClientRect();
            if (rect.top < viewportHeight && rect.bottom > 0) {
                const distance = viewportHeight + rect.height;
                const progress = (viewportHeight - rect.top) / distance;
                const move = (progress - 0.5) * 60;
                img.style.transform = `scale(1.1) translateY(${move}px)`;
            }
        });

        // 5. Scroll Progress
        if (scrollProgress) {
            const totalHeight = document.documentElement.scrollHeight - viewportHeight;
            const scrollPercent = (scrollY / totalHeight) * 100;
            scrollProgress.style.width = scrollPercent + "%";
        }

        // 6. Back to Top Visibility
        if (backToTop) {
            if (scrollY > viewportHeight) {
                backToTop.classList.add('active');
            } else {
                backToTop.classList.remove('active');
            }
        }
    }

    // --- Hero Ticker (Seamless vertical slot-machine) ---
    const cycleTrack = document.getElementById('cycle-track');
    const cWrapper = document.getElementById('cycle-wrapper');

    if (cycleTrack && cWrapper) {
        // Wait for layout so we can measure real phrase height
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                const firstPhrase = cycleTrack.firstElementChild;
                const phraseH = firstPhrase.offsetHeight;

                // Fix wrapper to exactly one phrase tall
                cWrapper.style.height = phraseH + 'px';

                // Clone first phrase → append at bottom for seamless loop
                const clone = firstPhrase.cloneNode(true);
                cycleTrack.appendChild(clone);

                let currentIdx = 0;
                let locked = false;

                const advance = () => {
                    if (locked) return;
                    locked = true;
                    currentIdx++;

                    // Slide track up
                    cycleTrack.style.transition = 'transform 1s cubic-bezier(0.16, 1, 0.3, 1)';
                    cycleTrack.style.transform = `translateY(-${currentIdx * phraseH}px)`;

                    setTimeout(() => {
                        // If we just showed the clone, snap back to position 0 instantly
                        if (currentIdx >= cycleTrack.children.length - 1) {
                            cycleTrack.style.transition = 'none';
                            cycleTrack.style.transform = 'translateY(0)';
                            currentIdx = 0;
                        }
                        locked = false;
                    }, 1050); // slightly longer than transition so snap happens after
                };

                setInterval(advance, 2800);
            });
        });
    }

    // --- Section Revealer (Observer) ---
    const sectionObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('reveal-active');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    document.querySelectorAll('.section:not(.hero-wrapper)').forEach(sec => {
        sectionObserver.observe(sec);
    });

    // --- Per-Element Direction Reveal Observer ---
    const elementObserver = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('in-view');
                obs.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15, rootMargin: '0px 0px -60px 0px' });

    document.querySelectorAll(
        '.reveal-from-left, .reveal-from-right, .reveal-from-bottom, .reveal-scale-in, .line-grow'
    ).forEach(el => elementObserver.observe(el));

    // --- Loader & Initial Hero ---
    const loader = document.getElementById('loader');
    const hero = document.getElementById('hero');
    window.addEventListener('load', () => {
        if (loader) {
            setTimeout(() => {
                loader.style.opacity = '0';
                setTimeout(() => {
                    loader.style.display = 'none';
                    document.body.classList.add('page-loaded');
                    if (hero) hero.classList.add('reveal-active');
                }, 1000);
            }, 1000);
        } else {
            // No loader? Just reveal immediately
            document.body.classList.add('page-loaded');
            if (hero) hero.classList.add('reveal-active');
        }
    });

    // --- Footer Accordion ---
    const accordionTriggers = document.querySelectorAll('.accordion-trigger');

    accordionTriggers.forEach(trigger => {
        trigger.addEventListener('click', function (e) {
            // Check if we are in mobile view based on CSS behavior
            // We use the visibility of chevron as a proxy for mobile mode
            const chevron = this.querySelector('.chevron');
            if (!chevron || window.getComputedStyle(chevron).display === 'none') return;

            e.preventDefault();
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            const newState = !isExpanded;

            // Toggle current accordion
            this.setAttribute('aria-expanded', newState.toString());

            // Pulse effect for feedback
            this.style.opacity = '0.5';
            setTimeout(() => this.style.opacity = '0.8', 150);

            // Optionally close other sections
            accordionTriggers.forEach(other => {
                if (other !== this) {
                    other.setAttribute('aria-expanded', 'false');
                }
            });
        });
    });

    // Run once on load
    updateOnScroll();

    window.addEventListener('scroll', () => {
        requestAnimationFrame(updateOnScroll);
    });

    // --- Testimonial Slider ---
    const track = document.getElementById('testimonial-track');
    const dots = document.querySelectorAll('.t-dot');
    const slides = document.querySelectorAll('.testimonial-slide');
    let currentSlide = 0;
    let autoPlayTimer;
    const TOTAL = slides.length;

    if (track && dots.length > 0) {
        const goTo = (index) => {
            currentSlide = (index + TOTAL) % TOTAL;
            track.style.transform = `translateX(-${currentSlide * 100}%)`;
            dots.forEach(d => d.classList.remove('active'));
            dots[currentSlide].classList.add('active');
        };

        document.getElementById('t-prev')?.addEventListener('click', () => goTo(currentSlide - 1));
        document.getElementById('t-next')?.addEventListener('click', () => goTo(currentSlide + 1));
        dots.forEach(dot => dot.addEventListener('click', () => goTo(parseInt(dot.dataset.index))));

        // Auto-play every 5s
        const startAutoPlay = () => { autoPlayTimer = setInterval(() => goTo(currentSlide + 1), 5000); };
        const stopAutoPlay = () => clearInterval(autoPlayTimer);

        const sliderSection = document.querySelector('.testimonial-section');
        if (sliderSection) {
            sliderSection.addEventListener('mouseenter', stopAutoPlay);
            sliderSection.addEventListener('mouseleave', startAutoPlay);
        }

        // Touch / swipe support
        let touchStartX = 0;
        track.addEventListener('touchstart', (e) => { touchStartX = e.changedTouches[0].clientX; }, { passive: true });
        track.addEventListener('touchend', (e) => {
            const diff = touchStartX - e.changedTouches[0].clientX;
            if (Math.abs(diff) > 50) goTo(diff > 0 ? currentSlide + 1 : currentSlide - 1);
        }, { passive: true });

        startAutoPlay();
    }

    // --- Package Modal Logic ---
    const packageData = {
        starter: {
            title: "Starter Package",
            desc: "Perfect for startups and small businesses looking for a professional entry into the digital space.",
            features: (window.beetleCMS && window.beetleCMS.starterFeatures) ? window.beetleCMS.starterFeatures : ["Custom UI/UX Design", "5-Page Responsive Website", "7 Days Post-Launch Support", "Basic SEO Optimization", "Contact Form Integration", "Social Media Linking"]
        },
        premium: {
            title: "Premium Package",
            desc: "Advanced solutions for growing businesses that require dynamic content management and interactive features.",
            features: (window.beetleCMS && window.beetleCMS.premiumFeatures) ? window.beetleCMS.premiumFeatures : ["Custom Dashboard & CMS", "Unlimited Pages (within scope)", "Advanced SEO & Analytics", "Interactive UI Animations", "15 Days Priority Support", "Newsletter Integration", "Speed Performance Tuning"]
        },
        enterprise: {
            title: "Enterprise Solutions",
            desc: "Tailor-made systems designed to automate complex business processes and scale infinitely.",
            features: (window.beetleCMS && window.beetleCMS.enterpriseFeatures) ? window.beetleCMS.enterpriseFeatures : ["E-commerce Management Platforms", "Custom CRM & Business Logic", "Billing & Invoicing Automation", "API & Third-party Integrations", "Dedicated Project Management", "30 Days Extensive Support", "Security Audits & Scaling"]
        }
    };

    const modal = document.getElementById('package-modal');
    const modalTitle = document.getElementById('modal-title');
    const modalDesc = document.getElementById('modal-desc');
    const modalList = document.getElementById('modal-list');
    const modalClose = document.querySelector('.modal-close');

    const openModal = (pkg) => {
        const data = packageData[pkg];
        if (!data) return;

        modalTitle.innerText = data.title;
        modalDesc.innerText = data.desc;
        modalList.innerHTML = data.features.map(f => `<li>${f}</li>`).join('');

        modal.classList.add('active');
        lenis.stop();
    };

    const closeModal = () => {
        modal.classList.remove('active');
        lenis.start();
    };

    document.querySelectorAll('.view-details').forEach(btn => {
        btn.addEventListener('click', () => {
            const pkg = btn.getAttribute('data-package');
            openModal(pkg);
        });
    });

    if (modalClose) modalClose.addEventListener('click', closeModal);

    // Centered Logo Click Animation
    const centerLogo = document.querySelector('.logo-circle-btn');
    if (centerLogo) {
        centerLogo.addEventListener('click', function (e) {
            e.preventDefault();
            const targetUrl = this.getAttribute('href');
            const rect = this.getBoundingClientRect();

            // Set starting position variables
            this.style.setProperty('--startX', rect.left + 'px');
            this.style.setProperty('--startY', rect.top + 'px');

            // Trigger animation
            requestAnimationFrame(() => {
                this.classList.add('is-animating');
            });

            // Wait for wings to fill screen (increased for 1.4s dive animation)
            setTimeout(() => {
                window.location.href = targetUrl;
            }, 1500);
        });
    }

    // Close modal on click outside content
    window.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });

    // Close on Escape key
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeModal();
    });
    // --- Global Feedback Modal ---
    const fbModal = document.getElementById('feedback-modal');
    const fbIcon = document.getElementById('fb-icon');
    const fbTitle = document.getElementById('fb-title');
    const fbMessage = document.getElementById('fb-message');
    const fbClose = document.getElementById('fb-close');

    const showFeedback = (title, message, type = 'success') => {
        fbTitle.innerText = title;
        fbMessage.innerText = message;
        fbIcon.innerText = type === 'success' ? '✓' : '×';
        fbIcon.className = 'feedback-icon' + (type === 'error' ? ' error' : '');
        
        fbModal.classList.add('active');
        lenis.stop();
    };

    const closeFeedback = () => {
        fbModal.classList.remove('active');
        lenis.start();
    };

    if (fbClose) fbClose.addEventListener('click', closeFeedback);
    fbModal?.addEventListener('click', (e) => { if (e.target === fbModal) closeFeedback(); });

    // --- Review Form Handling ---
    const reviewForm = document.getElementById('review-form');
    if (reviewForm) {
        reviewForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const name = document.getElementById('review-name').value;
            const role = document.getElementById('review-role').value;
            const quote = document.getElementById('review-quote').value;
            const rating = reviewForm.querySelector('input[name="rating"]:checked')?.value || '5';

            const btn = reviewForm.querySelector('.submit-btn');
            const originalContent = btn.innerHTML;

            btn.innerHTML = 'TRANSMITTING...';
            btn.style.pointerEvents = 'none';

            const formData = new FormData();
            formData.append('author', name);
            formData.append('position', role);
            formData.append('content', quote);
            formData.append('rating', rating);

            try {
                const response = await fetch('controller/testimonials/submit.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.status === 'success') {
                    showFeedback('Transmission Complete', result.message);
                    reviewForm.reset();
                    document.querySelectorAll('.star-rating input').forEach(radio => radio.checked = false);
                } else {
                    showFeedback('System Log', result.message, 'error');
                }
            } catch (error) {
                showFeedback('Signal Lost', 'Neural link disrupted. Please try again later.', 'error');
            } finally {
                btn.innerHTML = originalContent;
                btn.style.pointerEvents = 'all';
            }
        });
    }

    // --- Main Contact Form Handling ---
    const mainContactForm = document.getElementById('main-contact-form');
    if (mainContactForm) {
        mainContactForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = mainContactForm.querySelector('.submit-btn');
            const originalContent = btn.innerHTML;
            
            const formData = new FormData();
            formData.append('name', document.getElementById('contact-name').value);
            formData.append('email', document.getElementById('contact-email').value);
            formData.append('subject', document.getElementById('contact-subject').value);
            formData.append('message', document.getElementById('contact-message').value);

            btn.innerHTML = 'SENDING MESSAGE...';
            btn.style.pointerEvents = 'none';
            btn.style.opacity = '0.7';

            try {
                const response = await fetch('controller/contacts/submit.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.status === 'success') {
                    showFeedback('Transmission Complete', 'Thank you! Your inquiry has been received. Our team will contact you shortly.');
                    mainContactForm.reset();
                } else {
                    showFeedback('System Error', result.message, 'error');
                }
            } catch (error) {
                showFeedback('Signal Failure', 'An error occurred during transmission. Please try again later.', 'error');
            } finally {
                btn.innerHTML = originalContent;
                btn.style.pointerEvents = 'all';
                btn.style.opacity = '1';
            }
        });
    }

    // --- Multi-Step Contact Modal ---
    const contactModal = document.getElementById('contact-modal');
    const contactModalClose = document.getElementById('contact-modal-close');
    const msForm = document.getElementById('multi-step-form');
    const steps = msForm ? msForm.querySelectorAll('.step') : [];
    const progressBar = document.getElementById('form-progress');
    let currentStep = 1;

    const updateStep = (stepNum) => {
        steps.forEach(step => {
            step.classList.remove('active');
            if (parseInt(step.dataset.step) === stepNum) {
                step.classList.add('active');
            }
        });
        if (progressBar) {
            const progress = (stepNum / steps.length) * 100;
            progressBar.style.width = `${progress}%`;
        }
        currentStep = stepNum;
    };

    const openContactModal = (e) => {
        if (e) e.preventDefault();
        if (contactModal) {
            contactModal.classList.add('active');
            lenis.stop();
            updateStep(1);
        }
    };

    const closeContactModal = () => {
        if (contactModal) {
            contactModal.classList.remove('active');
            lenis.start();
        }
    };

    document.querySelectorAll('.contact-trigger').forEach(btn => {
        console.log('btn');
        btn.addEventListener('click', openContactModal);
    });

    if (contactModalClose) contactModalClose.addEventListener('click', closeContactModal);

    if (msForm) {
        msForm.querySelectorAll('.next-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                // Basic validation for current step
                const currentStepEl = msForm.querySelector(`.step[data-step="${currentStep}"]`);
                const inputs = currentStepEl.querySelectorAll('input[required]');
                let valid = true;
                inputs.forEach(input => {
                    if (!input.value) {
                        input.classList.add('invalid');
                        valid = false;
                    } else {
                        input.classList.remove('invalid');
                    }
                });

                if (valid && currentStep < steps.length) {
                    updateStep(currentStep + 1);
                }
            });
        });

        msForm.querySelectorAll('.back-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                if (currentStep > 1) {
                    updateStep(currentStep - 1);
                }
            });
        });

        // Dynamic Category Selection logic
        const mainCatItems = msForm.querySelectorAll('.main-cat-item');
        const subOptionGroups = msForm.querySelectorAll('.sub-options-group');
        const catItems = msForm.querySelectorAll('.cat-item');
        const sourceInput = document.getElementById('ms-source');
        const subPlaceholder = document.getElementById('sub-placeholder');

        mainCatItems.forEach(mainCat => {
            mainCat.addEventListener('click', () => {
                const category = mainCat.dataset.category;

                // Toggle active state for main buttons
                mainCatItems.forEach(i => i.classList.remove('active'));
                mainCat.classList.add('active');

                // Toggle visibility of sub-groups
                subOptionGroups.forEach(group => {
                    group.classList.remove('active');
                    if (group.dataset.parent === category) {
                        group.classList.add('active');
                    }
                });

                if (subPlaceholder) subPlaceholder.style.display = 'none';

                // Reset source input and sub-selections when parent changes
                if (sourceInput) sourceInput.value = `Category: ${category}`;
                catItems.forEach(i => i.classList.remove('selected'));
            });
        });

        catItems.forEach(item => {
            item.addEventListener('click', () => {
                const group = item.closest('.sub-options-group');
                const customField = group.querySelector('.custom-detail-field');

                catItems.forEach(i => {
                    // Only remove selection from items in the same group or globally? 
                    // Usually globally if only one source is allowed.
                    i.classList.remove('selected');
                });
                item.classList.add('selected');

                // Show custom field if 'Others' is selected, otherwise hide (if not permanently active)
                if (customField) {
                    if (item.dataset.value === 'Others') {
                        customField.classList.add('active');
                    } else if (group.dataset.parent !== 'others' && group.dataset.parent !== 'referrals') {
                        // Keep it active for 'others' and 'referrals' categories as they are custom-only
                        customField.classList.remove('active');
                    }
                }

                const parentCatName = group.dataset.parent;
                if (sourceInput) sourceInput.value = `${parentCatName} > ${item.dataset.value}`;
            });
        });

        msForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = msForm.querySelector('.submit-btn');
            const originalContent = btn.innerHTML;

            const formData = new FormData();
            formData.append('name', document.getElementById('ms-name').value);
            formData.append('email', document.getElementById('ms-email').value);
            formData.append('phone', document.getElementById('ms-phone').value);
            formData.append('message', document.getElementById('ms-project').value);
            formData.append('service', document.getElementById('ms-source').value); // Using source as service context

            btn.innerHTML = 'PROCESSING...';
            btn.style.pointerEvents = 'none';

            try {
                const response = await fetch('controller/clients/submit.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.status === 'success') {
                    showFeedback('Project Initiated', 'Inquiry received! Our team will review your project details and get back to you within 24 hours.');
                    closeContactModal();
                    msForm.reset();
                    catItems.forEach(i => i.classList.remove('selected'));
                    mainCatItems.forEach(i => i.classList.remove('active'));
                    subOptionGroups.forEach(i => i.classList.remove('active'));
                    if (subPlaceholder) subPlaceholder.style.display = 'block';
                    updateStep(1);
                } else {
                    showFeedback('Capture Error', result.message, 'error');
                }
            } catch (error) {
                showFeedback('Network Disruption', 'An error occurred. Please check your connection and try again.', 'error');
            } finally {
                btn.innerHTML = originalContent;
                btn.style.pointerEvents = 'all';
            }
        });
    }

    // --- High-End 3D Parallax Image Interaction ---
    const tiltContainers = document.querySelectorAll('.img-reveal-wrapper, .project-img');

    tiltContainers.forEach(container => {
        const img = container.querySelector('img');
        if (!img) return;

        container.addEventListener('mousemove', (e) => {
            const rect = container.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            // Normalize position -1 to 1
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            const deltaX = (x - centerX) / centerX;
            const deltaY = (y - centerY) / centerY;

            // Tilt & Parallax using GSAP for buttery smooth easing
            gsap.to(img, {
                duration: 0.6,
                x: deltaX * 30, // Parallax slide
                y: deltaY * 30,
                rotateY: deltaX * 12, // Subtle 3D tilt
                rotateX: -deltaY * 12,
                scale: 1.15,
                filter: 'grayscale(0) brightness(1)',
                ease: "power2.out",
                overwrite: true
            });
        });

        container.addEventListener('mouseleave', () => {
            gsap.to(img, {
                duration: 0.8,
                x: 0,
                y: 0,
                rotateX: 0,
                rotateY: 0,
                scale: 1,
                filter: 'grayscale(1) brightness(0.8)',
                ease: "power3.out",
                overwrite: true
            });
        });

    });

    // --- Payment Info Modal ---

    const paymentModal = document.getElementById('payment-modal');
    const paymentTeaser = document.querySelector('.payment-flex-teaser');
    const paymentModalClose = document.getElementById('payment-modal-close');

    if (paymentTeaser && paymentModal) {
        paymentTeaser.addEventListener('click', () => {
            paymentModal.classList.add('active');
            lenis.stop();
        });
    }


    if (paymentModalClose) {
        paymentModalClose.addEventListener('click', () => {
            paymentModal.classList.remove('active');
            lenis.start();
        });
    }

    // Close on click outside (extends the existing window click listener)
    window.addEventListener('click', (e) => {
        if (e.target === contactModal) closeContactModal();
        if (e.target === paymentModal) {
            paymentModal.classList.remove('active');
            lenis.start();
        }
    });

    // Handle Escape key
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeContactModal();
            if (paymentModal) {
                paymentModal.classList.remove('active');
                lenis.start();
            }
        }
    });

    // --- Dashboard Chart Initialization ---
    const chartCanvas = document.getElementById('dashboardChart');
    if (chartCanvas) {
        const ctx = chartCanvas.getContext('2d');

        // Gradient for Chart
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(255, 95, 31, 0.4)'); // --accent color
        gradient.addColorStop(1, 'rgba(255, 95, 31, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    label: 'Website Visits',
                    data: [1200, 1900, 1500, 2200, 3000, 2800, 3800],
                    borderColor: '#FF5F1F',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#FF5F1F',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#000',
                        titleColor: '#E5E3D3',
                        bodyColor: '#E5E3D3',
                        padding: 12,
                        cornerRadius: 12,
                        displayColors: false,
                        callbacks: {
                            label: function (context) {
                                return `Visits: ${context.parsed.y}`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                family: "'Inter', sans-serif",
                                size: 12
                            },
                            color: 'rgba(0,0,0,0.5)'
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(0,0,0,0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                family: "'Inter', sans-serif",
                                size: 12
                            },
                            color: 'rgba(0,0,0,0.5)',
                            stepSize: 1000
                        }
                    }
                }
            }
        });
    }

    // --- Cookie Consent Logic ---
    const cookieConsent = document.getElementById('cookie-consent');
    const acceptBtn = document.getElementById('cookie-accept');
    const rejectBtn = document.getElementById('cookie-reject');

    if (cookieConsent && !localStorage.getItem('beetle_cookies')) {
        // Show after a short delay for premium feel
        setTimeout(() => {
            cookieConsent.classList.add('active');
        }, 3000);
    }

    const saveChoice = (choice) => {
        localStorage.setItem('beetle_cookies', choice);
        cookieConsent.classList.remove('active');
    };

    if (acceptBtn) acceptBtn.addEventListener('click', () => saveChoice('accepted'));
    if (rejectBtn) rejectBtn.addEventListener('click', () => saveChoice('rejected'));

    // --- Footer Client Form Logic ---
    const footerClientForm = document.getElementById('footerClientForm');
    if (footerClientForm) {
        footerClientForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            const originalText = btn.innerText;
            btn.innerText = 'TRANSMITTING...';
            btn.style.opacity = '0.7';

            const formData = new FormData(this);

            fetch('controller/clients/submit.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    btn.innerText = 'LINK ESTABLISHED';
                    btn.style.background = '#28a745';
                    btn.style.color = '#fff';
                    this.reset();
                    setTimeout(() => {
                        showFeedback('Client Identity registered successfully.', 'success');
                        btn.innerText = originalText;
                        btn.style.opacity = '1';
                        btn.style.background = '';
                    }, 2000);
                } else {
                    btn.innerText = 'TRANSMISSION FAILED';
                    btn.style.background = '#ff3b30';
                    setTimeout(() => {
                        btn.innerText = originalText;
                        btn.style.opacity = '1';
                        btn.style.background = '';
                        showFeedback(data.message || 'Error occurred', 'error');
                    }, 2000);
                }
            })
            .catch(err => {
                console.error(err);
                btn.innerText = 'ERROR';
                setTimeout(() => {
                    btn.innerText = originalText;
                    btn.style.opacity = '1';
                }, 2000);
            });
        });
    }

});
