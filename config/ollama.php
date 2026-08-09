<?php

$host = rtrim((string) env('OLLAMA_HOST', '192.168.100.100'), '/');
$port = (int) env('OLLAMA_PORT', 11434);
$explicitUrl = env('OLLAMA_API_URL');

$apiUrl = $explicitUrl ?: "http://{$host}:{$port}";

return [
    'host' => $host,
    'port' => $port,
    'api_url' => rtrim($apiUrl, '/'),
    'model' => env('OLLAMA_MODEL', 'qwen3.5:2b'),
    'embedding_model' => env('OLLAMA_EMBEDDING_MODEL', 'qwen3-embedding:0.6b'),
    'think' => (bool) env('OLLAMA_THINK', true),
    'timeout' => (int) env('OLLAMA_TIMEOUT', 300),
    'tailscale_enabled' => env('OLLAMA_TAILSCALE_ENABLED', false),
    'tailscale_ip' => env('OLLAMA_TAILSCALE_IP'),
];
