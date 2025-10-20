@extends('layouts.app')

@section('title', 'Skills - Jawahar Ganesh @ Jay')

@section('content')
<section class="section" style="padding-top: 150px; background: var(--dark-secondary);">
    <div class="container">
        <h2 class="section-title">Skills & Technologies</h2>
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <p style="color: var(--text-secondary); font-size: 1.1rem;">
                    Bachelor Honours in Computer Engineering graduate from University Malaysia Perlis (UniMAP) and 
                    self-taught programmer with a comprehensive skill set spanning AI, cybersecurity, 
                    full-stack development, and hardware. Here are the technologies I've mastered:
                </p>
            </div>
        </div>

        <div class="skills-grid">
            <div class="skill-item">
                <i class="fas fa-robot skill-icon"></i>
                <h4>AI Model Training</h4>
                <p>Machine Learning, Deep Learning, Model Deployment</p>
            </div>
            <div class="skill-item">
                <i class="fas fa-shield-alt skill-icon"></i>
                <h4>Cybersecurity Analyst</h4>
                <p>Security Analysis, Vulnerability Assessment, Threat Detection</p>
            </div>
            <div class="skill-item">
                <i class="fas fa-code skill-icon"></i>
                <h4>Multi-Language Programmer</h4>
                <p>Python, Java, JavaScript, C++, PHP, SQL</p>
            </div>
            <div class="skill-item">
                <i class="fab fa-python skill-icon"></i>
                <h4>Python App Development</h4>
                <p>Django, Flask, FastAPI, Data Science, Automation</p>
            </div>
            <div class="skill-item">
                <i class="fab fa-docker skill-icon"></i>
                <h4>Docker Maker</h4>
                <p>Containerization, Orchestration, DevOps</p>
            </div>
            <div class="skill-item">
                <i class="fas fa-server skill-icon"></i>
                <h4>Server Making & Maintenance</h4>
                <p>Linux, Ubuntu, CentOS, Nginx, Apache</p>
            </div>
            <div class="skill-item">
                <i class="fas fa-laptop-code skill-icon"></i>
                <h4>Full Stack Web Developer</h4>
                <p>Frontend, Backend, Databases, APIs</p>
            </div>
            <div class="skill-item">
                <i class="fab fa-java skill-icon"></i>
                <h4>Java App Development</h4>
                <p>Spring Boot, Android, Desktop Applications</p>
            </div>
            <div class="skill-item">
                <i class="fas fa-microchip skill-icon"></i>
                <h4>Hardware Projects</h4>
                <p>Arduino, Raspberry Pi, IoT, Electronics</p>
            </div>
            <div class="skill-item">
                <i class="fas fa-database skill-icon"></i>
                <h4>Database Management</h4>
                <p>MySQL, PostgreSQL, MongoDB, Redis</p>
            </div>
            <div class="skill-item">
                <i class="fas fa-cloud skill-icon"></i>
                <h4>Cloud & DevOps</h4>
                <p>AWS, Azure, CI/CD, Infrastructure as Code</p>
            </div>
            <div class="skill-item">
                <i class="fas fa-mobile-alt skill-icon"></i>
                <h4>Mobile Development</h4>
                <p>Android, Flutter, React Native</p>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-lg-4">
                <div class="card-custom">
                    <i class="fas fa-graduation-cap card-icon"></i>
                    <h3 class="card-title">Self-Taught Expertise</h3>
                    <ul style="color: var(--text-secondary); text-align: left;">
                        <li>100% self-taught through YouTube and online resources</li>
                        <li>Hands-on learning through practical projects</li>
                        <li>Turning academic projects into side businesses</li>
                        <li>Continuous learning and skill development</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card-custom">
                    <i class="fas fa-briefcase card-icon"></i>
                    <h3 class="card-title">Entrepreneurial Skills</h3>
                    <ul style="color: var(--text-secondary); text-align: left;">
                        <li>Project management and client communication</li>
                        <li>Business development and solution delivery</li>
                        <li>Technical consulting and problem-solving</li>
                        <li>Full-stack solution architecture</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card-custom">
                    <i class="fas fa-certificate card-icon"></i>
                    <h3 class="card-title">Certifications & Badges</h3>
                    <p style="color: var(--text-secondary); text-align: left; margin-bottom: 1rem;">
                        Professional certifications and digital badges earned through various platforms and learning programs.
                    </p>
                    <a href="https://www.credly.com/users/jawahar-ganesh" target="_blank" class="btn btn-primary-custom" style="font-size: 0.9rem; padding: 8px 20px;">
                        <i class="fas fa-certificate me-2"></i>View on Credly
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
