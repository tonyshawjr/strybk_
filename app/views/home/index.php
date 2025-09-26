<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Strybk - Write Beautiful Books Online</title>
    
    <!-- Core CSS -->
    <link rel="stylesheet" href="/css/app.css">
    
    <!-- Homepage styles -->
    <style>
        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--indigo) 0%, var(--purple) 100%);
            color: var(--white);
            padding: var(--space-24) 0;
            text-align: center;
        }
        
        .hero h1 {
            font-size: var(--text-5xl);
            margin-bottom: var(--space-4);
            color: var(--white);
        }
        
        .hero p {
            font-size: var(--text-xl);
            opacity: 0.9;
            margin-bottom: var(--space-8);
            color: var(--white);
        }
        
        .hero-buttons {
            display: flex;
            gap: var(--space-4);
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-hero {
            padding: var(--space-4) var(--space-8);
            font-size: var(--text-lg);
        }
        
        .btn-light {
            background: var(--white);
            color: var(--purple);
        }
        
        .btn-light:hover {
            background: var(--gray-100);
            color: var(--purple-dark);
        }
        
        /* Features Section */
        .features {
            padding: var(--space-16) 0;
            background: var(--white);
        }
        
        .features h2 {
            text-align: center;
            font-size: var(--text-3xl);
            margin-bottom: var(--space-12);
        }
        
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: var(--space-8);
        }
        
        .feature-card {
            text-align: center;
            padding: var(--space-6);
        }
        
        .feature-icon {
            font-size: var(--text-4xl);
            margin-bottom: var(--space-4);
        }
        
        .feature-card h3 {
            font-size: var(--text-xl);
            margin-bottom: var(--space-3);
        }
        
        .feature-card p {
            color: var(--gray-600);
            line-height: var(--leading-relaxed);
        }
        
        /* CTA Section */
        .cta {
            background: var(--gray-50);
            padding: var(--space-16) 0;
            text-align: center;
        }
        
        .cta h2 {
            font-size: var(--text-3xl);
            margin-bottom: var(--space-4);
        }
        
        .cta p {
            font-size: var(--text-lg);
            color: var(--gray-600);
            margin-bottom: var(--space-8);
        }
        
        /* Footer */
        .footer {
            background: var(--gray-900);
            color: var(--gray-400);
            padding: var(--space-8) 0;
            text-align: center;
        }
        
        .footer a {
            color: var(--lime);
        }
        
        .footer a:hover {
            color: var(--lime-dark);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: var(--text-3xl);
            }
            
            .hero p {
                font-size: var(--text-base);
            }
            
            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn-hero {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>📚 Strybk_</h1>
            <p>Write beautiful books online with the simplest publishing platform</p>
            <div class="hero-buttons">
                <a href="/login" class="btn btn-light btn-hero">Get Started</a>
                <a href="#features" class="btn btn-ghost btn-hero" style="color: white; border: 2px solid white;">Learn More</a>
            </div>
        </div>
    </section>
    
    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <h2>Everything You Need to Write</h2>
            <div class="feature-grid">
                <div class="feature-card">
                    <div class="feature-icon">✍️</div>
                    <h3>Markdown Editor</h3>
                    <p>Write with a clean, distraction-free Markdown editor that lets you focus on your content.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📖</div>
                    <h3>Beautiful Reading</h3>
                    <p>Your books look stunning with automatic formatting, typography, and responsive design.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3>Track Progress</h3>
                    <p>Monitor your writing with word counts, reading time estimates, and completion tracking.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🖼️</div>
                    <h3>Rich Media</h3>
                    <p>Add images, create picture pages, and design beautiful section dividers.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔒</div>
                    <h3>Privacy Control</h3>
                    <p>Keep books private for yourself or share them publicly with a simple toggle.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">⚡</div>
                    <h3>Fast & Simple</h3>
                    <p>No complex setup. Start writing immediately with our intuitive interface.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <h2>Start Writing Your Story Today</h2>
            <p>Join writers who value simplicity and beauty in their publishing journey.</p>
            <a href="/login" class="btn btn-primary btn-lg">Create Your First Book</a>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> Strybk. Made with ❤️ for writers.</p>
            <p class="mt-2">
                <a href="/login">Sign In</a> · 
                <a href="#features">Features</a> · 
                <a href="https://github.com/tonyshawjr/strybk_">GitHub</a>
            </p>
        </div>
    </footer>
    
    <!-- Smooth scroll for anchor links -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>