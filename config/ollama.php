<?php

$host = rtrim((string) env('OLLAMA_HOST', '192.168.0.215'), '/');
$port = (int) env('OLLAMA_PORT', 11434);
$explicitUrl = env('OLLAMA_API_URL');

$apiUrl = $explicitUrl ?: "http://{$host}:{$port}";

return [
    'host' => $host,
    'port' => $port,
    'api_url' => rtrim($apiUrl, '/'),
    'model' => env('OLLAMA_MODEL', 'gemma4:e4b'),
    'timeout' => (int) env('OLLAMA_TIMEOUT', 300),
    'tailscale_enabled' => env('OLLAMA_TAILSCALE_ENABLED', false),
    'tailscale_ip' => env('OLLAMA_TAILSCALE_IP'),
];
