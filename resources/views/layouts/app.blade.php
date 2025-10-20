<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-site-verification" content="meVhqb01r1XAY5th9eDkkdckwOdorDK8IKrxiVh3DDo" />
    <title>@yield('title', 'Jawahar Ganesh @ Jay - Portfolio')</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('description', 'Professional portfolio of Jawahar Ganesh @ Jay - Full-Stack Developer, Software Engineer, and Technical Consultant specializing in web development, mobile apps, AI/ML, and embedded systems.')">
    <meta name="keywords" content="@yield('keywords', 'Jawahar Ganesh, JayXCoder, Full-Stack Developer, Software Engineer, Web Development, Mobile Apps, AI/ML, Embedded Systems, Laravel, React, Python, JavaScript, Portfolio')">
    <meta name="author" content="Jawahar Ganesh @ Jay">
    <meta name="robots" content="index, follow">
    <meta name="language" content="en">
    <meta name="revisit-after" content="7 days">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="@yield('title', 'Jawahar Ganesh @ Jay - Portfolio')">
    <meta property="og:description" content="@yield('description', 'Professional portfolio of Jawahar Ganesh @ Jay - Full-Stack Developer, Software Engineer, and Technical Consultant specializing in web development, mobile apps, AI/ML, and embedded systems.')">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="Jawahar Ganesh @ Jay Portfolio">
    <meta property="og:image" content="@yield('og_image', url('/images/og-image.jpg'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="Jawahar Ganesh @ Jay - Professional Portfolio">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'Jawahar Ganesh @ Jay - Portfolio')">
    <meta name="twitter:description" content="@yield('description', 'Professional portfolio of Jawahar Ganesh @ Jay - Full-Stack Developer, Software Engineer, and Technical Consultant.')">
    <meta name="twitter:image" content="@yield('og_image', url('/images/og-image.jpg'))">
    <meta name="twitter:image:alt" content="Jawahar Ganesh @ Jay - Professional Portfolio">
    
    <!-- Additional SEO Meta Tags -->
    <meta name="theme-color" content="#8b5cf6">
    <meta name="msapplication-TileColor" content="#8b5cf6">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    
    <!-- Structured Data -->
    @yield('structured_data')
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' fill='%231a1a1a' rx='8'/%3E%3Crect x='8' y='8' width='84' height='84' fill='none' stroke='%238b5cf6' stroke-width='2' rx='6'/%3E%3Ctext x='50' y='42' font-family='Courier New, monospace' font-size='20' font-weight='bold' text-anchor='middle' fill='%23ffffff'%3EJXG%3C/text%3E%3Ctext x='50' y='62' font-family='Courier New, monospace' font-size='12' text-anchor='middle' fill='%238b5cf6'%3E%3E_%3C/text%3E%3C/svg%3E">
    <link rel="apple-touch-icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' fill='%231a1a1a' rx='8'/%3E%3Crect x='8' y='8' width='84' height='84' fill='none' stroke='%238b5cf6' stroke-width='2' rx='6'/%3E%3Ctext x='50' y='42' font-family='Courier New, monospace' font-size='20' font-weight='bold' text-anchor='middle' fill='%23ffffff'%3EJXG%3C/text%3E%3Ctext x='50' y='62' font-family='Courier New, monospace' font-size='12' text-anchor='middle' fill='%238b5cf6'%3E%3E_%3C/text%3E%3C/svg%3E">
    <link rel="shortcut icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' fill='%231a1a1a' rx='8'/%3E%3Crect x='8' y='8' width='84' height='84' fill='none' stroke='%238b5cf6' stroke-width='2' rx='6'/%3E%3Ctext x='50' y='42' font-family='Courier New, monospace' font-size='20' font-weight='bold' text-anchor='middle' fill='%23ffffff'%3EJXG%3C/text%3E%3Ctext x='50' y='62' font-family='Courier New, monospace' font-size='12' text-anchor='middle' fill='%238b5cf6'%3E%3E_%3C/text%3E%3C/svg%3E">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --dark-bg: #0a0a0a;
            --dark-secondary: #1a1a1a;
            --dark-tertiary: #2a2a2a;
            --accent-primary: #8b5cf6;
            --accent-secondary: #a855f7;
            --accent-tertiary: #c084fc;
            --text-primary: #ffffff;
            --text-secondary: #a0a0a0;
            --text-muted: #666666;
            --border-color: #333333;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--dark-bg);
            color: var(--text-primary);
            line-height: 1.6;
            overflow-x: hidden;
        }

        .navbar {
            background: rgba(10, 10, 10, 0.95) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }
        
        .navbar .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative; /* anchor absolute animated text */
        }
        
        /* Default: collapsed (overridden for >=1600px or when .show is present) */
        .navbar-collapse {
            display: none; /* start closed by default */
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 0 2rem;
        }

        .navbar-collapse.show {
            display: flex; /* open state below 1600px */
        }

        /* Desktop wide: keep hamburger menu as well */
        @media (min-width: 1600px) {
            .navbar-toggler { display: block !important; }
            .navbar-collapse { display: none !important; width: 100% !important; padding: 1rem 0 !important; justify-content: flex-start; flex-direction: column; }
            .navbar-collapse.show { display: block !important; }
            .navbar-nav { flex-direction: column !important; width: 100% !important; margin-left: 0 !important; margin-right: 0 !important; padding: 0 !important; }
            .navbar-nav .nav-link { text-align: center; border-bottom: 1px solid rgba(139, 92, 246, 0.1); padding: 0.75rem 1.5rem; }
            .navbar-nav .nav-link:last-child { border-bottom: none; }
        }

        .navbar-brand {
            color: var(--accent-primary) !important;
            font-weight: 700;
            font-size: 1.5rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-brand-container {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .terminal-logo {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            background: var(--dark-secondary);
            padding: 0.3rem 0.6rem;
            border-radius: 5px;
            border: 1px solid var(--accent-primary);
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
        }

        .static-terminal-logo {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .static-terminal-logo:hover {
            transform: scale(1.05);
        }

        .terminal-prompt {
            color: var(--accent-primary);
        }

        .terminal-user {
            color: var(--accent-secondary);
        }

        .terminal-symbol {
            color: var(--text-primary);
        }

        .terminal-cursor {
            background: var(--accent-primary);
            width: 8px;
            height: 1rem;
            display: inline-block;
            animation: blink 1s infinite;
        }

        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0; }
        }

        /* ========================================
           ADMIN LOGIN BUTTON
           ======================================== */
        .admin-login-btn {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            border: 1px solid #dc2626;
            border-radius: 4px;
            padding: 0.3rem 0.8rem;
            margin-left: 0.5rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
            color: #000000 !important; /* ensure text is black */
        }

        .admin-login-btn:hover {
            background: #000000; /* black on hover */
            border-color: #000000;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            color: #ffffff !important; /* white text on black */
        }

        .admin-login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .admin-login-btn:hover::before {
            left: 100%;
        }

        .admin-login-btn i {
            animation: pulse 2s infinite;
            color: #000000 !important; /* icon black */
        }

        .admin-login-btn:hover i {
            color: #ffffff !important; /* icon white on hover */
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        /* ========================================
           NAVBAR ANIMATED TEXT
           ======================================== */
        .navbar-animated-text {
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            z-index: 0; /* keep behind interactive items */
            pointer-events: none; /* avoid blocking clicks */
        }

        .navbar-text-animation {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--accent-primary);
            font-family: 'Inter', sans-serif;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            opacity: 0.8;
            transition: all 0.3s ease;
            text-shadow: 0 0 10px var(--accent-primary);
        }

        .navbar-text-animation.zoom-in {
            animation: navbarZoomIn 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards,
                       navbarPulse 1s ease-in-out infinite 0.4s;
        }

        .navbar-text-animation.dash-left-in {
            animation: navbarDashLeftIn 0.35s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards,
                       navbarPulse 1s ease-in-out infinite 0.35s;
        }

        .navbar-text-animation.spin-in {
            animation: navbarSpinIn 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards,
                       navbarPulse 1s ease-in-out infinite 0.4s;
        }

        .navbar-text-animation.bounce-in {
            animation: navbarBounceIn 0.45s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards,
                       navbarPulse 1s ease-in-out infinite 0.45s;
        }

        .navbar-text-animation.zoom-out {
            animation: navbarZoomOut 0.3s ease-in forwards;
        }

        .navbar-text-animation.dash-right-out {
            animation: navbarDashRightOut 0.3s ease-out forwards;
        }

        .navbar-text-animation.flip-out {
            animation: navbarFlipOut 0.3s ease-in forwards;
        }

        .navbar-text-animation.slide-up-out {
            animation: navbarSlideUpOut 0.3s ease-in forwards;
        }


        /* Navbar animations */
        @keyframes navbarZoomIn {
            0% { opacity: 0; transform: scale(0.3) rotate(5deg); }
            50% { opacity: 0.8; transform: scale(1.1) rotate(-2deg); }
            100% { opacity: 0.8; transform: scale(1) rotate(0deg); }
        }

        @keyframes navbarZoomOut {
            0% { opacity: 0.8; transform: scale(1) rotate(0deg); }
            100% { opacity: 0; transform: scale(0.2) rotate(-10deg); }
        }

        @keyframes navbarDashLeftIn {
            0% { opacity: 0; transform: translateX(-50px) scale(0.5) rotate(-10deg); }
            60% { opacity: 0.9; transform: translateX(5px) scale(1.05) rotate(2deg); }
            100% { opacity: 0.8; transform: translateX(0) scale(1) rotate(0deg); }
        }

        @keyframes navbarDashRightOut {
            0% { opacity: 0.8; transform: translateX(0) scale(1) rotate(0deg); }
            100% { opacity: 0; transform: translateX(50px) scale(0.3) rotate(10deg); }
        }

        @keyframes navbarSpinIn {
            0% { opacity: 0; transform: rotate(180deg) scale(0.2); }
            50% { opacity: 0.8; transform: rotate(90deg) scale(1.1); }
            100% { opacity: 0.8; transform: rotate(0deg) scale(1); }
        }

        @keyframes navbarFlipOut {
            0% { opacity: 0.8; transform: rotateY(0deg) scale(1); }
            100% { opacity: 0; transform: rotateY(45deg) scale(0.3); }
        }

        @keyframes navbarBounceIn {
            0% { opacity: 0; transform: translateY(-20px) scale(0.3); }
            60% { opacity: 0.8; transform: translateY(5px) scale(1.1); }
            80% { opacity: 0.7; transform: translateY(-2px) scale(0.95); }
            100% { opacity: 0.8; transform: translateY(0) scale(1); }
        }

        @keyframes navbarSlideUpOut {
            0% { opacity: 0.8; transform: translateY(0) scale(1); }
            100% { opacity: 0; transform: translateY(-20px) scale(0.3); }
        }

        @keyframes navbarPulse {
            0%, 100% { text-shadow: 0 0 10px var(--accent-primary); opacity: 0.8; }
            50% { text-shadow: 0 0 15px var(--accent-primary), 0 0 20px var(--accent-secondary); opacity: 0.9; }
        }


        /* Force hamburger menu below 1600px - Override Bootstrap */
        @media (max-width: 1599px) {
            .navbar-animated-text {
                display: none !important; /* Hide animated text below 1600px */
            }
            
            /* Force hamburger menu behavior below 1600px */
            .navbar-collapse {
                background: rgba(10, 10, 10, 0.98);
                backdrop-filter: blur(10px);
                border-top: 1px solid var(--border-color);
                margin-top: 0.5rem;
                padding: 0.5rem 0;
                flex-direction: column;
                width: auto; /* dynamic width */
            }
            
            .navbar-collapse.show { display: block; }
            
            .navbar-nav {
                flex-direction: column !important;
                width: auto !important;
                margin-left: 0 !important; /* Override desktop margin */
                margin-right: 0 !important;
                padding: 0 !important;
            }
            
            .navbar-nav .nav-link {
                padding: 0.75rem 1.5rem;
                font-size: 1rem;
                text-align: center;
                border-bottom: 1px solid rgba(139, 92, 246, 0.1);
                transition: all 0.3s ease;
            }
            
            .navbar-nav .nav-link:hover {
                background: rgba(139, 92, 246, 0.1);
                color: var(--accent-primary);
            }
            
            .navbar-nav .nav-link:last-child {
                border-bottom: none;
            }
            
            .navbar-brand-container {
                flex-direction: row;
                align-items: center;
            }
            
            .terminal-logo {
                font-size: 0.8rem;
                padding: 0.2rem 0.4rem;
            }
            
            /* Show hamburger menu */
            .navbar-toggler {
                display: block;
                border: 1px solid var(--accent-primary);
                padding: 0.25rem 0.5rem;
            }
            
            .navbar-toggler:focus {
                box-shadow: 0 0 0 0.2rem rgba(139, 92, 246, 0.25);
            }
            
            .navbar-toggler-icon {
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28139, 92, 246, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
            }
        }

        @media (max-width: 768px) {
            .terminal-logo {
                font-size: 0.7rem;
                padding: 0.15rem 0.3rem;
            }
            
            .navbar-nav .nav-link {
                padding: 0.6rem 1rem;
                font-size: 0.9rem;
            }
        }
        
        @media (max-width: 576px) {
            .terminal-logo {
                font-size: 0.65rem;
                padding: 0.1rem 0.25rem;
            }
            
            .navbar-nav .nav-link {
                padding: 0.5rem 0.75rem;
                font-size: 0.85rem;
            }
        }

        .navbar-nav {
            margin-left: auto; /* push menu to the right on wide screens */
            gap: 0.5rem;
        }

        .nav-link {
            color: var(--text-secondary) !important;
            font-weight: 500;
            transition: color 0.3s ease;
            position: relative;
            text-decoration: none;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--accent-primary) !important;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 50%;
            background: var(--accent-primary);
            transition: all 0.3s ease;
        }

        .nav-link:hover::after, .nav-link.active::after {
            width: 100%;
            left: 0;
        }

        .section {
            padding: 100px 0;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            width: 60px;
            height: 4px;
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 2px;
        }

        .card-custom {
            background: var(--dark-secondary);
            border: 1px solid var(--border-color);
            border-radius: 15px;
            padding: 2rem;
            transition: all 0.3s ease;
            height: 100%;
        }

        .card-custom:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            border-color: var(--accent-primary);
        }

        .card-icon {
            font-size: 3rem;
            color: var(--accent-primary);
            margin-bottom: 1.5rem;
        }

        .card-title {
            color: var(--text-primary);
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .card-text {
            color: var(--text-secondary);
        }

        .skills-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .skill-item {
            background: var(--dark-secondary);
            padding: 2rem;
            border-radius: 15px;
            border: 1px solid var(--border-color);
            text-align: center;
            transition: all 0.3s ease;
        }

        .skill-item:hover {
            transform: translateY(-5px);
            border-color: var(--accent-primary);
        }

        .skill-icon {
            font-size: 2.5rem;
            color: var(--accent-primary);
            margin-bottom: 1rem;
        }

        .contact-info {
            background: var(--dark-secondary);
            padding: 3rem;
            border-radius: 20px;
            border: 1px solid var(--border-color);
            text-align: center;
        }

        .contact-item {
            margin: 1.5rem 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }

        .contact-icon {
            color: var(--accent-primary);
            font-size: 1.5rem;
        }

        .footer {
            background: var(--dark-secondary);
            padding: 2rem 0;
            border-top: 1px solid var(--border-color);
            text-align: center;
        }

        /* Visitor Count Widget Styles */
        .visitor-count-widget {
            background: linear-gradient(135deg, var(--dark-tertiary), var(--dark-secondary));
            border: 1px solid var(--border-color);
            border-radius: 15px;
            padding: 1.5rem;
            margin-top: 1rem;
            position: relative;
            overflow: hidden;
        }

        .visitor-count-widget::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(139, 92, 246, 0.1) 50%, transparent 70%);
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {
            0%, 100% { transform: translateX(-100%); }
            50% { transform: translateX(100%); }
        }

        .visitor-stats {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 2rem;
            margin-bottom: 1rem;
        }

        .visitor-stat-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(139, 92, 246, 0.1);
            padding: 0.75rem 1rem;
            border-radius: 25px;
            border: 1px solid rgba(139, 92, 246, 0.3);
            transition: all 0.3s ease;
            position: relative;
            z-index: 2;
        }

        .visitor-stat-item:hover {
            background: rgba(139, 92, 246, 0.2);
            border-color: var(--accent-primary);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(139, 92, 246, 0.3);
        }

        .visitor-icon {
            color: var(--accent-primary);
            font-size: 1.1rem;
            animation: pulse 2s ease-in-out infinite;
        }

        .visitor-label {
            color: var(--text-secondary);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .visitor-number {
            color: var(--accent-primary);
            font-weight: 700;
            font-size: 1.1rem;
            text-shadow: 0 0 10px rgba(139, 92, 246, 0.5);
            animation: countUp 0.8s ease-out;
        }

        @keyframes countUp {
            from { opacity: 0; transform: scale(0.5); }
            to { opacity: 1; transform: scale(1); }
        }

        .visitor-live-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .live-dot {
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
            animation: livePulse 1.5s ease-in-out infinite;
        }

        @keyframes livePulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.2); }
        }

        .live-text {
            color: var(--text-secondary);
            font-size: 0.8rem;
            font-weight: 500;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .visitor-stats {
                flex-direction: column;
                gap: 1rem;
            }
            
            .visitor-stat-item {
                width: 100%;
                justify-content: center;
            }
        }

        .security-badges .badge {
            font-size: 0.75rem;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            animation: glow 3s ease-in-out infinite alternate;
        }

        .security-badges .bg-success {
            background: linear-gradient(135deg, #10b981, #059669) !important;
        }

        .security-badges .bg-info {
            background: linear-gradient(135deg, #3b82f6, #2563eb) !important;
        }

        @keyframes glow {
            from {
                box-shadow: 0 0 5px rgba(139, 92, 246, 0.3);
            }
            to {
                box-shadow: 0 0 15px rgba(139, 92, 246, 0.6);
            }
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            text-decoration: none;
            color: white;
            display: inline-block;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(139, 92, 246, 0.4);
            color: white;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeInUp 0.8s ease;
        }

        @media (max-width: 768px) {
            .section {
                padding: 60px 0;
            }
            
            .section-title {
                font-size: 2rem;
            }
        }

        /* Hero specific styles */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, var(--dark-bg) 0%, var(--dark-secondary) 100%);
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 80%, rgba(139, 92, 246, 0.15) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(168, 85, 247, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
            animation: fadeInUp 1s ease;
        }

        .hero-subtitle {
            font-size: 1.3rem;
            color: var(--text-secondary);
            margin-bottom: 2rem;
            animation: fadeInUp 1s ease 0.2s both;
        }

        .hero-description {
            font-size: 1.1rem;
            color: var(--text-muted);
            max-width: 600px;
            margin-bottom: 3rem;
            animation: fadeInUp 1s ease 0.4s both;
        }

        .scroll-indicator {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateX(-50%) translateY(0);
            }
            40% {
                transform: translateX(-50%) translateY(-10px);
            }
            60% {
                transform: translateX(-50%) translateY(-5px);
            }
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
        }

        /* ========================================
           COMPACT RIGHT-CORNER COLLAPSE PANEL (ALL WIDTHS)
           ======================================== */
        .navbar .navbar-collapse {
            position: absolute;
            right: 1rem;
            top: 100%;
            width: auto; /* grow by content */
            max-width: 90vw;
            max-height: 80vh;
            overflow-y: auto;
            background: rgba(10, 10, 10, 0.98);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 0.25rem 0.25rem;
            margin-top: 0.5rem;
            display: none; /* default hidden; shown via .show */
            z-index: 1000;
            display: flex;
            flex-direction: column;
            align-items: stretch;
        }

        .navbar .navbar-collapse.show { display: block; }

        .navbar .navbar-nav { flex-direction: column; width: auto; margin: 0; padding: 0; }
        .navbar .navbar-nav .nav-link { text-align: left; border-bottom: 1px solid rgba(139, 92, 246, 0.1); padding: 0.5rem 0.75rem; white-space: nowrap; }
        .navbar .navbar-nav .nav-link:last-child { border-bottom: none; }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <div class="navbar-brand-container">
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' fill='%231a1a1a' rx='8'/%3E%3Crect x='8' y='8' width='84' height='84' fill='none' stroke='%238b5cf6' stroke-width='2' rx='6'/%3E%3Ctext x='50' y='42' font-family='Courier New, monospace' font-size='20' font-weight='bold' text-anchor='middle' fill='%23ffffff'%3EJXG%3C/text%3E%3Ctext x='50' y='62' font-family='Courier New, monospace' font-size='12' text-anchor='middle' fill='%238b5cf6'%3E%3E_%3C/text%3E%3C/svg%3E" 
                         alt="JXG Terminal Logo" 
                         class="static-terminal-logo">
                    <div class="terminal-logo">
                        <span class="terminal-prompt">$></span>
                        <span class="terminal-user">jayxcoder</span>
                        <span class="terminal-symbol">@</span>
                        <span class="terminal-user">portfolio</span>
                        <span class="terminal-cursor"></span>
                    </div>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Center Animated Text (outside collapse) -->
            <div class="navbar-animated-text d-none d-xxl-flex">
                <span class="navbar-text-animation" id="navbarAnimatedText">Code</span>
            </div>

            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- All Navigation Links on Right -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('skills') ? 'active' : '' }}" href="{{ route('skills') }}">Skills</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('projects') ? 'active' : '' }}" href="{{ route('projects') }}">Projects</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('portfolio') || request()->routeIs('portfolio.item') ? 'active' : '' }}" href="{{ route('portfolio') }}">Portfolio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('experience') ? 'active' : '' }}" href="{{ route('experience') }}">Experience</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link admin-login-btn" href="{{ url('/admin/login', [], true) }}" title="Admin Access">
                            <i class="fas fa-lock me-1"></i>
                            <span class="d-none d-xxl-inline">Admin</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; 2025 Jawahar Ganesh @ Jay. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="security-badges">
                        <span class="badge bg-success me-2" title="Secure Admin Panel">
                            <i class="fas fa-shield-alt me-1"></i>
                            Admin Protected
                        </span>
                        <span class="badge bg-info" title="Database Secured">
                            <i class="fas fa-database me-1"></i>
                            Database Secured
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Visitor Count Display -->
            <div class="row mt-3">
                <div class="col-12 text-center">
                    <div class="visitor-count-widget">
                        <div class="visitor-stats">
                            <div class="visitor-stat-item">
                                <i class="fas fa-users visitor-icon"></i>
                                <span class="visitor-label">Total Visitors:</span>
                                <span class="visitor-number" id="totalVisitors">Loading...</span>
                            </div>
                            <div class="visitor-stat-item">
                                <i class="fas fa-eye visitor-icon"></i>
                                <span class="visitor-label">Page Views:</span>
                                <span class="visitor-number" id="totalPageViews">Loading...</span>
                            </div>
                            <div class="visitor-stat-item">
                                <i class="fas fa-calendar-day visitor-icon"></i>
                                <span class="visitor-label">Today:</span>
                                <span class="visitor-number" id="todayVisitors">Loading...</span>
                            </div>
                        </div>
                        <div class="visitor-live-indicator">
                            <span class="live-dot"></span>
                            <span class="live-text">Live Stats</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add scroll effect to navbar
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(10, 10, 10, 0.98)';
            } else {
                navbar.style.background = 'rgba(10, 10, 10, 0.95)';
            }
        });

        // Add fade-in animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in');
                }
            });
        }, observerOptions);

        // Observe all cards and sections
        document.querySelectorAll('.card-custom, .skill-item').forEach(el => {
            observer.observe(el);
        });

        // Visitor Count Widget
        function loadVisitorStats() {
            fetch('/api/visitor-stats')
                .then(response => response.json())
                .then(data => {
                    // Animate number updates
                    animateNumber('totalVisitors', data.total_visitors || 0);
                    animateNumber('totalPageViews', data.total_page_views || 0);
                    animateNumber('todayVisitors', data.today_visitors || 0);
                })
                .catch(error => {
                    console.log('Visitor stats not available');
                    // Show fallback numbers
                    document.getElementById('totalVisitors').textContent = '0';
                    document.getElementById('totalPageViews').textContent = '0';
                    document.getElementById('todayVisitors').textContent = '0';
                });
        }

        function animateNumber(elementId, targetNumber) {
            const element = document.getElementById(elementId);
            const currentNumber = parseInt(element.textContent) || 0;
            const increment = Math.ceil((targetNumber - currentNumber) / 20);
            let current = currentNumber;

            const timer = setInterval(() => {
                current += increment;
                if (current >= targetNumber) {
                    current = targetNumber;
                    clearInterval(timer);
                }
                element.textContent = current.toLocaleString();
            }, 50);
        }

        // Load visitor stats when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadVisitorStats();
            
            // Refresh stats every 30 seconds
            setInterval(loadVisitorStats, 30000);
        });
    </script>
    @stack('scripts')
    
    <!-- Navbar Animation Script -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // ========================================
        // NAVBAR ANIMATED TEXT SYSTEM
        // ========================================
        
        // DOM element references
        const navbarAnimatedText = document.getElementById('navbarAnimatedText');
        
        // Word array for cycling animation
        const navbarWords = [
            'Code', 'Design', 'Innovate', 'Develop', 'Cybersecurity', 'Leadership',
            'Engineer', 'Solve', 'Optimize', 'Automate', 'Secure', 'Deploy'
        ];
        
        // Animation combinations
        const navbarAnimations = [
            { enter: 'zoom-in', exit: 'zoom-out' },
            { enter: 'dash-left-in', exit: 'dash-right-out' },
            { enter: 'spin-in', exit: 'flip-out' },
            { enter: 'bounce-in', exit: 'slide-up-out' }
        ];
        
        // Animation state tracking
        let navbarCurrentIndex = 0;
        let navbarAnimationIndex = 0;
        
        // Navbar animation function
        function animateNavbarText() {
            // Check if element exists
            if (!navbarAnimatedText) return;
            
            const currentAnimation = navbarAnimations[navbarAnimationIndex];
            
            // Remove all animation classes from text
            navbarAnimatedText.className = 'navbar-text-animation';
            
            // Add exit animation to current text
            navbarAnimatedText.classList.add(currentAnimation.exit);
            
            // Wait for exit animation to complete (0.3s)
            setTimeout(() => {
                // Check if element still exists
                if (!navbarAnimatedText) return;
                
                // Change text content
                navbarAnimatedText.textContent = navbarWords[navbarCurrentIndex];
                
                // Remove exit animation and add enter animation
                navbarAnimatedText.classList.remove(currentAnimation.exit);
                navbarAnimatedText.classList.add(currentAnimation.enter);
                
                // Move to next word and animation combination
                navbarCurrentIndex = (navbarCurrentIndex + 1) % navbarWords.length;
                navbarAnimationIndex = (navbarAnimationIndex + 1) % navbarAnimations.length;
            }, 300);
        }
        
        // Start navbar animation
        if (navbarAnimatedText) {
            navbarAnimatedText.classList.add('zoom-in');
            
            // Fast cycling - change text every 1 second
            setInterval(animateNavbarText, 1000);
        }
        
    });
    </script>
    
</body>
</html>
