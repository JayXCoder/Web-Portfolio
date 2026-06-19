<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $achievements = [
            [
                'organization' => 'IBM SkillsBuild',
                'type' => 'certificate',
                'title' => 'Cloud Computing Fundamentals',
                'issued_date' => '2026-02-25',
                'credly_url' => 'https://www.credly.com/org/ibm-skillsbuild/badge/cloud-computing-fundamentals',
                'image_url' => 'https://images.credly.com/images/5624b38a-5471-4d5c-a2bd-f4575babaa61/image.png',
                'story' => 'Completed IBM SkillsBuild coursework on cloud services, deployment models, containerization, and cloud security. Applied the concepts directly when containerizing this portfolio with Docker, Nginx, and PHP-FPM, and when thinking through how to deploy AI services and web apps reliably in production.',
                'project' => 'Web Portfolio — Dockerized Laravel stack with Nginx reverse proxy and volume-backed storage.',
                'skills' => ['Docker', 'Cloud deployment', 'Container orchestration', 'Cloud security'],
                'sort_order' => 1,
                'is_published' => true,
            ],
            [
                'organization' => 'IBM SkillsBuild',
                'type' => 'certificate',
                'title' => 'Cybersecurity Fundamentals',
                'issued_date' => '2025-08-19',
                'credly_url' => 'https://www.credly.com/org/ibm-skillsbuild/badge/cybersecurity-fundamentals',
                'image_url' => 'https://images.credly.com/images/50b96632-6cbb-40b7-ac0e-b83f49ff7f94/image.png',
                'story' => 'Studied threat landscapes, attack types, social engineering, cryptography, and organizational security strategies. I use this foundation when hardening web apps — CSRF protection, HTTPS enforcement, admin access controls, and secure handling of user data across my full-stack projects.',
                'project' => 'Portfolio admin panel — role-based access, forced HTTPS, and visitor tracking with privacy-aware analytics.',
                'skills' => ['Web security', 'Cryptography', 'Threat modeling', 'Secure SDLC'],
                'sort_order' => 2,
                'is_published' => true,
            ],
            [
                'organization' => 'IBM SkillsBuild',
                'type' => 'certificate',
                'title' => 'Getting Started with Cybersecurity',
                'issued_date' => '2025-08-19',
                'credly_url' => 'https://www.credly.com/org/ibm-skillsbuild/badge/getting-started-with-cybersecurity',
                'image_url' => 'https://images.credly.com/images/0462da0b-41f3-4542-b312-b2fc69869129/Getting_20Started_20With_20CybersecurityBadge.png',
                'story' => 'Built a baseline in the CIA triad (confidentiality, integrity, availability), data privacy principles, and how security tooling fits into real workflows. This shaped how I evaluate every feature — from contact form validation to AI chat endpoints — before shipping.',
                'project' => null,
                'skills' => ['CIA triad', 'Data privacy', 'Security tooling'],
                'sort_order' => 3,
                'is_published' => true,
            ],
            [
                'organization' => 'IBM SkillsBuild',
                'type' => 'certificate',
                'title' => 'Project Management Fundamentals',
                'issued_date' => '2025-08-19',
                'credly_url' => 'https://www.credly.com/org/ibm-skillsbuild/badge/project-management-fundamentals.4',
                'image_url' => 'https://images.credly.com/images/a4f13de3-9fc7-4d94-8f31-076999c2d06e/BadgeEmblem_ProjectManagementFundamentals.png',
                'story' => 'Learned project lifecycle management — charter, WBS, communication plans, and change control. I apply this when scoping portfolio features end-to-end: from AI chat integration and visitor analytics to admin CRUD flows, breaking work into deliverables and tracking progress through deployment.',
                'project' => 'AI Chat & Portfolio AI — scoped, built, and shipped LLM-powered portfolio tooling with async job tracking.',
                'skills' => ['Project planning', 'WBS', 'Agile delivery', 'Stakeholder communication'],
                'sort_order' => 4,
                'is_published' => true,
            ],
            [
                'organization' => 'IBM SkillsBuild',
                'type' => 'certificate',
                'title' => 'Web Development Fundamentals',
                'issued_date' => '2025-08-19',
                'credly_url' => 'https://www.credly.com/org/ibm-skillsbuild/badge/web-development-fundamentals',
                'image_url' => 'https://images.credly.com/images/0c1c6eed-818c-4f78-bfaa-7ea8704c863a/image.png',
                'story' => 'Covered HTML, CSS, JavaScript, and the full web development lifecycle — build, test, deploy. This credential aligns with the hands-on work I do daily: shipping responsive UIs with Tailwind, Laravel backends, and interactive front-ends across React, Angular, and vanilla JS.',
                'project' => 'JayXCoder Portfolio — responsive Blade + Tailwind site with Vite, dynamic skill tree, and admin dashboard.',
                'skills' => ['HTML', 'CSS', 'JavaScript', 'Laravel', 'Tailwind CSS'],
                'sort_order' => 5,
                'is_published' => true,
            ],
        ];

        foreach ($achievements as $achievement) {
            Achievement::updateOrCreate(
                [
                    'organization' => $achievement['organization'],
                    'title' => $achievement['title'],
                ],
                $achievement
            );
        }
    }
}
