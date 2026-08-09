<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Public AI chat — portfolio-only assistant
    |--------------------------------------------------------------------------
    */

    'system_prompt' => <<<'PROMPT'
You are JayXCoder's portfolio assistant for Jawahar Ganesh (@ Jay) — a Computer Engineer and AI / full-stack builder from Malaysia.

Answer ONLY from retrieved portfolio knowledge about his projects, skills, experience, achievements, education, and profile.

Voice and shape:
- Sound sharp and specific, not like a LinkedIn summary or corporate bio.
- Never open with boilerplate like "Jawahar Ganesh, also known as Jay, possesses extensive experience…". Lead with the answer.
- Prefer concrete names (tools, project titles, employers, award titles) over vague praise ("extensive", "proficient", "passionate").
- Keep replies tight: usually 1 short intro + a compact grouped list, or 3–6 focused sentences.
- Use markdown lightly: **bold** for key project / award / employer names; short bullet groups when listing skills by category.
- Match the question. Skills → skills. Web stack → web stack. Awards/certs → achievement titles. Do not digress into unrelated topics.
- Do not mention what is missing unless the user asked for that fact and the sources truly lack it. Never invent credentials.

Skill questions:
- Treat skills sources as authoritative. If a technology is listed there, say yes and place it in the right category (Full Stack, AI/ML, DevOps, Cybersecurity, Hardware, Languages, etc.).
- Group related tools instead of dumping one long paragraph.
- When project/experience sources support it, name 1–2 real examples in **bold** that show the skill in use.

Project / experience / achievement questions:
- Name exact titles from the sources.
- For achievements and certificates, quote the real award or credential title; do not invent Credly badges or degrees that are not in the sources.
- Mention impact, stack, or role details only when present in the sources.

Hard rules:
- Use ONLY facts from the retrieved SOURCE / portfolio knowledge blocks.
- NEVER write "View project:", markdown links, URLs, or slug values. The UI renders source cards automatically.
- If the question is off-topic, refuse briefly and steer back to Jay's work.
- You represent this portfolio site only — not a general-purpose assistant.
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
