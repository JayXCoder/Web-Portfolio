<?php

/**
 * Skill groups for the skills page.
 * Icons: https://cdn.simpleicons.org/{slug}/{color} (hex without #)
 * Slug null = use built-in fallback icon in the component.
 */
return [
    'groups' => [
        'Languages' => [
            ['label' => 'PHP', 'slug' => 'php', 'color' => '777BB4'],
            ['label' => 'JavaScript', 'slug' => 'javascript', 'color' => 'F7DF1E'],
            ['label' => 'TypeScript', 'slug' => 'typescript', 'color' => '3178C6'],
            ['label' => 'Python', 'slug' => 'python', 'color' => '3776AB'],
            ['label' => 'C/C++', 'slug' => 'cplusplus', 'color' => '00599C'],
            ['label' => 'SQL', 'slug' => 'mysql', 'color' => '4479A1'],
        ],
        'Web' => [
            ['label' => 'Laravel', 'slug' => 'laravel', 'color' => 'FF2D20'],
            ['label' => 'React', 'slug' => 'react', 'color' => '61DAFB'],
            ['label' => 'Vue', 'slug' => 'vuedotjs', 'color' => '4FC08D'],
            ['label' => 'REST APIs', 'slug' => 'postman', 'color' => 'FF6C37'],
            ['label' => 'Tailwind', 'slug' => 'tailwindcss', 'color' => '06B6D4'],
            ['label' => 'Bootstrap', 'slug' => 'bootstrap', 'color' => '7952B3'],
        ],
        'AI / Data' => [
            ['label' => 'Machine Learning', 'slug' => 'huggingface', 'color' => 'FFD21E'],
            ['label' => 'Ollama', 'slug' => 'ollama', 'color' => 'FFFFFF'],
            ['label' => 'TensorFlow', 'slug' => 'tensorflow', 'color' => 'FF6F00'],
            ['label' => 'Pandas', 'slug' => 'pandas', 'color' => '150458'],
            ['label' => 'OpenCV', 'slug' => 'opencv', 'color' => '5C3EE8'],
        ],
        'DevOps & Security' => [
            ['label' => 'Docker', 'slug' => 'docker', 'color' => '2496ED'],
            ['label' => 'Linux', 'slug' => 'linux', 'color' => 'FCC624'],
            ['label' => 'Git', 'slug' => 'git', 'color' => 'F05032'],
            ['label' => 'CI/CD', 'slug' => 'githubactions', 'color' => '2088FF'],
            ['label' => 'Penetration Testing', 'slug' => 'kalilinux', 'color' => '557C94'],
        ],
        'Hardware' => [
            ['label' => 'Arduino', 'slug' => 'arduino', 'color' => '00878F'],
            ['label' => 'Raspberry Pi', 'slug' => 'raspberrypi', 'color' => 'A22846'],
            ['label' => 'ESP32', 'slug' => 'espressif', 'color' => 'E7352C'],
            ['label' => 'IoT', 'slug' => 'homeassistant', 'color' => '18BCF2'],
            ['label' => 'Embedded C', 'slug' => 'gnu', 'color' => 'A42E2B'],
        ],
    ],
];
