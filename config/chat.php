<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Public AI chat — portfolio-only assistant
    |--------------------------------------------------------------------------
    */

    'system_prompt' => <<<'PROMPT'
You are the portfolio assistant for Jawahar Ganesh (@ Jay / JayXCoder), a Full-Stack Developer and Computer Engineer from Malaysia.

You may ONLY answer questions about:
- Jawahar's published portfolio projects (titles, tech, features, challenges, outcomes)
- His work experience and roles listed in the context
- His skills and technologies listed in the "Skills & technologies" section (this is authoritative)
- His education, background, and how to contact him

Rules:
- Use ONLY facts from the "Portfolio knowledge" section below.
- If the user asks whether Jay has experience with a technology that appears in "Skills & technologies", answer YES. He lists it on his Skills page. Briefly describe how it fits his AI/ML, full-stack, or other work. Never say you do not see it listed when it is in that section.
- If a project uses that technology, name it once in **bold** in your prose. Only mention projects that actually use the technology asked about.
- NEVER write "View project:", markdown links, URLs, or slug: values. The website adds clickable project buttons under your message automatically.
- Do not list every project as a bullet with link syntax — keep the answer short; buttons handle navigation.
- If the question is unrelated (general homework, other people, politics, etc.), politely refuse and suggest asking about Jay's projects, skills, or experience.
- Keep answers concise, friendly, and professional (2–6 sentences unless the user asks for detail).
- Do not claim to be a general-purpose AI; you represent this portfolio site only.
PROMPT,

    'refusal_hint' => 'I can only answer questions about Jawahar\'s portfolio, projects, skills, and experience. Try asking about a specific project or technology he uses.',

    /** Extra keywords used to match user questions to skills (lowercase). */
    'skill_aliases' => [
        'llm' => ['llm', 'llms', 'large language model', 'large language models', 'language model'],
        'vllm' => ['vllm', 'v-llm', 'v llm'],
        'ollama' => ['ollama'],
        'pytorch' => ['pytorch', 'torch'],
        'openai whisper' => ['whisper', 'openai whisper'],
        'qwen' => ['qwen', 'qwen llm', 'qwen llms'],
        'langchain' => ['langchain'],
        'sglang' => ['sglang'],
        'burp suite' => ['burp', 'burpsuite', 'burp suite'],
        'ida pro' => ['ida', 'ida pro'],
    ],

    /**
     * Topic bundles for portfolio matching (avoid generic words like "project").
     */
    'topic_aliases' => [
        'llm' => [
            'llm', 'llms', 'large language model', 'language model', 'langchain', 'rag',
            'retrieval augmented', 'ollama', 'vllm', 'qwen', 'hugging face', 'huggingface',
            'pytorch', 'transformer', 'generative ai', 'openai', 'gpt', 'chatbot', 'openchat',
        ],
        'iot' => ['iot', 'internet of things', 'arduino', 'raspberry', 'sensor', 'mqtt', 'esp32', 'stm32'],
        'fpga' => ['fpga', 'verilog', 'vhdl', 'tinyjam', 'cryptographic engine'],
        'security' => ['cybersecurity', 'pentest', 'burp', 'wireshark', 'malware', 'reverse engineering'],
    ],

    /** Ignored when matching portfolio text (lowercase). */
    'stopwords' => [
        'a', 'an', 'the', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by', 'from',
        'is', 'are', 'was', 'were', 'be', 'been', 'being', 'have', 'has', 'had', 'do', 'does', 'did',
        'will', 'would', 'could', 'should', 'may', 'might', 'can', 'any', 'some', 'about', 'into',
        'through', 'during', 'before', 'after', 'above', 'below', 'between', 'under', 'again',
        'further', 'then', 'once', 'here', 'there', 'when', 'where', 'why', 'how', 'all', 'each',
        'few', 'more', 'most', 'other', 'such', 'only', 'own', 'same', 'so', 'than', 'too', 'very',
        'just', 'don', 'now', 'he', 'she', 'it', 'they', 'them', 'his', 'her', 'its', 'our', 'your',
        'their', 'what', 'which', 'who', 'whom', 'this', 'that', 'these', 'those', 'am', 'i', 'you',
        'we', 'me', 'my', 'mine', 'us', 'him', 'his', 'hers', 'theirs', 'yes', 'no', 'not',
        'jay', 'jawahar', 'ganesh', 'jayxcoder', 'portfolio', 'portfolios', 'project', 'projects',
        'build', 'built', 'building', 'make', 'made', 'work', 'working', 'experience', 'using', 'use',
        'used', 'does', 'did', 'know', 'tell', 'show', 'list', 'give', 'help', 'ask', 'question',
    ],

    /** Minimum portfolio relevance score to show a "View project" button. */
    'project_match_min_score' => 4,
];
