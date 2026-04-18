<?php 
require_once '../core/db.php';
include '../includes/pageheader.php'; 
?>

<body class="error-page">
    <div class="grain"></div>
    
    <main class="error-container">
        <div class="error-content">
            <span class="error-code">404</span>
            <span class="subheading"><span>COORDINATE MISMATCH</span></span>
            <h1>Lost in the Nebula.</h1>
            <p>The digital interface you're looking for has moved beyond the observable horizon or never existed in this dimension.</p>
            
            <div class="error-actions">
                <a href="home" class="btn-solid magnetic">RETURN TO BASE</a>
                <a href="contact" class="btn-outline">REPORT GLITCH</a>
            </div>
        </div>
        
        <!-- Decorative Background Elements -->
        <div class="error-glow"></div>
        <div class="glitch-orb"></div>
    </main>

    <style>
        .error-page {
            background: var(--bg-primary);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            text-align: center;
        }

        .error-container {
            position: relative;
            z-index: 10;
        }

        .error-code {
            font-family: var(--font-heading);
            font-size: clamp(8rem, 20vw, 15rem);
            font-weight: 900;
            line-height: .8;
            letter-spacing: -5px;
            background: linear-gradient(135deg, var(--text-primary) 30%, var(--accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: block;
            margin-bottom: 2rem;
            filter: drop-shadow(0 0 30px rgba(255, 95, 31, 0.2));
            animation: float 6s ease-in-out infinite;
        }

        .error-content h1 {
            font-family: var(--font-heading);
            font-size: clamp(2rem, 5vw, 4rem);
            margin: 1.5rem 0;
            color: var(--text-primary);
        }

        .error-content p {
            max-width: 500px;
            margin: 0 auto 3rem;
            opacity: 0.6;
            line-height: 1.6;
            font-size: 1.1rem;
        }

        .error-actions {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
        }

        .error-glow {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 60vw;
            height: 60vw;
            background: radial-gradient(circle, rgba(255, 95, 31, 0.05) 0%, transparent 70%);
            transform: translate(-50%, -50%);
            z-index: -1;
            pointer-events: none;
        }

        .glitch-orb {
            position: absolute;
            width: 300px;
            height: 300px;
            background: var(--accent);
            filter: blur(150px);
            opacity: 0.1;
            border-radius: 50%;
            z-index: -1;
            animation: moveOrb 20s linear infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        @keyframes moveOrb {
            0% { top: 10%; left: 10%; }
            25% { top: 50%; left: 80%; }
            50% { top: 80%; left: 30%; }
            75% { top: 30%; left: 60%; }
            100% { top: 10%; left: 10%; }
        }

        .magnetic { transition: transform 0.3s var(--transition); }
    </style>

    <script>
        // Use existing magnetic effect if main.js is loaded, otherwise simple follow
        document.querySelectorAll('.magnetic').forEach(btn => {
            btn.addEventListener('mousemove', (e) => {
                const rect = btn.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;
                btn.style.transform = `translate(${x * 0.3}px, ${y * 0.3}px)`;
            });
            btn.addEventListener('mouseleave', () => {
                btn.style.transform = `translate(0, 0)`;
            });
        });

        // Entrance Animation
        gsap.from(".error-code", { duration: 1.5, opacity: 0, scale: 0.8, y: 50, ease: "power4.out" });
        gsap.from(".error-content > *", { duration: 1, opacity: 0, y: 30, stagger: 0.2, ease: "power3.out", delay: 0.5 });
    </script>
</body>
</html>
