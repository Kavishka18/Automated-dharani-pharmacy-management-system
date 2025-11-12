<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dharani Pharmacy | Gampaha's Premier Healthcare</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #667eea;
            --primary-dark: #5a6fd8;
            --secondary: #764ba2;
            --accent: #38ef7d;
            --accent-dark: #11998e;
            --text: #2d3748;
            --text-light: #718096;
            --white: #ffffff;
            --bg-light: #f7fafc;
            --gradient: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            --gradient-accent: linear-gradient(45deg, var(--accent), var(--accent-dark));
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 25px 60px rgba(0, 0, 0, 0.15);
            --radius: 24px;
            --radius-lg: 32px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text);
            background-color: var(--bg-light);
            overflow-x: hidden;
            line-height: 1.6;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            line-height: 1.2;
        }

        .container {
            width: 100%;
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
        }

        section {
            padding: 100px 0;
            position: relative;
        }

        /* Navigation */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            padding: 20px 0;
            transition: all 0.4s ease;
        }

        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: var(--shadow);
            padding: 15px 0;
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .logo-icon {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            background: var(--gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 22px;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
        }

        .logo:hover .logo-icon {
            transform: rotate(15deg) scale(1.1);
        }

        .logo-text {
            font-size: 24px;
            font-weight: 800;
            color: var(--white);
        }

        .navbar.scrolled .logo-text {
            color: var(--text);
        }

        .nav-links {
            display: flex;
            gap: 8px;
        }

        .nav-link {
            padding: 12px 24px;
            border-radius: 50px;
            text-decoration: none;
            color: var(--white);
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .navbar.scrolled .nav-link {
            color: var(--text);
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--white);
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: 50px;
        }

        .nav-link:hover::before {
            opacity: 0.1;
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
        }

        .navbar.scrolled .nav-link.active {
            background: rgba(102, 126, 234, 0.1);
            color: var(--primary);
        }

        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--white);
            font-size: 24px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .mobile-toggle:hover {
            transform: scale(1.1);
        }

        .navbar.scrolled .mobile-toggle {
            color: var(--text);
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: var(--gradient);
            position: relative;
            overflow: hidden;
            padding-top: 80px;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000" opacity="0.05"><polygon fill="white" points="0,1000 1000,0 1000,1000"/></svg>');
            background-size: cover;
        }

        .hero-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }

        .hero-text {
            color: var(--white);
            z-index: 2;
        }

        .hero-title {
            font-size: clamp(3rem, 5vw, 5.5rem);
            margin-bottom: 24px;
            line-height: 1.1;
        }

        .hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: 32px;
            opacity: 0.9;
        }

        .hero-description {
            font-size: 1.125rem;
            margin-bottom: 40px;
            opacity: 0.85;
            max-width: 500px;
        }

        .hero-buttons {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 16px 32px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: var(--accent);
            color: var(--text);
            box-shadow: 0 10px 30px rgba(56, 239, 125, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(56, 239, 125, 0.6);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            color: var(--white);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-5px);
        }

        .hero-visual {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .floating-card {
            width: 320px;
            height: 400px;
            border-radius: var(--radius-lg);
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: var(--shadow-lg);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px;
            text-align: center;
            color: var(--white);
            position: relative;
            z-index: 2;
            animation: float 6s ease-in-out infinite;
            transition: all 0.3s ease;
        }

        .floating-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 35px 70px rgba(0, 0, 0, 0.3);
        }

        .floating-card i {
            font-size: 64px;
            margin-bottom: 24px;
            color: var(--accent);
            transition: all 0.3s ease;
        }

        .floating-card:hover i {
            transform: scale(1.2) rotate(10deg);
        }

        .floating-card h3 {
            font-size: 24px;
            margin-bottom: 16px;
        }

        .floating-card p {
            opacity: 0.9;
        }

        .floating-pills {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .pill {
            position: absolute;
            font-size: 32px;
            color: rgba(255, 255, 255, 0.3);
            animation: float-pill 8s ease-in-out infinite;
            transition: all 0.3s ease;
        }

        .pill:hover {
            color: var(--accent);
            transform: scale(1.3);
        }

        .pill:nth-child(1) {
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .pill:nth-child(2) {
            top: 20%;
            right: 15%;
            animation-delay: 1s;
        }

        .pill:nth-child(3) {
            bottom: 15%;
            left: 20%;
            animation-delay: 2s;
        }

        .pill:nth-child(4) {
            bottom: 25%;
            right: 10%;
            animation-delay: 3s;
        }

        /* About Section */
        .about {
            background: var(--white);
            position: relative;
            overflow: hidden;
        }

        .about::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: var(--gradient);
            border-radius: 50%;
            opacity: 0.03;
            animation: rotate 20s linear infinite;
        }

        .section-header {
            text-align: center;
            margin-bottom: 80px;
            position: relative;
            z-index: 2;
        }

        .section-title {
            font-size: clamp(2.5rem, 4vw, 3.5rem);
            margin-bottom: 16px;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
            display: inline-block;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--gradient);
            border-radius: 2px;
        }

        .section-subtitle {
            font-size: 1.25rem;
            color: var(--text-light);
            max-width: 600px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .feature-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 40px 30px;
            text-align: center;
            box-shadow: var(--shadow);
            transition: all 0.4s ease;
            border: 1px solid rgba(102, 126, 234, 0.1);
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--gradient);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s ease;
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            background: var(--gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            color: var(--white);
            font-size: 32px;
            transition: all 0.3s ease;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .feature-title {
            font-size: 1.5rem;
            margin-bottom: 16px;
        }

        .feature-description {
            color: var(--text-light);
        }

        /* Stats Section */
        .stats {
            background: var(--gradient);
            color: var(--white);
            position: relative;
            overflow: hidden;
        }

        .stats::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000" opacity="0.05"><circle fill="white" cx="500" cy="500" r="400"/></svg>');
            background-size: cover;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            text-align: center;
        }

        .stat-item {
            z-index: 2;
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            transform: translateY(-5px);
        }

        .stat-number {
            font-size: 4rem;
            font-weight: 800;
            margin-bottom: 16px;
            background: var(--gradient-accent);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .stat-label {
            font-size: 1.25rem;
            opacity: 0.9;
        }

        /* Services Section */
        .services {
            background: var(--bg-light);
            position: relative;
        }

        .services::before {
            content: '';
            position: absolute;
            bottom: -50%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: var(--gradient);
            border-radius: 50%;
            opacity: 0.03;
            animation: rotate 25s linear infinite reverse;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            position: relative;
            z-index: 2;
        }

        .service-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 40px 30px;
            box-shadow: var(--shadow);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(102, 126, 234, 0.1);
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--gradient);
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: 0;
        }

        .service-card:hover::before {
            opacity: 0.05;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
        }

        .service-icon {
            width: 70px;
            height: 70px;
            border-radius: 18px;
            background: var(--gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            color: var(--white);
            font-size: 28px;
            position: relative;
            z-index: 1;
            transition: all 0.3s ease;
        }

        .service-card:hover .service-icon {
            transform: scale(1.1) rotate(-5deg);
        }

        .service-title {
            font-size: 1.5rem;
            margin-bottom: 16px;
            position: relative;
            z-index: 1;
        }

        .service-description {
            color: var(--text-light);
            position: relative;
            z-index: 1;
        }

        /* Enhanced Contact Info Section */
        .contact-info-section {
            background: var(--white);
            position: relative;
            overflow: hidden;
        }

        .contact-info-section::before {
            content: '';
            position: absolute;
            top: -20%;
            right: -5%;
            width: 300px;
            height: 300px;
            background: var(--gradient);
            border-radius: 50%;
            opacity: 0.03;
            animation: pulse 4s ease-in-out infinite;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            position: relative;
            z-index: 2;
        }

        .contact-card {
            background: var(--bg-light);
            border-radius: var(--radius);
            padding: 40px 30px;
            text-align: center;
            box-shadow: var(--shadow);
            transition: all 0.4s ease;
            border: 1px solid rgba(102, 126, 234, 0.1);
            position: relative;
            overflow: hidden;
        }

        .contact-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--gradient);
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: 0;
        }

        .contact-card:hover::before {
            opacity: 0.03;
        }

        .contact-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
        }

        .contact-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            background: var(--gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            color: var(--white);
            font-size: 32px;
            position: relative;
            z-index: 1;
            transition: all 0.3s ease;
        }

        .contact-card:hover .contact-icon {
            transform: scale(1.1);
        }

        .contact-title {
            font-size: 1.5rem;
            margin-bottom: 16px;
            position: relative;
            z-index: 1;
        }

        .contact-details {
            color: var(--text-light);
            position: relative;
            z-index: 1;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 16px;
            margin-top: 20px;
        }

        .social-link {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            background: var(--bg-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text);
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid rgba(102, 126, 234, 0.1);
        }

        .social-link:hover {
            background: var(--gradient);
            color: var(--white);
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        /* Footer */
        .footer {
            background: var(--text);
            color: var(--white);
            padding: 60px 0 30px;
            position: relative;
            overflow: hidden;
        }

        .footer::before {
            content: '';
            position: absolute;
            bottom: -20%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: var(--gradient);
            border-radius: 50%;
            opacity: 0.03;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 40px;
            margin-bottom: 50px;
            position: relative;
            z-index: 2;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .footer-logo .logo-icon {
            width: 40px;
            height: 40px;
            font-size: 18px;
        }

        .footer-logo .logo-text {
            font-size: 20px;
        }

        .footer-description {
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 30px;
            max-width: 300px;
        }

        .footer-heading {
            font-size: 1.25rem;
            margin-bottom: 24px;
        }

        .footer-links {
            list-style: none;
        }

        .footer-link {
            margin-bottom: 12px;
        }

        .footer-link a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-link a:hover {
            color: var(--accent);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 30px;
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            position: relative;
            z-index: 2;
        }

        /* Animations */
        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        @keyframes float-pill {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-30px) rotate(10deg);
            }
        }

        @keyframes rotate {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }

        /* Responsive */
        @media (max-width: 992px) {
            .hero-content {
                grid-template-columns: 1fr;
                gap: 40px;
                text-align: center;
            }

            .contact-grid {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr;
                gap: 40px;
            }
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(20px);
                flex-direction: column;
                padding: 20px;
                box-shadow: var(--shadow);
            }

            .nav-links.active {
                display: flex;
            }

            .navbar.scrolled .nav-links {
                background: rgba(255, 255, 255, 0.95);
            }

            .nav-link {
                color: var(--text);
                margin: 5px 0;
            }

            .mobile-toggle {
                display: block;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            section {
                padding: 80px 0;
            }
        }

        @media (max-width: 576px) {
            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .floating-card {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container nav-container">
            <a href="#" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-stethoscope"></i>
                </div>
                <div class="logo-text">Dharani PMS</div>
            </a>
            
            <div class="nav-links">
                <a href="#" class="nav-link active">Home</a>
                <a href="pharmacist/index.php" class="nav-link">Pharmacist</a>
                <a href="admin/index.php" class="nav-link">Admin</a>
                <a href="customer/index.php" class="nav-link">Customer</a>
                <a href="InsuranceProvider/index.php" class="nav-link">Insurance</a>
            </div>
            
            <button class="mobile-toggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container hero-content">
            <div class="hero-text">
                <h1 class="hero-title">
                    Dharani<br>
                    <span style="color: #38ef7d;">Pharmacy</span>
                </h1>
                <p class="hero-subtitle">Gampaha's Newest & Most Trusted</p>
                <p class="hero-description">
                    <strong>Established in 2024</strong> – One modern pharmacy serving <span style="font-weight: 700; color: #38ef7d;">1,250+ customers</span> with care, precision, and 24/7 availability.
                </p>
                <div class="hero-buttons">
                    <a href="#about" class="btn btn-primary">
                        <i class="fas fa-book-open"></i>
                        Our Story
                    </a>
                    <a href="https://share.google/BqMnMJfTJbgFDWPK0" class="btn btn-secondary">
                        <i class="fas fa-map-marker-alt"></i>
                        Visit Us
                    </a>
                </div>
            </div>
            
            <div class="hero-visual">
                <div class="floating-card">
                    <i class="fas fa-clinic-medical"></i>
                    <h3>Modern. Clean. Open 24/7</h3>
                    <p>State-of-the-art pharmacy in Gampaha</p>
                </div>
                
                <div class="floating-pills">
                    <div class="pill"><i class="fas fa-capsules"></i></div>
                    <div class="pill"><i class="fas fa-tablets"></i></div>
                    <div class="pill"><i class="fas fa-prescription-bottle-alt"></i></div>
                    <div class="pill"><i class="fas fa-pills"></i></div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="about">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">About Dharani Pharmacy</h2>
                <p class="section-subtitle">
                    Established in <span style="font-weight: 700; color: #667eea;">2024</span> in the heart of Gampaha, Dharani is the newest and most modern pharmacy in the region, built to serve with excellence.
                </p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3 class="feature-title">1 Pharmacy</h3>
                    <p class="feature-description">State-of-the-art location in Gampaha</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3 class="feature-title">1+ Year of Service</h3>
                    <p class="feature-description">Launched in 2024 with full commitment</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="feature-title">24/7 Open</h3>
                    <p class="feature-description">Always here for emergencies</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number" id="patients">0</div>
                    <div class="stat-label">Registered Customers</div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-number" id="prescriptions">0</div>
                    <div class="stat-label">Prescriptions Filled</div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-number" id="staff">0</div>
                    <div class="stat-label">Qualified Staff</div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-number">4.9</div>
                    <div class="stat-label">Customer Rating</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Our Services</h2>
                <p class="section-subtitle">
                    We provide comprehensive pharmaceutical services with cutting-edge technology and expert care.
                </p>
            </div>
            
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-prescription"></i>
                    </div>
                    <h3 class="service-title">Automated Inventory Analysis</h3>
                    <p class="service-description">Advanced systems to manage and optimize medication stock levels.</p>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <h3 class="service-title">Best Pharmacist Advice</h3>
                    <p class="service-description">Expert consultations from qualified pharmacists for all your needs.</p>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h3 class="service-title">AI Prescription Validation</h3>
                    <p class="service-description">Smart systems to ensure prescription accuracy and safety.</p>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="service-title">Insurance Support</h3>
                    <p class="service-description">Seamless processing of insurance claims and coverage verification.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced Contact Info Section -->
    <section class="contact-info-section" id="contact">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Visit Dharani Pharmacy</h2>
                <p class="section-subtitle">
                    Get in touch with us for all your pharmaceutical needs
                </p>
            </div>
            
            <div class="contact-grid">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3 class="contact-title">Our Location</h3>
                    <p class="contact-details">No. 45, Oruthota Road, Gampaha</p>
                </div>
                
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h3 class="contact-title">Phone Number</h3>
                    <p class="contact-details">+94 33 222 2024</p>
                </div>
                
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3 class="contact-title">Email Address</h3>
                    <p class="contact-details">dharani.pharmacy@gmail.com</p>
                </div>
                
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="contact-title">Opening Hours</h3>
                    <p class="contact-details">Open 24 Hours</p>
                    <!-- <div class="social-links">
                        <a href="#" class="social-link">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div> -->
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-about">
                    <div class="footer-logo">
                        <div class="logo-icon">
                            <i class="fas fa-stethoscope"></i>
                        </div>
                        <div class="logo-text">Dharani PMS</div>
                    </div>
                    <p class="footer-description">
                        Gampaha's newest trusted pharmacy, established in 2024. Serving the community with care, precision, and 24/7 availability.
                    </p>
                </div>
                
                <div class="footer-links-column">
                    <h3 class="footer-heading">Quick Links</h3>
                    <ul class="footer-links">
                        <li class="footer-link"><a href="#">Home</a></li>
                        <li class="footer-link"><a href="pharmacist/index.php">Pharmacist Portal</a></li>
                        <li class="footer-link"><a href="admin/index.php">Admin Portal</a></li>
                        <li class="footer-link"><a href="customer/index.php">Customer Portal</a></li>
                    </ul>
                </div>
                
                <div class="footer-links-column">
                    <h3 class="footer-heading">Services</h3>
                    <ul class="footer-links">
                        <li class="footer-link"><a>Prescription Filling</a></li>
                        <li class="footer-link"><a>Health Consultations</a></li>
                        <li class="footer-link"><a>Medical Supplies</a></li>
                        <li class="footer-link"><a>Insurance Processing</a></li>
                    </ul>
                </div>
                
                <div class="footer-links-column">
                    <h3 class="footer-heading">Contact Info</h3>
                    <ul class="footer-links">
                        <li class="footer-link"><a>No. 45, Oruthota Road, Gampaha</a></li>
                        <li class="footer-link"><a>+94 33 222 2024</a></li>
                        <li class="footer-link"><a>dharani.pharmacy@gmail.com</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2025 Dharani Pharmacy. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Counter animation
        const counters = [
            { id: 'patients', target: 1250 },
            { id: 'prescriptions', target: 4850 },
            { id: 'staff', target: 12 }
        ];

        const startCounter = (id, target) => {
            let count = 0;
            const increment = target / 120;
            const timer = setInterval(() => {
                count += increment;
                if (count >= target) {
                    document.getElementById(id).textContent = target.toLocaleString();
                    clearInterval(timer);
                } else {
                    document.getElementById(id).textContent = Math.floor(count).toLocaleString();
                }
            }, 15);
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    counters.forEach(c => startCounter(c.id, c.target));
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.6 });

        observer.observe(document.querySelector('.stats'));

        // Mobile menu toggle
        document.querySelector('.mobile-toggle').addEventListener('click', function() {
            const navLinks = document.querySelector('.nav-links');
            navLinks.classList.toggle('active');
        });
    </script>
</body>
</html>