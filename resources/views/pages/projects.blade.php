@extends('layouts.app')

@section('title', 'Projects - Jawahar Ganesh @ Jay')

@section('content')
<section class="section" style="padding-top: 150px;">
    <div class="container">
        <h2 class="section-title">Featured Projects</h2>
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <p style="color: var(--text-secondary); font-size: 1.1rem;">
                    Here are some of my notable projects spanning AI, cybersecurity, full-stack development, 
                    and hardware solutions - all developed through self-learning and entrepreneurial ventures:
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <div class="card-custom">
                    <i class="fas fa-robot card-icon"></i>
                    <h3 class="card-title">AI Model Training Projects</h3>
                    <p class="card-text">
                        Developing and training machine learning models for various applications. 
                        From computer vision to natural language processing, creating AI solutions 
                        that solve real-world problems.
                    </p>
                    <div class="mt-3">
                        <span class="badge" style="background: var(--accent-primary); color: var(--dark-bg);">AI/ML</span>
                        <span class="badge" style="background: var(--accent-secondary); color: white;">Python</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card-custom">
                    <i class="fas fa-microchip card-icon"></i>
                    <h3 class="card-title">Hardware IoT Solutions</h3>
                    <p class="card-text">
                        Building complete IoT systems using Arduino and Raspberry Pi. Creating 
                        smart devices, automation systems, and hardware prototypes for various 
                        applications and client projects.
                    </p>
                    <div class="mt-3">
                        <span class="badge" style="background: var(--accent-primary); color: var(--dark-bg);">Hardware</span>
                        <span class="badge" style="background: var(--accent-secondary); color: white;">IoT</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card-custom">
                    <i class="fas fa-briefcase card-icon"></i>
                    <h3 class="card-title">Side Business Ventures</h3>
                    <p class="card-text">
                        Converting final year projects into profitable side businesses. Building 
                        custom software solutions, web applications, and providing technical 
                        consulting services to clients.
                    </p>
                    <div class="mt-3">
                        <span class="badge" style="background: var(--accent-primary); color: var(--dark-bg);">Business</span>
                        <span class="badge" style="background: var(--accent-secondary); color: white;">Consulting</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-lg-6">
                <div class="card-custom">
                    <i class="fas fa-shield-alt card-icon"></i>
                    <h3 class="card-title">Cybersecurity Analysis</h3>
                    <p class="card-text">
                        Conducting security assessments and vulnerability analysis for various 
                        systems. Implementing security measures and developing threat detection 
                        solutions for client infrastructure.
                    </p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card-custom">
                    <i class="fas fa-server card-icon"></i>
                    <h3 class="card-title">Server & Infrastructure</h3>
                    <p class="card-text">
                        Building and maintaining server infrastructure using Docker containers. 
                        Setting up scalable systems, implementing CI/CD pipelines, and managing 
                        cloud-based solutions for various applications.
                    </p>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-lg-12">
                <div class="card-custom text-center">
                    <i class="fas fa-rocket card-icon"></i>
                    <h3 class="card-title">Future Projects</h3>
                    <p class="card-text">
                        Continuously exploring new technologies and expanding my skill set. Currently 
                        focusing on advanced AI applications, edge computing solutions, and innovative 
                        hardware-software integration projects for the next generation of smart systems.
                    </p>
                    <a href="{{ route('contact') }}" class="btn btn-primary-custom mt-3">Collaborate with Me</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
