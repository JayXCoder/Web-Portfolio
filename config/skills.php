<?php

/**
 * Skills page: horizontal AI tools + tree (apex → stacks → branches → languages).
 *
 * Icon fields per skill:
 * - slug + color → Simple Icons CDN (with automatic SVG fallback)
 * - icon → optional full URL override when CDN slug fails
 */
return [
    'ai_tools' => [
        ['label' => 'Cursor', 'slug' => 'cursor', 'color' => 'FFFFFF'],
        ['label' => 'GitHub Copilot', 'slug' => 'githubcopilot', 'color' => 'FFFFFF'],
        ['label' => 'Claude Code', 'slug' => 'anthropic', 'color' => 'CC785C'],
        ['label' => 'OpenAI Codex', 'slug' => 'openai', 'color' => '412991'],
    ],

    'tree' => [
        'apex' => [
            'title' => 'Full Stack',
            'subtitle' => 'Apps, APIs, and data layers I ship in production.',
            'skills' => [
                ['label' => 'Next.js', 'slug' => 'nextdotjs', 'color' => 'FFFFFF'],
                ['label' => 'Laravel', 'slug' => 'laravel', 'color' => 'FF2D20'],
                ['label' => 'React', 'slug' => 'react', 'color' => '61DAFB'],
                ['label' => 'Angular', 'slug' => 'angular', 'color' => 'DD0031'],
                ['label' => 'Node.js', 'slug' => 'nodedotjs', 'color' => '339933'],
                ['label' => 'FastAPI', 'slug' => 'fastapi', 'color' => '009688'],
                ['label' => 'Flask', 'slug' => 'flask', 'color' => 'FFFFFF'],
                ['label' => 'PostgreSQL', 'slug' => 'postgresql', 'color' => '4169E1'],
                ['label' => 'MySQL', 'slug' => 'mysql', 'color' => '4479A1'],
                ['label' => 'MongoDB', 'slug' => 'mongodb', 'color' => '47A248'],
                ['label' => 'Qdrant', 'slug' => 'qdrant', 'color' => 'DC244C'],
                ['label' => 'Redis', 'slug' => 'redis', 'color' => 'FF4438'],
                ['label' => 'Prisma', 'slug' => 'prisma', 'color' => '2D3748'],
                ['label' => 'GraphQL', 'slug' => 'graphql', 'color' => 'E10098'],
                ['label' => 'NestJS', 'slug' => 'nestjs', 'color' => 'E0234E'],
                ['label' => 'Express', 'slug' => 'express', 'color' => 'FFFFFF'],
            ],
        ],
        'stacks' => [
            'title' => 'Stacks & UI',
            'subtitle' => 'Framework combos and front-end layers (e.g. Next.js + FastAPI, Laravel + React).',
            'skills' => [
                ['label' => 'Vue', 'slug' => 'vuedotjs', 'color' => '4FC08D'],
                ['label' => 'Tailwind', 'slug' => 'tailwindcss', 'color' => '06B6D4'],
                ['label' => 'Bootstrap', 'slug' => 'bootstrap', 'color' => '7952B3'],
                ['label' => 'Streamlit', 'slug' => 'streamlit', 'color' => 'FF4B4B'],
                ['label' => 'REST APIs', 'slug' => 'postman', 'color' => 'FF6C37'],
                ['label' => 'Vite', 'slug' => 'vite', 'color' => '646CFF'],
                ['label' => 'Electron', 'slug' => 'electron', 'color' => '47848F'],
                ['label' => 'PyQt', 'slug' => 'qt', 'color' => '41CD52'],
            ],
        ],
        'branches' => [
            [
                'title' => 'AI / ML',
                'skills' => [
                    ['label' => 'PyTorch', 'slug' => 'pytorch', 'color' => 'EE4C2C'],
                    ['label' => 'TensorFlow', 'slug' => 'tensorflow', 'color' => 'FF6F00'],
                    ['label' => 'Hugging Face', 'slug' => 'huggingface', 'color' => 'FFD21E'],
                    ['label' => 'Qwen LLMs', 'slug' => 'qwen', 'color' => '615EFF'],
                    ['label' => 'Whisper', 'slug' => 'openai', 'color' => '412991'],
                    ['label' => 'Ollama', 'slug' => 'ollama', 'color' => 'FFFFFF'],
                    ['label' => 'vLLM', 'slug' => 'vllm', 'color' => 'FFFFFF'],
                    ['label' => 'SGLang', 'slug' => null, 'color' => 'a855f7'],
                    ['label' => 'LangChain', 'slug' => 'langchain', 'color' => '1C3C3C'],
                    ['label' => 'Scikit-learn', 'slug' => 'scikitlearn', 'color' => 'F7931E'],
                    ['label' => 'OpenCV', 'slug' => 'opencv', 'color' => '5C3EE8'],
                    ['label' => 'YOLO', 'slug' => 'ultralytics', 'color' => '00F200'],
                    ['label' => 'CUDA', 'slug' => 'nvidia', 'color' => '76B900'],
                    ['label' => 'AMD ROCm', 'slug' => 'amd', 'color' => 'ED1C24'],
                ],
            ],
            [
                'title' => 'Cybersecurity',
                'skills' => [
                    ['label' => 'Web Pentest', 'slug' => 'owasp', 'color' => 'FFFFFF'],
                    ['label' => 'Burp Suite', 'slug' => 'burpsuite', 'color' => 'FF6633'],
                    ['label' => 'Wireshark', 'slug' => 'wireshark', 'color' => '1679A7'],
                    ['label' => 'Kali Linux', 'slug' => 'kalilinux', 'color' => '557C94'],
                    ['label' => 'Metasploit', 'slug' => 'metasploit', 'color' => '2596CD'],
                    ['label' => 'Nmap', 'slug' => 'nmap', 'color' => '2A6041'],
                ],
            ],
            [
                'title' => 'DevOps',
                'skills' => [
                    ['label' => 'Kubernetes', 'slug' => 'kubernetes', 'color' => '326CE5'],
                    ['label' => 'Docker', 'slug' => 'docker', 'color' => '2496ED'],
                    ['label' => 'Helm', 'slug' => 'helm', 'color' => '0F1689'],
                    ['label' => 'Portainer', 'slug' => 'portainer', 'color' => '13BEF9'],
                    ['label' => 'Nginx', 'slug' => 'nginx', 'color' => '009639'],
                    ['label' => 'Linux', 'slug' => 'linux', 'color' => 'FCC624'],
                    ['label' => 'Git', 'slug' => 'git', 'color' => 'F05032'],
                    ['label' => 'CI/CD', 'slug' => 'githubactions', 'color' => '2088FF'],
                ],
            ],
            [
                'title' => 'Reverse Eng.',
                'skills' => [
                    ['label' => 'IDA Pro', 'slug' => null, 'color' => '00B0F0'],
                    ['label' => 'Ghidra', 'slug' => null, 'color' => 'a855f7'],
                    ['label' => 'x64dbg', 'slug' => null, 'color' => '4CAF50'],
                ],
            ],
            [
                'title' => 'Tools',
                'skills' => [
                    ['label' => 'Chrome', 'slug' => 'googlechrome', 'color' => '4285F4'],
                    ['label' => 'Playwright', 'slug' => 'playwright', 'color' => '2EAD33', 'icon' => 'https://cdn.jsdelivr.net/npm/simple-icons@11.15.0/icons/playwright.svg'],
                    ['label' => 'Puppeteer', 'slug' => 'puppeteer', 'color' => '40B5A4'],
                    ['label' => 'VS Code', 'slug' => 'visualstudiocode', 'color' => '007ACC', 'icon' => 'https://cdn.jsdelivr.net/npm/simple-icons@11.15.0/icons/visualstudiocode.svg'],
                    ['label' => 'FFmpeg', 'slug' => 'ffmpeg', 'color' => '007808'],
                    ['label' => 'npm', 'slug' => 'npm', 'color' => 'CB3837'],
                ],
            ],
            [
                'title' => 'Hardware',
                'skills' => [
                    ['label' => 'Arduino', 'slug' => 'arduino', 'color' => '00878F'],
                    ['label' => 'Raspberry Pi', 'slug' => 'raspberrypi', 'color' => 'A22846'],
                    ['label' => 'ESP32', 'slug' => 'espressif', 'color' => 'E7352C'],
                    ['label' => 'IoT', 'slug' => 'homeassistant', 'color' => '18BCF2'],
                ],
            ],
        ],
        'foundation' => [
            'title' => 'Languages',
            'subtitle' => 'Everything in the tree builds on these core languages.',
            'skills' => [
                ['label' => 'PHP', 'slug' => 'php', 'color' => '777BB4'],
                ['label' => 'JavaScript', 'slug' => 'javascript', 'color' => 'F7DF1E'],
                ['label' => 'TypeScript', 'slug' => 'typescript', 'color' => '3178C6'],
                ['label' => 'Python', 'slug' => 'python', 'color' => '3776AB'],
                ['label' => 'C/C++', 'slug' => 'cplusplus', 'color' => '00599C'],
                ['label' => 'SQL', 'slug' => 'mysql', 'color' => '4479A1'],
            ],
        ],
    ],
];
