<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dharani Pharmacy | Gampaha's Premier Healthcare</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
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

        /* 3D Particle Background */
        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
        }

        /* Navigation - Ultra Modern Glass Effect */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            padding: 20px 0;
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(35px);
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.12);
            padding: 15px 0;
            transform: translateY(-5px);
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
            position: relative;
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
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .logo-icon::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: left 0.8s;
        }

        .logo:hover .logo-icon::before {
            left: 100%;
        }

        .logo:hover .logo-icon {
            transform: rotate(15deg) scale(1.1);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.6);
        }

        .logo-text {
            font-size: 24px;
            font-weight: 800;
            color: var(--white);
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .navbar.scrolled .logo-text {
            color: var(--text);
            text-shadow: none;
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
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .navbar.scrolled .nav-link {
            color: var(--text);
            background: rgba(102, 126, 234, 0.05);
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.6s;
        }

        .nav-link:hover::before {
            left: 100%;
        }

        .nav-link:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            background: rgba(255, 255, 255, 0.2);
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(15px);
            box-shadow: 0 8px 25px rgba(255, 255, 255, 0.2);
        }

        .navbar.scrolled .nav-link.active {
            background: var(--gradient);
            color: var(--white);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }

        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--white);
            font-size: 24px;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 8px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.1);
        }

        .mobile-toggle:hover {
            transform: scale(1.1) rotate(90deg);
            background: rgba(255, 255, 255, 0.2);
        }

        .navbar.scrolled .mobile-toggle {
            color: var(--text);
        }

        /* Hero Section - 3D Enhanced */
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
            background: 
                radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.2) 0%, transparent 50%);
            animation: float 6s ease-in-out infinite;
        }

        .hero-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .hero-text {
            color: var(--white);
        }

        .hero-title {
            font-size: clamp(3.5rem, 6vw, 6rem);
            margin-bottom: 24px;
            line-height: 1.1;
            text-shadow: 0 5px 25px rgba(0,0,0,0.3);
            animation: titleGlow 3s ease-in-out infinite alternate;
        }

        @keyframes titleGlow {
            0% {
                text-shadow: 0 5px 25px rgba(0,0,0,0.3);
            }
            100% {
                text-shadow: 0 5px 35px rgba(255,255,255,0.2);
            }
        }

        .hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: 32px;
            opacity: 0.9;
            font-weight: 300;
            letter-spacing: 1px;
        }

        .hero-description {
            font-size: 1.125rem;
            margin-bottom: 40px;
            opacity: 0.85;
            max-width: 500px;
            line-height: 1.7;
        }

        .hero-buttons {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 18px 36px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            cursor: pointer;
            font-size: 1.1rem;
            position: relative;
            overflow: hidden;
            transform-style: preserve-3d;
            perspective: 1000px;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: left 0.8s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: var(--accent);
            color: var(--text);
            box-shadow: 
                0 15px 35px rgba(56, 239, 125, 0.4),
                0 5px 15px rgba(56, 239, 125, 0.3);
            transform: translateZ(20px);
        }

        .btn-primary:hover {
            transform: translateY(-8px) translateZ(30px) scale(1.05);
            box-shadow: 
                0 25px 50px rgba(56, 239, 125, 0.6),
                0 15px 30px rgba(56, 239, 125, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            color: var(--white);
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 10px 30px rgba(255, 255, 255, 0.1);
            transform: translateZ(15px);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-8px) translateZ(25px) scale(1.05);
            box-shadow: 0 20px 40px rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.5);
        }

        .hero-visual {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            perspective: 1000px;
        }

        .floating-card {
            width: 360px;
            height: 450px;
            border-radius: var(--radius-lg);
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 
                0 25px 60px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255,255,255,0.2);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 50px;
            text-align: center;
            color: var(--white);
            position: relative;
            z-index: 2;
            animation: float3d 8s ease-in-out infinite;
            transform-style: preserve-3d;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes float3d {
            0%, 100% {
                transform: translateY(0px) rotateX(0deg) rotateY(0deg);
            }
            33% {
                transform: translateY(-20px) rotateX(5deg) rotateY(5deg);
            }
            66% {
                transform: translateY(-10px) rotateX(-5deg) rotateY(-5deg);
            }
        }

        .floating-card:hover {
            transform: translateY(-15px) rotateX(10deg) rotateY(10deg) scale(1.05);
            box-shadow: 
                0 40px 80px rgba(0, 0, 0, 0.4),
                inset 0 1px 0 rgba(255,255,255,0.3);
        }

        .floating-card i {
            font-size: 80px;
            margin-bottom: 30px;
            color: var(--accent);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            filter: drop-shadow(0 5px 15px rgba(56, 239, 125, 0.4));
        }

        .floating-card:hover i {
            transform: scale(1.3) rotate(15deg);
            filter: drop-shadow(0 10px 25px rgba(56, 239, 125, 0.6));
        }

        .floating-card h3 {
            font-size: 28px;
            margin-bottom: 20px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        .floating-card p {
            opacity: 0.9;
            font-size: 1.1rem;
            line-height: 1.6;
        }

        .floating-pills {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .pill {
            position: absolute;
            font-size: 40px;
            color: rgba(255, 255, 255, 0.4);
            animation: floatPill 10s ease-in-out infinite;
            transition: all 0.5s ease;
            filter: drop-shadow(0 5px 15px rgba(0,0,0,0.3));
            transform-style: preserve-3d;
        }

        @keyframes floatPill {
            0%, 100% {
                transform: translateY(0) rotate(0deg) translateZ(0);
            }
            25% {
                transform: translateY(-40px) rotate(90deg) translateZ(20px);
            }
            50% {
                transform: translateY(-20px) rotate(180deg) translateZ(10px);
            }
            75% {
                transform: translateY(-30px) rotate(270deg) translateZ(15px);
            }
        }

        .pill:hover {
            color: var(--accent);
            transform: scale(1.5) rotate(360deg) translateZ(30px);
            filter: drop-shadow(0 10px 25px rgba(56, 239, 125, 0.6));
        }

        .pill:nth-child(1) {
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }
        .pill:nth-child(2) {
            top: 20%;
            right: 15%;
            animation-delay: 2s;
        }
        .pill:nth-child(3) {
            bottom: 15%;
            left: 20%;
            animation-delay: 4s;
        }
        .pill:nth-child(4) {
            bottom: 25%;
            right: 10%;
            animation-delay: 6s;
        }
        .pill:nth-child(5) {
            top: 50%;
            left: 5%;
            animation-delay: 1s;
        }
        .pill:nth-child(6) {
            top: 60%;
            right: 5%;
            animation-delay: 3s;
        }

        /* Enhanced Sections with 3D Effects */
        .section-header {
            text-align: center;
            margin-bottom: 80px;
            position: relative;
        }

        .section-title {
            font-size: clamp(3rem, 5vw, 4.5rem);
            margin-bottom: 20px;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
            display: inline-block;
            transform-style: preserve-3d;
            animation: titleFloat 6s ease-in-out infinite;
        }

        @keyframes titleFloat {
            0%, 100% { transform: translateY(0) rotateX(0); }
            50% { transform: translateY(-10px) rotateX(5deg); }
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: var(--gradient);
            border-radius: 2px;
            animation: linePulse 2s ease-in-out infinite;
        }

        @keyframes linePulse {
            0%, 100% { width: 100px; opacity: 1; }
            50% { width: 120px; opacity: 0.8; }
        }

        .section-subtitle {
            font-size: 1.3rem;
            color: var(--text-light);
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.7;
        }

        /* Enhanced Cards with 3D Hover */
        .features-grid, .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 40px;
        }

        .feature-card, .service-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 50px 35px;
            text-align: center;
            box-shadow: var(--shadow);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(102, 126, 234, 0.1);
            position: relative;
            overflow: hidden;
            transform-style: preserve-3d;
            perspective: 1000px;
        }

        .feature-card::before, .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--gradient);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .feature-card:hover::before, .service-card:hover::before {
            transform: scaleX(1);
        }

        .feature-card:hover, .service-card:hover {
            transform: translateY(-15px) rotateX(5deg) rotateY(5deg) scale(1.03);
            box-shadow: 
                0 35px 70px rgba(0, 0, 0, 0.15),
                0 15px 35px rgba(102, 126, 234, 0.1);
        }

        .feature-icon, .service-icon {
            width: 90px;
            height: 90px;
            border-radius: 25px;
            background: var(--gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            color: var(--white);
            font-size: 36px;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.3);
            transform-style: preserve-3d;
        }

        .feature-card:hover .feature-icon, .service-card:hover .service-icon {
            transform: scale(1.15) rotate(10deg) translateZ(20px);
            box-shadow: 0 25px 50px rgba(102, 126, 234, 0.5);
        }

        .feature-title, .service-title {
            font-size: 1.6rem;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .feature-card:hover .feature-title, .service-card:hover .service-title {
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .feature-description, .service-description {
            color: var(--text-light);
            font-size: 1.1rem;
            line-height: 1.7;
        }

        /* Enhanced Contact Section */
        .contact-section {
            background: var(--white);
            position: relative;
            overflow: hidden;
        }

        .contact-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: var(--gradient);
            border-radius: 50%;
            opacity: 0.03;
            animation: rotate 20s linear infinite;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            position: relative;
            z-index: 2;
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 40px;
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            padding: 30px;
            background: var(--bg-light);
            border-radius: var(--radius);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(102, 126, 234, 0.1);
        }

        .contact-item:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: var(--shadow-lg);
            background: var(--white);
        }

        .contact-icon {
            width: 60px;
            height: 60px;
            border-radius: 18px;
            background: var(--gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 24px;
            flex-shrink: 0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .contact-item:hover .contact-icon {
            transform: scale(1.2) rotate(15deg);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.5);
        }

        .contact-details h3 {
            font-size: 1.4rem;
            margin-bottom: 10px;
            color: var(--text);
        }

        .contact-details p {
            color: var(--text-light);
            font-size: 1.1rem;
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 15px;
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
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(102, 126, 234, 0.1);
        }

        .social-link:hover {
            background: var(--gradient);
            color: var(--white);
            transform: translateY(-5px) scale(1.1);
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
        }

        /* Enhanced Contact Form */
        .contact-form {
            background: var(--bg-light);
            border-radius: var(--radius);
            padding: 50px;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
            transform-style: preserve-3d;
        }

        .contact-form::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--gradient);
            opacity: 0;
            transition: opacity 0.6s ease;
            z-index: 0;
        }

        .contact-form:hover::before {
            opacity: 0.02;
        }

        .form-group {
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
            transform-style: preserve-3d;
        }

        .form-label {
            display: block;
            margin-bottom: 12px;
            font-weight: 600;
            color: var(--text);
            font-size: 1.1rem;
            transform: translateZ(20px);
        }

        .form-control {
            width: 100%;
            padding: 18px 22px;
            border-radius: 16px;
            border: 2px solid #e2e8f0;
            background: var(--white);
            font-family: 'Inter', sans-serif;
            font-size: 1.1rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            z-index: 1;
            transform-style: preserve-3d;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 
                0 0 0 4px rgba(102, 126, 234, 0.1),
                0 10px 25px rgba(102, 126, 234, 0.15);
            transform: translateY(-3px) translateZ(10px);
        }

        textarea.form-control {
            min-height: 180px;
            resize: vertical;
        }

        .btn-block {
            width: 100%;
            justify-content: center;
            margin-top: 10px;
        }

        .form-message {
            margin-top: 20px;
            text-align: center;
            padding: 15px;
            border-radius: 12px;
            font-weight: 600;
            position: relative;
            z-index: 1;
            transform-style: preserve-3d;
            animation: messageSlide 0.5s ease-out;
        }

        @keyframes messageSlide {
            from {
                opacity: 0;
                transform: translateY(-20px) translateZ(0);
            }
            to {
                opacity: 1;
                transform: translateY(0) translateZ(10px);
            }
        }

        .form-message.success {
            background: linear-gradient(135deg, rgba(56, 239, 125, 0.1), rgba(17, 153, 142, 0.1));
            color: #38ef7d;
            border: 2px solid rgba(56, 239, 125, 0.3);
            box-shadow: 0 10px 25px rgba(56, 239, 125, 0.15);
        }

        .form-message.error {
            background: linear-gradient(135deg, rgba(229, 62, 62, 0.1), rgba(220, 38, 38, 0.1));
            color: #e53e3e;
            border: 2px solid rgba(229, 62, 62, 0.3);
            box-shadow: 0 10px 25px rgba(229, 62, 62, 0.15);
        }

        /* Enhanced Footer */
        .footer {
            background: linear-gradient(135deg, var(--text) 0%, #1a202c 100%);
            color: var(--white);
            padding: 80px 0 40px;
            position: relative;
            overflow: hidden;
        }

        .footer::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -20%;
            width: 600px;
            height: 600px;
            background: var(--gradient);
            border-radius: 50%;
            opacity: 0.03;
            animation: rotate 30s linear infinite reverse;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 50px;
            margin-bottom: 60px;
            position: relative;
            z-index: 2;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        .footer-logo .logo-icon {
            width: 45px;
            height: 45px;
            font-size: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        }

        .footer-logo .logo-text {
            font-size: 22px;
            color: var(--white);
        }

        .footer-description {
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 35px;
            max-width: 350px;
            font-size: 1.1rem;
            line-height: 1.7;
        }

        .footer-heading {
            font-size: 1.3rem;
            margin-bottom: 30px;
            color: var(--white);
            position: relative;
        }

        .footer-heading::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 40px;
            height: 3px;
            background: var(--accent);
            border-radius: 2px;
        }

        .footer-links {
            list-style: none;
        }

        .footer-link {
            margin-bottom: 15px;
        }

        .footer-link a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer-link a:hover {
            color: var(--accent);
            transform: translateX(8px);
        }

        .footer-link a::before {
            content: '▸';
            font-size: 0.8rem;
            transition: transform 0.3s ease;
        }

        .footer-link a:hover::before {
            transform: translateX(3px);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 40px;
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            position: relative;
            z-index: 2;
            font-size: 1.1rem;
        }

        /* Enhanced Animations */
        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-20px);
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

        /* Responsive Design */
        @media (max-width: 1200px) {
            .hero-content {
                gap: 60px;
            }
            
            .contact-grid {
                gap: 60px;
            }
            
            .footer-grid {
                gap: 40px;
            }
        }

        @media (max-width: 992px) {
            .hero-content {
                grid-template-columns: 1fr;
                gap: 50px;
                text-align: center;
            }

            .contact-grid {
                grid-template-columns: 1fr;
                gap: 50px;
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr;
                gap: 40px;
            }
            
            .floating-card {
                width: 320px;
                height: 420px;
            }
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(30px);
                flex-direction: column;
                padding: 30px;
                box-shadow: var(--shadow-lg);
                border-radius: 0 0 25px 25px;
            }

            .nav-links.active {
                display: flex;
            }

            .navbar.scrolled .nav-links {
                background: rgba(255, 255, 255, 0.98);
            }

            .nav-link {
                color: var(--text);
                margin: 8px 0;
                text-align: center;
                background: rgba(102, 126, 234, 0.05);
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
            
            .features-grid, .services-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }
            
            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                max-width: 300px;
                justify-content: center;
            }
        }

        @media (max-width: 576px) {
            .container {
                padding: 0 20px;
            }
            
            .floating-card {
                width: 100%;
                max-width: 280px;
                height: 380px;
                padding: 30px;
            }
            
            .contact-form {
                padding: 30px 25px;
            }
            
            .contact-item {
                padding: 25px;
            }
            
            .section-title {
                font-size: 2.5rem;
            }
        }

        /* Loading Animation */
        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }

        .loading-screen.hidden {
            opacity: 0;
            visibility: hidden;
        }

        .loader {
            width: 80px;
            height: 80px;
            border: 4px solid rgba(255,255,255,0.3);
            border-top: 4px solid var(--accent);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Scroll Progress Bar */
        .scroll-progress {
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 4px;
            background: var(--gradient-accent);
            z-index: 1001;
            transition: width 0.3s ease;
            box-shadow: 0 2px 10px rgba(56, 239, 125, 0.5);
        }

        /* Back to Top Button */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: var(--gradient);
            color: var(--white);
            border: none;
            border-radius: 50%;
            cursor: pointer;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .back-to-top.visible {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .back-to-top:hover {
            transform: translateY(-5px) scale(1.1);
            box-shadow: 0 20px 45px rgba(102, 126, 234, 0.6);
        }
    </style>
</head>
<body>
    <!-- Loading Screen -->
    <div class="loading-screen" id="loadingScreen">
        <div class="loader"></div>
    </div>

    <!-- Scroll Progress Bar -->
    <div class="scroll-progress" id="scrollProgress"></div>

    <!-- Back to Top Button -->
    <button class="back-to-top" id="backToTop">
        <i class="fas fa-chevron-up"></i>
    </button>

    <!-- 3D Particle Background -->
    <div id="particles-js"></div>

    <!-- Navigation -->
    <nav class="navbar" id="navbar">
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
            
            <button class="mobile-toggle" id="mobileToggle">
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
                    <a href="#contact" class="btn btn-secondary">
                        <i class="fas fa-envelope"></i>
                        Contact Us
                    </a>
                </div>
            </div>
            
            <div class="hero-visual">
                <div class="floating-card">
                    <i class="fas fa-clinic-medical"></i>
                    <h3>Modern. Clean. Open 24/7</h3>
                    <p>State-of-the-art pharmacy in Gampaha with cutting-edge technology and premium healthcare services</p>
                </div>
                
                <div class="floating-pills">
                    <div class="pill"><i class="fas fa-capsules"></i></div>
                    <div class="pill"><i class="fas fa-tablets"></i></div>
                    <div class="pill"><i class="fas fa-prescription-bottle-alt"></i></div>
                    <div class="pill"><i class="fas fa-pills"></i></div>
                    <div class="pill"><i class="fas fa-syringe"></i></div>
                    <div class="pill"><i class="fas fa-heartbeat"></i></div>
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
                    Established in <span style="font-weight: 700; color: #667eea;">2024</span> in the heart of Gampaha, Dharani is the newest and most modern pharmacy in the region, built to serve with excellence and innovation.
                </p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3 class="feature-title">Premium Location</h3>
                    <p class="feature-description">State-of-the-art facility in the heart of Gampaha with easy access and ample parking</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3 class="feature-title">Years of Excellence</h3>
                    <p class="feature-description">Launched in 2024 with full commitment to quality healthcare and customer satisfaction</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="feature-title">24/7 Service</h3>
                    <p class="feature-description">Always here for emergencies and urgent medical needs with round-the-clock service</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section
    <section class="stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number" id="patients">0</div>
                    <div class="stat-label">Satisfied Customers</div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-number" id="prescriptions">0</div>
                    <div class="stat-label">Prescriptions Filled</div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-number" id="staff">0</div>
                    <div class="stat-label">Expert Staff Members</div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-number">4.9</div>
                    <div class="stat-label">Customer Rating</div>
                </div>
            </div>
        </div>
    </section> -->

    <!-- Services Section -->
    <section class="services">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Our Premium Services</h2>
                <p class="section-subtitle">
                    We provide comprehensive pharmaceutical services with cutting-edge technology and expert care for all your healthcare needs.
                </p>
            </div>
            
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-prescription"></i>
                    </div>
                    <h3 class="service-title">Smart Inventory Management</h3>
                    <p class="service-description">AI-powered systems to manage and optimize medication stock levels with real-time analytics</p>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <h3 class="service-title">Expert Consultation</h3>
                    <p class="service-description">Professional consultations from qualified pharmacists for personalized healthcare solutions</p>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h3 class="service-title">AI Prescription Validation</h3>
                    <p class="service-description">Advanced AI systems to ensure prescription accuracy, safety, and drug interaction checks</p>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="service-title">Insurance Support</h3>
                    <p class="service-description">Seamless processing of insurance claims and coverage verification with multiple providers</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section" id="contact">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Get In Touch</h2>
                <p class="section-subtitle">
                    Have questions or need assistance? We're here to help with all your pharmaceutical needs and inquiries.
                </p>
            </div>
            
            <div class="contact-grid">
                <div class="contact-info">
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Visit Our Location</h3>
                            <p>No. 45, Oruthota Road, Gampaha</p>
                            <p>Sri Lanka</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Call Us Anytime</h3>
                            <p>+94 33 222 2024</p>
                            <p>24/7 Emergency Line</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Email Us</h3>
                            <p>dharani.pharmacy@gmail.com</p>
                            <p>Quick response guaranteed</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Opening Hours</h3>
                            <p>24 Hours, 7 Days a Week</p>
                            <p>Emergency services available</p>
                            <!-- <div class="social-links">
                                <a href="#" class="social-link" title="Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="social-link" title="WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                                <a href="#" class="social-link" title="Instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="#" class="social-link" title="Twitter">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            </div> -->
                        </div>
                    </div>
                </div>
                
                <div class="contact-form">
                    <h3 style="margin-bottom: 30px; font-size: 1.8rem;">Send Us a Message</h3>
                    <form id="contactForm" method="POST">
                        <input type="hidden" name="access_key" value="fdf74da1-b7a0-4bb0-a7d9-a722bea52325">
                        <input type="hidden" name="redirect" value="https://yourdomain.com/thank-you.html">
                        
                        <div class="form-group">
                            <label class="form-label" for="name">Your Full Name</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Enter your full name" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="email">Email Address</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email address" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="subject">Subject</label>
                            <input type="text" id="subject" name="subject" class="form-control" placeholder="What is this regarding?" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="message">Your Message</label>
                            <textarea id="message" name="message" class="form-control" placeholder="Please describe your inquiry in detail..." required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-block" id="submitBtn">
                            <i class="fas fa-paper-plane"></i>
                            Send Message
                        </button>
                    </form>
                    <div id="formMessage" class="form-message" style="display: none;"></div>
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
                        Gampaha's premier trusted pharmacy, established in 2024. Serving the community with excellence, precision, and 24/7 availability. Your health is our priority.
                    </p>
                </div>
                
                <div class="footer-links-column">
                    <h3 class="footer-heading">Quick Access</h3>
                    <ul class="footer-links">
                        <li class="footer-link"><a href="#"><i class="fas fa-home"></i> Home</a></li>
                        <li class="footer-link"><a href="pharmacist/index.php"><i class="fas fa-user-md"></i> Pharmacist Portal</a></li>
                        <li class="footer-link"><a href="admin/index.php"><i class="fas fa-cog"></i> Admin Portal</a></li>
                        <li class="footer-link"><a href="customer/index.php"><i class="fas fa-users"></i> Customer Portal</a></li>
                    </ul>
                </div>
                
                <div class="footer-links-column">
                    <h3 class="footer-heading">Our Services</h3>
                    <ul class="footer-links">
                        <li class="footer-link"><a><i class="fas fa-prescription"></i> Prescription Services</a></li>
                        <li class="footer-link"><a><i class="fas fa-comment-medical"></i> Health Consultations</a></li>
                        <li class="footer-link"><a><i class="fas fa-pills"></i> Medical Supplies</a></li>
                        <li class="footer-link"><a><i class="fas fa-file-invoice-dollar"></i> Insurance Processing</a></li>
                    </ul>
                </div>
                
                <div class="footer-links-column">
                    <h3 class="footer-heading">Contact Info</h3>
                    <ul class="footer-links">
                        <li class="footer-link"><a><i class="fas fa-map-marker-alt"></i> No. 45, Oruthota Road, Gampaha</a></li>
                        <li class="footer-link"><a><i class="fas fa-phone"></i> +94 33 222 2024</a></li>
                        <li class="footer-link"><a><i class="fas fa-envelope"></i> dharani.pharmacy@gmail.com</a></li>
                        <li class="footer-link"><a><i class="fas fa-clock"></i> Open 24/7</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2025 Dharani Pharmacy Management System. All Rights Reserved. | Designed with Kavishka <i class="fas fa-heart" style="color: #38ef7d;"></i> for better healthcare</p>
            </div>
        </div>
    </footer>

    <script>
        // Enhanced JavaScript with modern features
        document.addEventListener('DOMContentLoaded', function() {
            // Remove loading screen
            setTimeout(() => {
                document.getElementById('loadingScreen').classList.add('hidden');
            }, 1500);

            // Particle.js configuration
            particlesJS('particles-js', {
                particles: {
                    number: { value: 80, density: { enable: true, value_area: 800 } },
                    color: { value: "#ffffff" },
                    shape: { type: "circle" },
                    opacity: { value: 0.3, random: true },
                    size: { value: 3, random: true },
                    line_linked: {
                        enable: true,
                        distance: 150,
                        color: "#ffffff",
                        opacity: 0.2,
                        width: 1
                    },
                    move: {
                        enable: true,
                        speed: 2,
                        direction: "none",
                        random: true,
                        straight: false,
                        out_mode: "out",
                        bounce: false
                    }
                },
                interactivity: {
                    detect_on: "canvas",
                    events: {
                        onhover: { enable: true, mode: "repulse" },
                        onclick: { enable: true, mode: "push" },
                        resize: true
                    }
                }
            });

            // Navbar scroll effect
            const navbar = document.getElementById('navbar');
            const scrollProgress = document.getElementById('scrollProgress');
            const backToTop = document.getElementById('backToTop');

            window.addEventListener('scroll', () => {
                const scrolled = window.pageYOffset;
                const height = document.documentElement.scrollHeight - window.innerHeight;
                const progress = (scrolled / height) * 100;
                
                scrollProgress.style.width = progress + '%';
                
                if (scrolled > 50) {
                    navbar.classList.add('scrolled');
                    backToTop.classList.add('visible');
                } else {
                    navbar.classList.remove('scrolled');
                    backToTop.classList.remove('visible');
                }
            });

            // Mobile menu toggle
            document.getElementById('mobileToggle').addEventListener('click', function() {
                const navLinks = document.querySelector('.nav-links');
                navLinks.classList.toggle('active');
                this.classList.toggle('active');
            });

            // Back to top functionality
            backToTop.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });

            // Counter animation
            const counters = [
                { id: 'patients', target: 1250 },
                { id: 'prescriptions', target: 4850 },
                { id: 'staff', target: 12 }
            ];

            const startCounter = (id, target) => {
                let count = 0;
                const increment = target / 100;
                const timer = setInterval(() => {
                    count += increment;
                    if (count >= target) {
                        document.getElementById(id).textContent = target.toLocaleString();
                        clearInterval(timer);
                    } else {
                        document.getElementById(id).textContent = Math.floor(count).toLocaleString();
                    }
                }, 20);
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        counters.forEach(c => startCounter(c.id, c.target));
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });

            observer.observe(document.querySelector('.stats'));

            // Enhanced form submission with better UX
            const contactForm = document.getElementById('contactForm');
            const submitBtn = document.getElementById('submitBtn');
            const formMessage = document.getElementById('formMessage');

            contactForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                // Enhanced loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending Message...';
                submitBtn.style.opacity = '0.8';
                
                try {
                    const formData = new FormData(this);
                    const response = await fetch('https://api.web3forms.com/submit', {
                        method: 'POST',
                        body: formData
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        formMessage.textContent = '🎉 Thank you! Your message has been sent successfully. We\'ll get back to you within 24 hours.';
                        formMessage.className = 'form-message success';
                        formMessage.style.display = 'block';
                        this.reset();
                        
                        // Add celebration effect
                        submitBtn.style.background = 'var(--accent)';
                        setTimeout(() => {
                            submitBtn.style.background = '';
                        }, 2000);
                    } else {
                        throw new Error(result.message || 'Unable to send message');
                    }
                } catch (error) {
                    formMessage.textContent = '❌ Sorry, there was an error sending your message. Please try again or contact us directly.';
                    formMessage.className = 'form-message error';
                    formMessage.style.display = 'block';
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Send Message';
                    submitBtn.style.opacity = '1';
                    
                    // Hide message after 8 seconds
                    setTimeout(() => {
                        formMessage.style.display = 'none';
                    }, 8000);
                }
            });

            // Add smooth scrolling for navigation links
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

            // Add parallax effect to hero section
            window.addEventListener('scroll', () => {
                const scrolled = window.pageYOffset;
                const hero = document.querySelector('.hero');
                if (hero) {
                    hero.style.transform = `translateY(${scrolled * 0.5}px)`;
                }
            });

            // Add interactive cursor effects
            document.addEventListener('mousemove', (e) => {
                const cards = document.querySelectorAll('.feature-card, .service-card, .contact-item');
                cards.forEach(card => {
                    const rect = card.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    
                    if (x > 0 && x < rect.width && y > 0 && y < rect.height) {
                        card.style.setProperty('--mouse-x', `${x}px`);
                        card.style.setProperty('--mouse-y', `${y}px`);
                    }
                });
            });
        });

        // Add CSS for interactive cursor effects
        const style = document.createElement('style');
        style.textContent = `
            .feature-card, .service-card, .contact-item {
                position: relative;
                overflow: hidden;
            }
            
            .feature-card::after, .service-card::after, .contact-item::after {
                content: '';
                position: absolute;
                top: var(--mouse-y, 50%);
                left: var(--mouse-x, 50%);
                width: 100px;
                height: 100px;
                background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
                transform: translate(-50%, -50%);
                opacity: 0;
                transition: opacity 0.3s ease;
                pointer-events: none;
            }
            
            .feature-card:hover::after, .service-card:hover::after, .contact-item:hover::after {
                opacity: 1;
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>