<?php

return [
    'chat_model' => env('OLLAMA_MODEL', 'qwen3.5:2b'),
    'embedding_model' => env('OLLAMA_EMBEDDING_MODEL', 'qwen3-embedding:0.6b'),
    'think' => (bool) env('OLLAMA_THINK', true),
    'planner_max_tokens' => (int) env('OLLAMA_PLANNER_MAX_TOKENS', 256),
    'analysis_max_tokens' => (int) env('OLLAMA_ANALYSIS_MAX_TOKENS', 1536),
    'answer_max_tokens' => (int) env('OLLAMA_ANSWER_MAX_TOKENS', 768),
];
