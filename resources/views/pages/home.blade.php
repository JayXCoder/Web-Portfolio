@extends('layouts.app')

@section('title', 'Home - Jawahar Ganesh @ Jay')
@section('description', 'Jawahar Ganesh @ Jay - Computer Engineer graduate from UniMAP Malaysia. Self-taught Full-Stack Developer specializing in Laravel, React, Python, AI/ML, cybersecurity, and embedded systems. View my portfolio projects and get in touch for development services.')
@section('keywords', 'Jawahar Ganesh, JayXCoder, Computer Engineer Malaysia, UniMAP graduate, Full-Stack Developer Malaysia, Software Engineer Malaysia, Self-taught programmer, Laravel developer, React developer, Python developer, AI ML developer, Cybersecurity Malaysia, Embedded systems, Arduino Raspberry Pi, Web development Malaysia, Mobile app development, Portfolio Malaysia')
@section('og_type', 'website')

@section('structured_data')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "Person",
  "name": "Jawahar Ganesh @ Jay",
  "alternateName": "JayXCoder",
  "jobTitle": "Full-Stack Developer & Software Engineer",
  "description": "Professional Full-Stack Developer, Software Engineer, and Technical Consultant specializing in web development, mobile apps, AI/ML, and embedded systems.",
  "url": "{{ url('/') }}",
  "sameAs": [
    "https://github.com/jayxcoder",
    "https://linkedin.com/in/jawaharganesh"
  ],
  "knowsAbout": [
    "Web Development",
    "Mobile App Development", 
    "AI/ML",
    "Embedded Systems",
    "Laravel",
    "React",
    "Python",
    "JavaScript"
  ],
  "hasOccupation": {
    "@@type": "Occupation",
    "name": "Software Engineer",
    "occupationLocation": {
      "@@type": "Country",
      "name": "Malaysia"
    }
  }
}
</script>
@endsection

