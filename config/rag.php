<?php

return [
    'enabled' => (bool) env('RAG_ENABLED', true),
    'top_k' => (int) env('RAG_TOP_K', 8),
    'min_semantic_score' => (float) env('RAG_MIN_SEMANTIC_SCORE', 0.35),
    'max_chunks_per_document' => (int) env('RAG_MAX_CHUNKS_PER_DOCUMENT', 2),
    'chunk_size' => (int) env('RAG_CHUNK_SIZE', 1200),
    'chunk_overlap' => (int) env('RAG_CHUNK_OVERLAP', 150),
    'embedding_batch_size' => (int) env('RAG_EMBEDDING_BATCH_SIZE', 16),
    'history_turns' => (int) env('RAG_HISTORY_TURNS', 8),
    'source_cards' => (int) env('RAG_SOURCE_CARDS', 4),
    'profile' => [
        'title' => 'Jawahar Ganesh — Profile',
        'content' => <<<'TEXT'
Name: Jawahar Ganesh, also known as Jay and JayXCoder.
Role: Full-Stack Developer and Computer Engineer based in Malaysia and educated at UniMAP.
Focus: Laravel, React, Python, AI and machine learning, cybersecurity, embedded systems, and IoT.
LinkedIn: https://linkedin.com/in/jay71
Portfolio sections: Home, About, Skills, Projects, Portfolio, Experience, Achievements, Contact, and AI Chat.
TEXT,
    ],
];
