document.addEventListener('DOMContentLoaded', () => {
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
    let scrollY = 0;

    const openMenu = () => {
        scrollY = window.scrollY;
        navToggle.classList.add('active');
        mobileMenu.classList.add('active');
        document.body.style.top = `-${scrollY}px`;
        document.body.classList.add('menu-open');
    };

    const closeMenu = () => {
        navToggle.classList.remove('active');
        mobileMenu.classList.remove('active');
        document.body.classList.remove('menu-open');
        document.body.style.top = '';
        window.scrollTo(0, scrollY);
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

    // --- Custom Cursor ---
    document.addEventListener('mousemove', (e) => {
        const { clientX: x, clientY: y } = e;
        if (cursor) {
            cursor.style.left = `${x}px`;
            cursor.style.top = `${y}px`;
        }
        if (follower) {
            follower.style.left = `${x}px`;
            follower.style.top = `${y}px`;
        }
    });

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
                targetElement.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });

    // --- Back to Top ---
    if (backToTop) {
        backToTop.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
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
    window.addEventListener('load', () => {
        setTimeout(() => {
            loader.style.opacity = '0';
            setTimeout(() => {
                loader.style.display = 'none';
                document.body.classList.add('page-loaded');
                document.getElementById('hero').classList.add('reveal-active');
            }, 1000);
        }, 3000);
    });

    // --- Footer Accordion ---
    const accordionTriggers = document.querySelectorAll('.accordion-trigger');
    
    accordionTriggers.forEach(trigger => {
        trigger.addEventListener('click', function(e) {
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

    const goTo = (index) => {
        currentSlide = (index + TOTAL) % TOTAL;
        track.style.transform = `translateX(-${currentSlide * 100}%)`;
        dots.forEach(d => d.classList.remove('active'));
        dots[currentSlide].classList.add('active');
    };

    document.getElementById('t-prev').addEventListener('click', () => goTo(currentSlide - 1));
    document.getElementById('t-next').addEventListener('click', () => goTo(currentSlide + 1));
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

    // --- Form Handling ---
    const contactForm = document.getElementById('contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', (e) => {
            e.preventDefault();
            alert('Thank you! Your message has been sent to Beetle System.');
            contactForm.reset();
        });
    }
});
