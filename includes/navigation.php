
    <nav id="navbar" aria-label="Main Navigation">
        <div class="logo">
            <a href="./" style="text-decoration: none; color: inherit;">BEETLE SYSTEM</a>
        </div>

        <div class="header-logo-center">
            <a href="admin" class="logo-circle-btn" aria-label="Administrative Login Portal">
                <svg viewBox="0 0 100 120" xmlns="http://www.w3.org/2000/svg">
                    <!-- Legs -->
                    <g class="btn-legs" stroke="black" stroke-width="2" fill="none">
                        <path d="M30 40 L15 30" />
                        <path d="M30 60 L10 60" />
                        <path d="M35 80 L20 100" />
                        <path d="M70 40 L85 30" />
                        <path d="M70 60 L90 60" />
                        <path d="M65 80 L80 100" />
                    </g>
                    <!-- Body/Head -->
                    <circle cx="50" cy="30" r="12" fill="black" />
                    <ellipse cx="50" cy="70" rx="20" ry="30" fill="black" opacity="0.5" />
                    <!-- Wings -->
                    <g class="btn-wings">
                        <path class="btn-wing-l" d="M50 40 C30 40 25 60 25 80 C25 100 40 110 50 110 Z" fill="black" />
                        <path class="btn-wing-r" d="M50 40 C70 40 75 60 75 80 C75 100 60 110 50 110 Z" fill="black" />
                    </g>
                    <!-- Antennas -->
                    <path class="btn-antennas" d="M45 20 C40 10 35 15 30 10 M55 20 C60 10 65 15 70 10" fill="none"
                        stroke="black" stroke-width="2" />
                </svg>
            </a>
        </div>

        <div class="nav-toggle" id="nav-toggle" aria-expanded="false" aria-controls="mobile-menu" aria-label="Toggle navigation menu">
            <span></span>
            <span></span>
        </div>
        
        <div class="nav-links">
            <a href="about">About</a>
            <a href="services">Services</a>
            <a href="projects">Projects</a>
            <a href="contact">Contact</a>
        </div>
        <div id="scroll-progress"></div>
    </nav>

    <div class="mobile-menu" id="mobile-menu">
        <button class="mobile-close" id="mobile-close" aria-label="Close menu">
            <span></span>
            <span></span>
        </button>
        <div class="mobile-links">
            <a href="./">Home</a>
            <a href="about">About</a>
            <a href="services">Services</a>
            <a href="projects">Projects</a>
            <a href="contact">Contact</a>
        </div>
    </div>