@push('styles')
<style>
    /* ========================================
       FULLSCREEN ANIMATED TEXT CONTAINER
       ======================================== */
    /* Main container that fills hero section but only displays content closer to center */
    .animated-text-container {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding-right: 15%;
        z-index: 1; /* Behind hero content but above hero background */
        overflow: hidden;
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
        background: transparent !important;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        pointer-events: none; /* Don't interfere with user interactions */
    }

    /* Ensure hero section has relative positioning for absolute children */
    .hero {
        position: relative;
        overflow: hidden;
    }

    /* Hero image wrapper - ensures no inherited borders */
    .hero-image {
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
        background: transparent !important;
        margin: 0;
        padding: 0;
        width: 100%;
        height: 100%;
    }

    /* ========================================
       CODING ANIMATION CONTAINER
       ======================================== */
    .hero-spacer {
        height: 15rem;
        width: 100%;
        position: relative;
        z-index: 5;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Terminal-style coding window */
    .coding-terminal {
        background: rgba(0, 0, 0, 0.85);
        border: 1px solid #333;
        border-radius: 8px;
        padding: 1.5rem;
        width: 90%;
        max-width: 600px;
        font-family: 'Courier New', monospace;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        position: relative;
        overflow: hidden;
    }

    /* Terminal header */
    .terminal-header {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #333;
    }

    .terminal-dots {
        display: flex;
        gap: 0.5rem;
    }

    .terminal-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }

    .terminal-dot.red { background: #ff5f56; }
    .terminal-dot.yellow { background: #ffbd2e; }
    .terminal-dot.green { background: #27ca3f; }

    .terminal-title {
        color: #888;
        font-size: 0.9rem;
        margin-left: 1rem;
    }

    /* Code content area */
    .code-content {
        color: #f8f8f2;
        font-size: 0.95rem;
        line-height: 1.8;
        min-height: 8rem;
        position: relative;
        white-space: pre-line; /* Preserve line breaks and spacing */
        text-align: left !important; /* Force left alignment */
    }

    /* Terminal prompt */
    .terminal-prompt {
        color: #ff8c00 !important; /* Orange/Gold color */
        margin-right: 0;
        text-align: left !important; /* Force left alignment */
        display: inline !important; /* Make it inline with code */
        text-shadow: 0 0 8px #ff8c00; /* Orange glow effect */
    }
    
    /* Code output area - separate from prompt */
    .code-output {
        color: #00ff00 !important; /* Classic terminal green */
        font-family: 'Courier New', monospace;
        font-size: 0.95rem;
        line-height: 1.8;
        text-align: left;
        margin-top: 0.5rem;
        text-shadow: 0 0 5px #00ff00; /* Green glow effect */
    }

    /* Syntax highlighting - Make colors more prominent */
    .code-keyword { 
        color: #f92672 !important; 
        font-weight: bold !important;
    }      /* Python: def, if, for, etc. */
    .code-string { 
        color: #e6db74 !important; 
        font-weight: normal !important;
    }      /* Strings */
    .code-comment { 
        color: #75715e !important; 
        font-style: italic !important;
    }     /* Comments */
    .code-function { 
        color: #66d9ef !important; 
        font-weight: bold !important;
    }    /* Functions */
    .code-variable { 
        color: #fd971f !important; 
        font-weight: normal !important;
    }    /* Variables */
    .code-number { 
        color: #ae81ff !important; 
        font-weight: bold !important;
    }      /* Numbers */
    .code-operator { 
        color: #f92672 !important; 
        font-weight: bold !important;
    }    /* Operators */

    /* Cursor animation */
    .typing-cursor {
        display: inline-block;
        width: 8px;
        height: 1.2em;
        background: #f8f8f2;
        animation: blink 1s infinite;
        margin-left: 2px;
    }

    @keyframes blink {
        0%, 50% { opacity: 1; }
        51%, 100% { opacity: 0; }
    }

    /* Language indicator */
    .language-indicator {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(102, 217, 239, 0.2);
        color: #66d9ef;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.8rem;
        border: 1px solid #66d9ef;
    }

    /* Ensure hero content is above animation */
    .hero-content {
        position: relative;
        z-index: 10;
    }

    /* ========================================
       ANIMATED TEXT STYLES
       ======================================== */
    /* Main animated text element with diffused neon glow effect */
    .animated-text {
        font-size: clamp(4rem, 8vw, 8rem);
        font-weight: 900;
        color: var(--accent-primary);
        font-family: 'Inter', sans-serif;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        position: absolute;
        opacity: 0;
        transform: scale(0.5);
        /* Multi-layer diffused glow effect - 6 shadow layers for smooth neon appearance */
        text-shadow: 
            0 0 5px var(--accent-primary),   /* Inner glow */
            0 0 10px var(--accent-primary),  /* Mid glow */
            0 0 15px var(--accent-primary),  /* Outer glow */
            0 0 20px var(--accent-primary),  /* Extended glow */
            0 0 35px var(--accent-primary),  /* Far glow */
            0 0 40px var(--accent-primary);  /* Maximum glow */
        filter: blur(0.5px); /* Subtle blur for extra softness */
    }

    /* ========================================
       FAVICON INTEGRATION
       ======================================== */
    /* Animated favicon that appears alongside text */
    .animated-favicon {
        position: absolute;
        top: -1.5rem;
        right: -1.5rem;
        width: 4rem;
        height: 4rem;
        opacity: 0;
        transform: scale(0.3) rotate(0deg);
        transition: all 0.3s ease-in-out;
        z-index: 15;
        filter: drop-shadow(0 0 10px var(--accent-primary));
        border-radius: 8px;
    }

    /* Favicon animation states */
    .animated-favicon.show {
        opacity: 1;
        transform: scale(1) rotate(360deg);
        animation: faviconPulse 0.5s ease-in-out;
        filter: drop-shadow(0 0 15px var(--accent-primary));
    }

    /* Favicon pulse animation */
    @keyframes faviconPulse {
        0% { 
            transform: scale(1) rotate(360deg); 
            filter: drop-shadow(0 0 15px var(--accent-primary));
        }
        50% { 
            transform: scale(1.2) rotate(180deg); 
            filter: drop-shadow(0 0 20px var(--accent-primary));
        }
        100% { 
            transform: scale(1) rotate(0deg); 
            filter: drop-shadow(0 0 15px var(--accent-primary));
        }
    }

    /* ========================================
       ANIMATION CLASSES - ENTRANCE EFFECTS
       ======================================== */
    /* Zoom In: Scales from small with rotation, bounces to oversize, settles */
    .animated-text.zoom-in {
        animation: zoomIn 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards,
                   pulse 0.5s ease-in-out infinite 0.4s;
    }

    /* Dash Left In: Dashes from left with overshoot and rotation */
    .animated-text.dash-left-in {
        animation: dashLeftIn 0.35s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards,
                   pulse 0.5s ease-in-out infinite 0.35s;
    }

    /* Spin In: Full 360° rotation while scaling */
    .animated-text.spin-in {
        animation: spinIn 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards,
                   pulse 0.5s ease-in-out infinite 0.4s;
    }

    /* Bounce In: Physics-based bounce with gravity simulation */
    .animated-text.bounce-in {
        animation: bounceIn 0.45s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards,
                   pulse 0.5s ease-in-out infinite 0.45s;
    }

    /* ========================================
       ANIMATION CLASSES - EXIT EFFECTS
       ======================================== */
    /* Zoom Out: Shrinks while rotating out */
    .animated-text.zoom-out {
        animation: zoomOut 0.3s ease-in forwards;
    }

    /* Dash Right Out: Dashes to right while rotating and shrinking */
    .animated-text.dash-right-out {
        animation: dashRightOut 0.3s ease-out forwards;
    }

    /* Flip Out: 3D flip effect (rotateY) while shrinking */
    .animated-text.flip-out {
        animation: flipOut 0.3s ease-in forwards;
    }

    /* Slide Up Out: Slides up and out while shrinking */
    .animated-text.slide-up-out {
        animation: slideUpOut 0.3s ease-in forwards;
    }

    /* Keyframe Animations */
    @keyframes zoomIn {
        0% {
            opacity: 0;
            transform: scale(0.3) rotate(10deg);
            text-shadow: 
                0 0 5px var(--accent-primary),
                0 0 10px var(--accent-primary),
                0 0 15px var(--accent-primary),
                0 0 20px var(--accent-primary),
                0 0 35px var(--accent-primary),
                0 0 40px var(--accent-primary);
        }
        50% {
            opacity: 0.8;
            transform: scale(1.1) rotate(-5deg);
            text-shadow: 
                0 0 8px var(--accent-primary),
                0 0 16px var(--accent-primary),
                0 0 24px var(--accent-primary),
                0 0 32px var(--accent-primary),
                0 0 48px var(--accent-primary),
                0 0 56px var(--accent-primary),
                0 0 64px var(--accent-secondary);
        }
        100% {
            opacity: 0.3;
            transform: scale(1) rotate(0deg);
            text-shadow: 
                0 0 5px var(--accent-primary),
                0 0 10px var(--accent-primary),
                0 0 15px var(--accent-primary),
                0 0 20px var(--accent-primary),
                0 0 35px var(--accent-primary),
                0 0 40px var(--accent-primary);
        }
    }

    @keyframes zoomOut {
        0% {
            opacity: 0.3;
            transform: scale(1) rotate(0deg);
        }
        100% {
            opacity: 0;
            transform: scale(0.2) rotate(-15deg);
        }
    }

    @keyframes dashLeftIn {
        0% {
            opacity: 0;
            transform: translateX(-200px) scale(0.5) rotate(-20deg);
            text-shadow: 
                0 0 5px var(--accent-primary),
                0 0 10px var(--accent-primary),
                0 0 15px var(--accent-primary),
                0 0 20px var(--accent-primary),
                0 0 35px var(--accent-primary),
                0 0 40px var(--accent-primary);
        }
        60% {
            opacity: 0.9;
            transform: translateX(20px) scale(1.05) rotate(5deg);
            text-shadow: 
                0 0 8px var(--accent-primary),
                0 0 16px var(--accent-primary),
                0 0 24px var(--accent-primary),
                0 0 32px var(--accent-primary),
                0 0 48px var(--accent-primary),
                0 0 56px var(--accent-primary),
                0 0 64px var(--accent-secondary);
        }
        100% {
            opacity: 0.3;
            transform: translateX(0) scale(1) rotate(0deg);
            text-shadow: 
                0 0 5px var(--accent-primary),
                0 0 10px var(--accent-primary),
                0 0 15px var(--accent-primary),
                0 0 20px var(--accent-primary),
                0 0 35px var(--accent-primary),
                0 0 40px var(--accent-primary);
        }
    }

    @keyframes dashRightOut {
        0% {
            opacity: 0.3;
            transform: translateX(0) scale(1) rotate(0deg);
        }
        100% {
            opacity: 0;
            transform: translateX(200px) scale(0.3) rotate(20deg);
        }
    }

    @keyframes spinIn {
        0% {
            opacity: 0;
            transform: rotate(360deg) scale(0.2);
            text-shadow: 
                0 0 5px var(--accent-primary),
                0 0 10px var(--accent-primary),
                0 0 15px var(--accent-primary),
                0 0 20px var(--accent-primary),
                0 0 35px var(--accent-primary),
                0 0 40px var(--accent-primary);
        }
        50% {
            opacity: 0.8;
            transform: rotate(180deg) scale(1.1);
            text-shadow: 
                0 0 8px var(--accent-primary),
                0 0 16px var(--accent-primary),
                0 0 24px var(--accent-primary),
                0 0 32px var(--accent-primary),
                0 0 48px var(--accent-primary),
                0 0 56px var(--accent-primary),
                0 0 64px var(--accent-secondary);
        }
        100% {
            opacity: 0.3;
            transform: rotate(0deg) scale(1);
            text-shadow: 
                0 0 5px var(--accent-primary),
                0 0 10px var(--accent-primary),
                0 0 15px var(--accent-primary),
                0 0 20px var(--accent-primary),
                0 0 35px var(--accent-primary),
                0 0 40px var(--accent-primary);
        }
    }

    @keyframes flipOut {
        0% {
            opacity: 0.3;
            transform: rotateY(0deg) scale(1);
        }
        100% {
            opacity: 0;
            transform: rotateY(90deg) scale(0.3);
        }
    }

    @keyframes bounceIn {
        0% {
            opacity: 0;
            transform: translateY(-100px) scale(0.3);
            text-shadow: 
                0 0 5px var(--accent-primary),
                0 0 10px var(--accent-primary),
                0 0 15px var(--accent-primary),
                0 0 20px var(--accent-primary),
                0 0 35px var(--accent-primary),
                0 0 40px var(--accent-primary);
        }
        60% {
            opacity: 0.8;
            transform: translateY(20px) scale(1.1);
            text-shadow: 
                0 0 8px var(--accent-primary),
                0 0 16px var(--accent-primary),
                0 0 24px var(--accent-primary),
                0 0 32px var(--accent-primary),
                0 0 48px var(--accent-primary),
                0 0 56px var(--accent-primary),
                0 0 64px var(--accent-secondary);
        }
        80% {
            opacity: 0.4;
            transform: translateY(-10px) scale(0.95);
        }
        100% {
            opacity: 0.3;
            transform: translateY(0) scale(1);
            text-shadow: 
                0 0 5px var(--accent-primary),
                0 0 10px var(--accent-primary),
                0 0 15px var(--accent-primary),
                0 0 20px var(--accent-primary),
                0 0 35px var(--accent-primary),
                0 0 40px var(--accent-primary);
        }
    }

    @keyframes slideUpOut {
        0% {
            opacity: 0.3;
            transform: translateY(0) scale(1);
        }
        100% {
            opacity: 0;
            transform: translateY(-100px) scale(0.3);
        }
    }

    @keyframes pulse {
        0%, 100% {
            text-shadow: 
                0 0 5px var(--accent-primary),
                0 0 10px var(--accent-primary),
                0 0 15px var(--accent-primary),
                0 0 20px var(--accent-primary),
                0 0 35px var(--accent-primary),
                0 0 40px var(--accent-primary);
            opacity: 0.3;
        }
        50% {
            text-shadow: 
                0 0 8px var(--accent-primary),
                0 0 16px var(--accent-primary),
                0 0 24px var(--accent-primary),
                0 0 32px var(--accent-primary),
                0 0 48px var(--accent-primary),
                0 0 56px var(--accent-primary),
                0 0 64px var(--accent-secondary);
            opacity: 0.4;
        }
    }

    /* ========================================
       ADDITIONAL VISUAL ELEMENTS
       ======================================== */
    /* Geometric shapes that appear with text */
    .animated-shape {
        position: absolute;
        opacity: 0;
        transition: all 0.3s ease-in-out;
        pointer-events: none;
    }

    .animated-shape.show {
        opacity: 0.6;
        animation: shapeFloat 2s ease-in-out infinite;
    }

    /* Shape floating animation */
    @keyframes shapeFloat {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-10px) rotate(180deg); }
    }

    /* Circle shape */
    .shape-circle {
        width: 20px;
        height: 20px;
        border: 2px solid var(--accent-primary);
        border-radius: 50%;
        top: -3rem;
        left: -3rem;
    }

    /* Triangle shape */
    .shape-triangle {
        width: 0;
        height: 0;
        border-left: 15px solid transparent;
        border-right: 15px solid transparent;
        border-bottom: 25px solid var(--accent-primary);
        bottom: -2rem;
        right: -3rem;
    }

    /* Square shape */
    .shape-square {
        width: 18px;
        height: 18px;
        border: 2px solid var(--accent-secondary);
        transform: rotate(45deg);
        bottom: -2.5rem;
        left: -2.5rem;
    }

    @media (max-width: 1200px) {
        .animated-text {
            font-size: clamp(3rem, 6vw, 6rem);
        }
    }

    @media (max-width: 768px) {
        .animated-text {
            font-size: clamp(2.5rem, 5vw, 4rem);
        }
        .animated-text-container {
            height: 12rem;
        }
    }

    @media (max-width: 576px) {
        .animated-text {
            font-size: clamp(2rem, 4vw, 3rem);
        }
        .animated-text-container {
            height: 10rem;
        }
    }

    @media (max-width: 400px) {
        .animated-text {
            font-size: clamp(1.5rem, 3.5vw, 2.5rem);
        }
        .animated-text-container {
            height: 8rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section with Integrated Animated Text -->
<section class="hero">
    
    <!-- Hero content positioned above animated background -->
    <div class="container" style="position: relative; z-index: 10;">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="hero-title">Jawahar Ganesh @ Jay</h1>
                    <h2 class="hero-subtitle">Computer Engineer & Self-Taught Programmer</h2>
                    <p class="hero-description">
                        A Computer Engineer graduate from University Malaysia Perlis with Bachelor Honours in Computer Engineering, 
                        and self-taught computer science programmer who learned everything through YouTube, internet guides, 
                        and hands-on final year projects. Passionate about AI, cybersecurity, and full-stack development.
                    </p>
                    <a href="{{ route('contact') }}" class="btn btn-primary-custom">Get In Touch</a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <!-- Coding animation terminal -->
                <div class="hero-spacer">
                    <div class="coding-terminal">
                        <!-- Terminal header -->
                        <div class="terminal-header">
                            <div class="terminal-dots">
                                <div class="terminal-dot red"></div>
                                <div class="terminal-dot yellow"></div>
                                <div class="terminal-dot green"></div>
                            </div>
                            <div class="terminal-title">jay@portfolio:~</div>
                        </div>
                        
                        <!-- Language indicator -->
                        <div class="language-indicator" id="languageIndicator">Python</div>
                        
                        <!-- Code content -->
                        <div class="code-content" id="codeContent">
                            <div class="terminal-prompt">
                                $><span id="typedCode">INITIAL TEST!</span><span class="typing-cursor"></span>
                            </div>
                        </div>
                        
                        <!-- Coding Animation JavaScript -->
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const typedCodeElement = document.getElementById('typedCode');
                                const languageIndicator = document.getElementById('languageIndicator');
                                
                                if (!typedCodeElement || !languageIndicator) return;
                                
                                const codeSnippets = [
                                    {
                                        language: 'Python',
                                        text: 'print("Hello! Welcome to my portfolio")\nprint("I\'m a Computer Engineer & Self-taught Programmer")\nprint("Click \'About\' to learn more about my journey")\n\ndef main():\n    print("Ready to explore my projects?")',
                                        color: '#3776ab'
                                    },
                                    {
                                        language: 'C++',
                                        text: '#include <iostream>\nusing namespace std;\n\nint main() {\n    cout << "Welcome to Jay\'s Portfolio!" << endl;\n    cout << "AI, Cybersecurity, Full-stack Development" << endl;\n    return 0;\n}',
                                        color: '#00599c'
                                    },
                                    {
                                        language: 'PHP',
                                        text: 'echo "Welcome to Jay\'s Digital Space!";\necho "\\nAI, Cybersecurity, Full-stack Development";\necho "\\nLet\'s build something amazing together!";\n\n// Visit Skills page to see my tech stack\n// Check out my projects in the Portfolio section',
                                        color: '#777bb4'
                                    },
                                    {
                                        language: 'JavaScript',
                                        text: 'console.log(\'Hello World!\');\nconsole.log(\'I code in multiple languages\');\nconsole.log(\'From Python to JavaScript, I love it all\');\n\nconst message = \'Ready to see my projects?\';\nalert(message);',
                                        color: '#f7df1e'
                                    }
                                ];
                                
                                let currentIndex = 0;
                                let isTyping = false;
                                
                                function typeCode(snippet, callback) {
                                    if (isTyping) return;
                                    isTyping = true;
                                    
                                    languageIndicator.textContent = snippet.language;
                                    languageIndicator.style.borderColor = snippet.color;
                                    typedCodeElement.textContent = '';
                                    
                                    let index = 0;
                                    const fullText = snippet.text;
                                    
                                    function typeChar() {
                                        if (index < fullText.length) {
                                            typedCodeElement.textContent += fullText[index];
                                            index++;
                                            setTimeout(typeChar, Math.random() * 30 + 20);
                                        } else {
                                            // Simple green terminal style - no complex colors
                                            isTyping = false;
                                            setTimeout(callback, 2000);
                                        }
                                    }
                                    typeChar();
                                }
                                
                                function applySyntaxHighlighting(language) {
                                    const text = typedCodeElement.textContent;
                                    let highlightedText = text;
                                    
                                    if (language === 'Python') {
                                        highlightedText = text
                                            .replace(/\b(print|def|if|for|while|return|import|from|class)\b/g, '<span style="color: #66d9ef !important; font-weight: bold !important;">$1</span>')
                                            .replace(/"([^"]*)"/g, '<span style="color: #e6db74 !important;">"$1"</span>')
                                            .replace(/\b(main)\b/g, '<span style="color: #66d9ef !important; font-weight: bold !important;">$1</span>');
                                    } else if (language === 'C++') {
                                        // Apply highlighting in correct order to avoid conflicts
                                        highlightedText = text
                                            .replace(/\b(iostream|std|main)\b/g, '<span style="color: #66d9ef !important; font-weight: bold !important;">$1</span>')
                                            .replace(/"([^"]*)"/g, '<span style="color: #e6db74 !important;">"$1"</span>')
                                            .replace(/\b(0)\b/g, '<span style="color: #ae81ff !important; font-weight: bold !important;">$1</span>')
                                            .replace(/\b(#include|using|namespace|int|return|cout|endl)\b/g, '<span style="color: #f92672 !important; font-weight: bold !important;">$1</span>');
                                    } else if (language === 'PHP') {
                                        highlightedText = text
                                            .replace(/"([^"]*)"/g, '<span style="color: #e6db74 !important;">"$1"</span>')
                                            .replace(/\/\/(.*)/g, '<span style="color: #75715e !important; font-style: italic !important;">//$1</span>')
                                            .replace(/\b(echo)\b/g, '<span style="color: #f92672 !important; font-weight: bold !important;">$1</span>');
                                    } else if (language === 'JavaScript') {
                                        highlightedText = text
                                            .replace(/'([^']*)'/g, '<span style="color: #e6db74 !important;">\'$1\'</span>')
                                            .replace(/\b(log|message)\b/g, '<span style="color: #fd971f !important;">$1</span>')
                                            .replace(/\b(const|let|var|function|console|alert)\b/g, '<span style="color: #66d9ef !important; font-weight: bold !important;">$1</span>');
                                    }
                                    
                                    // Apply highlighting with a delay to make it smooth
                                    setTimeout(() => {
                                        typedCodeElement.innerHTML = highlightedText;
                                    }, 500);
                                }
                                
                                function cycleSnippets() {
                                    if (currentIndex >= codeSnippets.length) {
                                        currentIndex = 0;
                                    }
                                    typeCode(codeSnippets[currentIndex], () => {
                                        currentIndex++;
                                        cycleSnippets();
                                    });
                                }
                                
                                // Test with a simple colored snippet first
                                typedCodeElement.innerHTML = '<span style="color: #f92672 !important; font-weight: bold !important;">#include</span> &lt;<span style="color: #fd971f !important;">iostream</span>&gt;';
                                
                                // Start after 3 seconds
                                setTimeout(cycleSnippets, 3000);
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="scroll-indicator">
        <i class="fas fa-chevron-down" style="color: var(--accent-primary); font-size: 1.5rem;"></i>
    </div>
