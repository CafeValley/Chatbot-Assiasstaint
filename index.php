<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Chatbot Assistant - Teachable Conversational AI</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --ocean-deep: #0c1929;
            --ocean-dark: #0f2744;
            --ocean-mid: #1a4971;
            --ocean-light: #2d6a9f;
            --ocean-bright: #4a9eda;
            --teal-primary: #14b8a6;
            --teal-light: #5eead4;
            --aqua: #22d3ee;
            --foam: #e0f7fa;
            --white: #ffffff;
            --text-primary: #f0f9ff;
            --text-secondary: rgba(224, 247, 250, 0.7);
            --shadow-color: rgba(20, 184, 166, 0.3);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--ocean-deep);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Ocean wave background */
        .bg-ocean {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: 
                radial-gradient(ellipse at 30% 0%, rgba(74, 158, 218, 0.25) 0%, transparent 50%),
                radial-gradient(ellipse at 70% 100%, rgba(20, 184, 166, 0.2) 0%, transparent 50%),
                radial-gradient(ellipse at 100% 50%, rgba(34, 211, 238, 0.15) 0%, transparent 40%),
                linear-gradient(180deg, var(--ocean-deep) 0%, var(--ocean-dark) 50%, var(--ocean-mid) 100%);
        }

        /* Wave animation */
        .wave {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 200%;
            height: 200px;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%231a4971' fill-opacity='0.3' d='M0,160L48,176C96,192,192,224,288,213.3C384,203,480,149,576,138.7C672,128,768,160,864,186.7C960,213,1056,235,1152,218.7C1248,203,1344,149,1392,122.7L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3C/svg%3E") repeat-x;
            animation: wave 25s linear infinite;
            z-index: -1;
            opacity: 0.6;
        }

        .wave:nth-child(2) {
            bottom: 10px;
            animation: wave 20s linear reverse infinite;
            opacity: 0.4;
        }

        .wave:nth-child(3) {
            bottom: 20px;
            animation: wave 30s linear infinite;
            opacity: 0.2;
        }

        @keyframes wave {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        /* Floating bubbles */
        .bubble {
            position: fixed;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(20, 184, 166, 0.2), rgba(34, 211, 238, 0.1));
            border: 1px solid rgba(94, 234, 212, 0.2);
            z-index: -1;
            animation: float 15s ease-in-out infinite;
        }

        .bubble-1 { width: 300px; height: 300px; top: 10%; right: 10%; animation-delay: 0s; }
        .bubble-2 { width: 200px; height: 200px; top: 60%; left: 5%; animation-delay: -5s; }
        .bubble-3 { width: 150px; height: 150px; top: 30%; left: 20%; animation-delay: -10s; }
        .bubble-4 { width: 100px; height: 100px; bottom: 20%; right: 20%; animation-delay: -3s; }

        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-30px) scale(1.05); }
        }

        /* Navigation */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
            backdrop-filter: blur(20px);
            background: rgba(12, 25, 41, 0.7);
            border-bottom: 1px solid rgba(94, 234, 212, 0.1);
        }

        .logo {
            font-size: 1.6rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--teal-light), var(--aqua));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.5px;
        }

        .nav-links {
            display: flex;
            gap: 32px;
        }

        .nav-links a {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.3s;
            position: relative;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--teal-primary);
            transition: width 0.3s;
        }

        .nav-links a:hover {
            color: var(--teal-light);
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 120px 20px 80px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(20, 184, 166, 0.15);
            border: 1px solid rgba(20, 184, 166, 0.4);
            padding: 10px 20px;
            border-radius: 50px;
            font-size: 0.9rem;
            color: var(--teal-light);
            margin-bottom: 32px;
            animation: fadeInUp 0.8s ease;
        }

        .hero-badge svg {
            width: 18px;
            height: 18px;
            fill: currentColor;
        }

        h1 {
            font-size: clamp(2.8rem, 8vw, 5.5rem);
            font-weight: 800;
            line-height: 1.05;
            margin-bottom: 28px;
            animation: fadeInUp 0.8s ease 0.1s both;
            letter-spacing: -2px;
        }

        h1 .highlight {
            background: linear-gradient(135deg, var(--teal-primary), var(--aqua), var(--ocean-bright));
            background-size: 200% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmer 4s ease infinite;
        }

        @keyframes shimmer {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .hero-subtitle {
            font-size: clamp(1.1rem, 2.5vw, 1.5rem);
            color: var(--text-secondary);
            max-width: 650px;
            margin-bottom: 48px;
            line-height: 1.7;
            font-weight: 400;
            animation: fadeInUp 0.8s ease 0.2s both;
        }

        .hero-buttons {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
            animation: fadeInUp 0.8s ease 0.3s both;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 18px 36px;
            border-radius: 16px;
            font-size: 1.05rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            border: none;
        }

        .btn svg {
            width: 22px;
            height: 22px;
            fill: currentColor;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--teal-primary), var(--ocean-light));
            color: white;
            box-shadow: 0 8px 32px var(--shadow-color), 0 0 0 1px rgba(20, 184, 166, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 48px var(--shadow-color), 0 0 0 1px rgba(20, 184, 166, 0.5);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-primary);
            border: 1px solid rgba(94, 234, 212, 0.3);
            backdrop-filter: blur(10px);
        }

        .btn-secondary:hover {
            background: rgba(20, 184, 166, 0.15);
            border-color: var(--teal-primary);
            transform: translateY(-4px);
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Features Section */
        .features {
            padding: 120px 20px;
            max-width: 1300px;
            margin: 0 auto;
        }

        .section-header {
            text-align: center;
            margin-bottom: 80px;
        }

        .section-header h2 {
            font-size: clamp(2rem, 5vw, 3.2rem);
            font-weight: 700;
            margin-bottom: 20px;
            letter-spacing: -1px;
        }

        .section-header p {
            color: var(--text-secondary);
            font-size: 1.15rem;
            max-width: 500px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
            gap: 28px;
        }

        .feature-card {
            background: linear-gradient(135deg, rgba(26, 73, 113, 0.4), rgba(15, 39, 68, 0.6));
            border: 1px solid rgba(94, 234, 212, 0.15);
            border-radius: 24px;
            padding: 36px 32px;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--teal-primary), var(--aqua));
            opacity: 0;
            transition: opacity 0.4s;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            border-color: rgba(20, 184, 166, 0.4);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .feature-card:hover::before {
            opacity: 1;
        }

        .feature-icon {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 24px;
            background: linear-gradient(135deg, rgba(20, 184, 166, 0.2), rgba(34, 211, 238, 0.1));
            border: 1px solid rgba(94, 234, 212, 0.2);
        }

        .feature-card h3 {
            font-size: 1.35rem;
            font-weight: 700;
            margin-bottom: 14px;
            letter-spacing: -0.3px;
        }

        .feature-card p {
            color: var(--text-secondary);
            line-height: 1.7;
            font-size: 0.95rem;
        }

        /* Stats Section */
        .stats {
            padding: 100px 20px;
            background: linear-gradient(180deg, transparent, rgba(20, 184, 166, 0.05), transparent);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 48px;
            max-width: 1100px;
            margin: 0 auto;
            text-align: center;
        }

        .stat-item h3 {
            font-size: 3.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--teal-light), var(--aqua));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 12px;
            letter-spacing: -2px;
        }

        .stat-item p {
            color: var(--text-secondary);
            font-size: 1.1rem;
            font-weight: 500;
        }

        /* CTA Section */
        .cta {
            padding: 140px 20px;
            text-align: center;
        }

        .cta-box {
            max-width: 800px;
            margin: 0 auto;
            padding: 72px 48px;
            background: linear-gradient(135deg, rgba(20, 184, 166, 0.1), rgba(26, 73, 113, 0.3));
            border: 1px solid rgba(94, 234, 212, 0.2);
            border-radius: 40px;
            position: relative;
            overflow: hidden;
        }

        .cta-box::before {
            content: '';
            position: absolute;
            top: -100%;
            left: -100%;
            width: 300%;
            height: 300%;
            background: radial-gradient(circle, rgba(20, 184, 166, 0.08) 0%, transparent 40%);
            animation: rotateCTA 30s linear infinite;
        }

        @keyframes rotateCTA {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .cta-box h2 {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 20px;
            position: relative;
            letter-spacing: -1px;
        }

        .cta-box p {
            color: var(--text-secondary);
            font-size: 1.2rem;
            margin-bottom: 36px;
            position: relative;
        }

        .cta-box .btn {
            position: relative;
        }

        /* Footer */
        footer {
            padding: 48px 20px;
            text-align: center;
            color: var(--text-secondary);
            font-size: 0.95rem;
            border-top: 1px solid rgba(94, 234, 212, 0.1);
        }

        footer a {
            color: var(--teal-light);
            text-decoration: none;
            transition: color 0.3s;
        }

        footer a:hover {
            color: var(--aqua);
        }

        /* Responsive */
        @media (max-width: 768px) {
            nav { padding: 15px 20px; }
            .nav-links { display: none; }
            .hero-buttons { flex-direction: column; width: 100%; max-width: 320px; }
            .btn { width: 100%; justify-content: center; }
            .features-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="bg-ocean"></div>
    <div class="wave"></div>
    <div class="wave"></div>
    <div class="wave"></div>
    <div class="bubble bubble-1"></div>
    <div class="bubble bubble-2"></div>
    <div class="bubble bubble-3"></div>
    <div class="bubble bubble-4"></div>

    <nav>
        <div class="logo">AI Chatbot</div>
        <div class="nav-links">
            <a href="#features">Features</a>
            <a href="bot.php">Chat</a>
            <a href="admin.php">Admin</a>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-badge">
            <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
            Teachable AI Assistant
        </div>
        <h1>Your <span class="highlight">Intelligent</span><br>Conversation Partner</h1>
        <p class="hero-subtitle">
            A self-learning chatbot that gets smarter with every conversation. 
            Train it with your knowledge, and watch it grow.
        </p>
        <div class="hero-buttons">
            <a href="bot.php" class="btn btn-primary">
                <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/></svg>
                Start Chatting
            </a>
            <a href="admin.php" class="btn btn-secondary">
                <svg viewBox="0 0 24 24"><path d="M19.14 12.94c.04-.31.06-.63.06-.94 0-.31-.02-.63-.06-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.04.31-.06.63-.06.94s.02.63.06.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/></svg>
                Admin Panel
            </a>
        </div>
    </section>

    <section class="features" id="features">
        <div class="section-header">
            <h2>Powerful Features</h2>
            <p>Everything you need to build an intelligent assistant</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🧠</div>
                <h3>Intent-Based Learning</h3>
                <p>Group similar questions under intents. One question can have multiple ways of asking, all leading to the right answer.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🎯</div>
                <h3>Fuzzy Matching</h3>
                <p>Advanced algorithms find the best match even with typos, variations, or incomplete questions.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📚</div>
                <h3>Continuous Learning</h3>
                <p>Unknown questions are saved for review. Teach the bot new responses through the admin panel.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">⚡</div>
                <h3>Instant Responses</h3>
                <p>Lightning-fast database queries deliver answers in milliseconds, no external APIs needed.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🎨</div>
                <h3>Multiple Responses</h3>
                <p>Add variety with multiple response options per intent, with confidence weighting.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3>Analytics & Feedback</h3>
                <p>Track matched vs unmatched queries, collect user feedback, and improve over time.</p>
            </div>
        </div>
    </section>

    <section class="stats">
        <div class="stats-grid">
            <div class="stat-item">
                <h3>100%</h3>
                <p>Self-Hosted</p>
            </div>
            <div class="stat-item">
                <h3>0ms</h3>
                <p>API Latency</p>
            </div>
            <div class="stat-item">
                <h3>∞</h3>
                <p>Trainable Intents</p>
            </div>
            <div class="stat-item">
                <h3>24/7</h3>
                <p>Always Available</p>
            </div>
        </div>
    </section>

    <section class="cta">
        <div class="cta-box">
            <h2>Ready to Get Started?</h2>
            <p>Jump into the chat and see the AI in action, or head to the admin panel to start training.</p>
            <a href="bot.php" class="btn btn-primary">Launch Chatbot</a>
        </div>
    </section>

    <footer>
        <p>Built with PHP & MySQL • <a href="admin.php">Admin Access</a></p>
    </footer>

    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({ behavior: 'smooth' });
            });
        });

        document.addEventListener('mousemove', (e) => {
            const bubbles = document.querySelectorAll('.bubble');
            const x = e.clientX / window.innerWidth - 0.5;
            const y = e.clientY / window.innerHeight - 0.5;
            bubbles.forEach((bubble, i) => {
                const speed = (i + 1) * 15;
                bubble.style.transform = `translate(${x * speed}px, ${y * speed}px)`;
            });
        });
    </script>
</body>
</html>
