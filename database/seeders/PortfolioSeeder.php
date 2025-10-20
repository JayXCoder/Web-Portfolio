<?php

namespace Database\Seeders;

use App\Models\Portfolio;
use Illuminate\Database\Seeder;

class PortfolioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $portfolios = [
            [
                'title' => 'ExamX Protocol - AI Exam Integrity System',
                'slug' => 'examx-protocol',
                'short_description' => 'Revolutionary examination system with AI-powered integrity monitoring using gaze detection and speech analysis to prevent cheating.',
                'description' => 'ExamX Protocol is a cutting-edge examination system that leverages advanced AI technologies to ensure academic integrity during online exams. The system combines computer vision with natural language processing to monitor student behavior in real-time, detecting potential cheating attempts through gaze tracking and speech analysis. Built entirely in Python, it represents a breakthrough in educational technology.',
                'technologies' => ['Python', 'PyQt6', 'OpenCV', 'TensorFlow', 'Whisper AI', 'Firebase', 'Google Sheets', 'Custom ML Models', 'Real-time Processing'],
                'category' => 'AI/ML',
                'images' => [
                    'https://via.placeholder.com/800x600/8b5cf6/ffffff?text=Exam+Interface',
                    'https://via.placeholder.com/800x600/a855f7/ffffff?text=AI+Monitoring+Dashboard',
                    'https://via.placeholder.com/800x600/c084fc/ffffff?text=Integrity+Analytics'
                ],
                'features' => [
                    'Real-time gaze tracking and analysis',
                    'Speech detection and conversation monitoring',
                    'Custom integrity percentage calculation algorithm',
                    'PyQt6-based secure exam interface',
                    'Firebase integration for data storage',
                    'Automatic Google Sheets reporting',
                    'Fine-tuned with real UniMAP student data',
                    '100% Python implementation'
                ],
                'duration_months' => 3,
                'client' => 'University Malaysia Perlis (UniMAP)',
                'challenges' => 'Creating an accurate AI system that could detect cheating behaviors without false positives, while maintaining smooth exam experience and real-time processing capabilities.',
                'solutions' => 'Developed custom TensorFlow models for gaze detection, integrated OpenAI Whisper for speech recognition, and created a proprietary algorithm for integrity scoring. Used PyQt6 for the exam interface and Firebase for real-time data storage.',
                'is_featured' => true,
                'is_published' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'AI-Powered Security Analysis System',
                'slug' => 'ai-security-analysis',
                'short_description' => 'A comprehensive machine learning system that analyzes network traffic patterns and detects potential security threats in real-time.',
                'description' => 'This project involved developing a sophisticated AI system that monitors network traffic and identifies anomalous patterns that could indicate security breaches. The system uses deep learning algorithms to classify network behavior and alert administrators to potential threats.',
                'technologies' => ['Python', 'TensorFlow', 'Django', 'Docker', 'Linux', 'Keras', 'Scikit-learn'],
                'category' => 'AI/ML',
                'images' => [
                    'https://via.placeholder.com/800x600/8b5cf6/ffffff?text=System+Architecture',
                    'https://via.placeholder.com/800x600/a855f7/ffffff?text=Dashboard+View',
                    'https://via.placeholder.com/800x600/c084fc/ffffff?text=Threat+Detection'
                ],
                'features' => [
                    'Real-time network monitoring',
                    'AI-powered threat detection',
                    'Interactive dashboard',
                    'Automated alert system',
                    'Historical data analysis'
                ],
                'duration_months' => 3,
                'client' => 'Academic Project / Side Business',
                'challenges' => 'The main challenge was optimizing the model for real-time processing while maintaining high accuracy in threat detection.',
                'solutions' => 'Implemented a hybrid approach combining traditional machine learning with deep learning, using Docker for consistent deployment and Django for the web interface.',
                'is_featured' => true,
                'is_published' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'IoT Smart Home Automation',
                'slug' => 'iot-smart-home',
                'short_description' => 'Complete smart home system using Arduino and Raspberry Pi with web-based control interface.',
                'description' => 'A comprehensive IoT solution that integrates multiple smart devices and sensors to create an intelligent home automation system. The project combines hardware development with web technologies to provide seamless control and monitoring capabilities.',
                'technologies' => ['Arduino', 'Raspberry Pi', 'Python', 'JavaScript', 'React', 'MQTT', 'Node.js'],
                'category' => 'Hardware/IoT',
                'images' => [
                    'https://via.placeholder.com/800x600/a855f7/ffffff?text=IoT+Home',
                    'https://via.placeholder.com/800x600/8b5cf6/ffffff?text=Control+Panel',
                    'https://via.placeholder.com/800x600/c084fc/ffffff?text=Device+Network'
                ],
                'features' => [
                    'Remote device control',
                    'Sensor data monitoring',
                    'Automated scheduling',
                    'Mobile app interface',
                    'Voice control integration',
                    'Energy usage tracking'
                ],
                'duration_months' => 2,
                'client' => 'Personal Project',
                'challenges' => 'Integrating multiple hardware platforms and ensuring reliable communication between devices.',
                'solutions' => 'Used MQTT protocol for device communication and created a unified web interface for device management.',
                'is_featured' => true,
                'is_published' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'Full-Stack E-Commerce Platform',
                'slug' => 'ecommerce-platform',
                'short_description' => 'Secure e-commerce platform with payment integration, inventory management, and admin dashboard.',
                'description' => 'A complete e-commerce solution built from scratch with modern web technologies. Features include user authentication, product management, shopping cart, payment processing, and comprehensive admin dashboard.',
                'technologies' => ['Laravel', 'React', 'MySQL', 'Docker', 'Stripe API', 'Redis'],
                'category' => 'Web Development',
                'images' => [
                    'https://via.placeholder.com/800x600/c084fc/ffffff?text=E-Commerce',
                    'https://via.placeholder.com/800x600/8b5cf6/ffffff?text=Admin+Dashboard',
                    'https://via.placeholder.com/800x600/a855f7/ffffff?text=Payment+System'
                ],
                'features' => [
                    'User authentication & authorization',
                    'Product catalog management',
                    'Shopping cart functionality',
                    'Payment gateway integration',
                    'Order management system',
                    'Admin dashboard',
                    'Inventory tracking'
                ],
                'duration_months' => 4,
                'client' => 'Freelance Project',
                'challenges' => 'Implementing secure payment processing and managing complex user workflows.',
                'solutions' => 'Used Laravel for backend API, React for frontend, and integrated Stripe for secure payment processing.',
                'is_featured' => false,
                'is_published' => true,
                'sort_order' => 4,
            ],
            [
                'title' => 'Cybersecurity Penetration Testing Tool',
                'slug' => 'cyber-pentest-tool',
                'short_description' => 'Custom penetration testing tool for vulnerability assessment and security auditing.',
                'description' => 'A specialized tool designed for cybersecurity professionals to conduct comprehensive penetration tests. The system automates common vulnerability scans and provides detailed reporting for security assessments.',
                'technologies' => ['Python', 'Linux', 'Network Security', 'Docker', 'Nmap', 'Metasploit'],
                'category' => 'Cybersecurity',
                'images' => [
                    'https://via.placeholder.com/800x600/8b5cf6/ffffff?text=Cybersecurity',
                    'https://via.placeholder.com/800x600/a855f7/ffffff?text=Vulnerability+Scan',
                    'https://via.placeholder.com/800x600/c084fc/ffffff?text=Security+Report'
                ],
                'features' => [
                    'Automated vulnerability scanning',
                    'Network reconnaissance',
                    'Exploit testing',
                    'Report generation',
                    'Multi-threaded scanning',
                    'Custom payload creation'
                ],
                'duration_months' => 2,
                'client' => 'Academic Research',
                'challenges' => 'Ensuring ethical use while maintaining powerful testing capabilities.',
                'solutions' => 'Implemented strict authorization controls and comprehensive logging for all testing activities.',
                'is_featured' => true,
                'is_published' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($portfolios as $portfolioData) {
            Portfolio::create($portfolioData);
        }
    }
}