</section>

<!-- Quick Overview Section -->
<section class="section">
    <div class="container">
        <h2 class="section-title">Quick Overview</h2>
        <div class="row">
            <div class="col-lg-4">
                <div class="card-custom">
                    <i class="fas fa-graduation-cap card-icon"></i>
                    <h3 class="card-title">Education & Learning</h3>
                    <p class="card-text">
                        Bachelor Honours in Computer Engineering graduate from University Malaysia Perlis (UniMAP), 
                        self-taught programmer through YouTube tutorials, internet guides, and practical final year projects as side business ventures.
                    </p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card-custom">
                    <i class="fas fa-robot card-icon"></i>
                    <h3 class="card-title">AI & Development</h3>
                    <p class="card-text">
                        AI Model training, multi-language programming, Python & Java app development, 
                        full-stack web programming, and Docker containerization.
                    </p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card-custom">
                    <i class="fas fa-microchip card-icon"></i>
                    <h3 class="card-title">Hardware & Infrastructure</h3>
                    <p class="card-text">
                        Hardware projects with Arduino and Raspberry Pi, server making and maintenance, 
                        cybersecurity analysis, and system architecture.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ========================================
    // ANIMATED TEXT SYSTEM INITIALIZATION
    // ========================================
    
    // DOM element references
    const animatedText = document.getElementById('animatedText');
    const animatedFavicon = document.getElementById('animatedFavicon');
    const shapeCircle = document.getElementById('shapeCircle');
    const shapeTriangle = document.getElementById('shapeTriangle');
    const shapeSquare = document.getElementById('shapeSquare');
    
    // Word array for cycling animation
    const words = [
        'Code', 'Design', 'Innovate', 'Create', 'Build', 'Develop', 
        'Engineer', 'Solve', 'Optimize', 'Automate', 'Secure', 'Deploy'
    ];
    
    // Animation combinations - each word gets different entrance/exit effects
    const animations = [
        { enter: 'zoom-in', exit: 'zoom-out' },
        { enter: 'dash-left-in', exit: 'dash-right-out' },
        { enter: 'spin-in', exit: 'flip-out' },
        { enter: 'bounce-in', exit: 'slide-up-out' }
    ];
    
    // Animation state tracking
    let currentIndex = 0;
    let animationIndex = 0;
    
    // ========================================
    // MAIN ANIMATION FUNCTION
    // ========================================
    function animateText() {
        const currentAnimation = animations[animationIndex];
        
        // Remove all animation classes from text
        animatedText.className = 'animated-text';
        
        // Hide all visual elements during transition
        animatedFavicon.classList.remove('show');
        shapeCircle.classList.remove('show');
        shapeTriangle.classList.remove('show');
        shapeSquare.classList.remove('show');
        
        // Add exit animation to current text
        animatedText.classList.add(currentAnimation.exit);
        
        // Wait for exit animation to complete (0.3s)
        setTimeout(() => {
            // Change text content
            animatedText.textContent = words[currentIndex];
            
            // Remove exit animation and add enter animation
            animatedText.classList.remove(currentAnimation.exit);
            animatedText.classList.add(currentAnimation.enter);
            
            // Show all visual elements with staggered animation
            setTimeout(() => {
                animatedFavicon.classList.add('show');
            }, 100);
            
            setTimeout(() => {
                shapeCircle.classList.add('show');
            }, 200);
            
            setTimeout(() => {
                shapeTriangle.classList.add('show');
            }, 300);
            
            setTimeout(() => {
                shapeSquare.classList.add('show');
            }, 400);
            
            // Move to next word and animation combination
            currentIndex = (currentIndex + 1) % words.length;
            animationIndex = (animationIndex + 1) % animations.length;
        }, 300); // Faster exit timing
    }
    
    // ========================================
    // SPARKLE EFFECT SYSTEM
    // ========================================
    function createSparkle() {
        const sparkle = document.createElement('div');
        sparkle.style.position = 'absolute';
        sparkle.style.width = '4px';
        sparkle.style.height = '4px';
        sparkle.style.background = 'var(--accent-primary)';
        sparkle.style.borderRadius = '50%';
        sparkle.style.left = Math.random() * 100 + '%';
        sparkle.style.top = Math.random() * 100 + '%';
        sparkle.style.opacity = '0.8';
        sparkle.style.boxShadow = '0 0 10px var(--accent-primary)';
        sparkle.style.animation = 'sparkleFade 1.5s ease-out forwards';
        sparkle.style.pointerEvents = 'none';
        sparkle.style.zIndex = '5';
        
        document.querySelector('.animated-text-container').appendChild(sparkle);
        
        // Remove sparkle after animation
        setTimeout(() => {
            if (sparkle.parentNode) {
                sparkle.remove();
            }
        }, 1500);
    }
    
    // Add sparkle animation CSS dynamically
    const sparkleStyle = document.createElement('style');
    sparkleStyle.textContent = `
        @keyframes sparkleFade {
            0% {
                opacity: 0.8;
                transform: scale(0);
            }
            50% {
                opacity: 1;
                transform: scale(1);
            }
            100% {
                opacity: 0;
                transform: scale(0);
            }
        }
    `;
    document.head.appendChild(sparkleStyle);
    
    // ========================================
    // ANIMATION INITIALIZATION
    // ========================================
    
    // Start with first animation and show all visual elements
    animatedText.classList.add('zoom-in');
    setTimeout(() => {
        animatedFavicon.classList.add('show');
    }, 200);
    
    setTimeout(() => {
        shapeCircle.classList.add('show');
    }, 300);
    
    setTimeout(() => {
        shapeTriangle.classList.add('show');
    }, 400);
    
    setTimeout(() => {
        shapeSquare.classList.add('show');
    }, 500);
    
    // Fast cycling - change text every 1 second (0.5s display + 0.5s transition)
    setInterval(animateText, 1000);
    
    // Create sparkles every 2-4 seconds for extra visual flair
    setInterval(createSparkle, Math.random() * 2000 + 2000);
    
    // ========================================
    // CODING ANIMATION SYSTEM
    // ========================================
    
    // Simple test - just change the text
    setTimeout(function() {
        const typedCodeElement = document.getElementById('typedCode');
        if (typedCodeElement) {
            typedCodeElement.innerHTML = '<span style="color: #ff0000 !important; font-size: 1.2rem !important; font-weight: bold !important;">JavaScript is working!</span>';
        }
    }, 2000);
    
}); // Close the DOMContentLoaded event listener
</script>
@endpush
