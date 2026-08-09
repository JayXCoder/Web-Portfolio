<?php

return [
    'client_id' => env('LINKEDIN_CLIENT_ID'),
    'client_secret' => env('LINKEDIN_CLIENT_SECRET'),
    'redirect_uri' => env('LINKEDIN_REDIRECT_URI'),
    'api_version' => env('LINKEDIN_API_VERSION', '202607'),
    'scopes' => array_values(array_filter(explode(' ', (string) env('LINKEDIN_SCOPES', 'openid profile r_member_social')))),
    'authorize_url' => 'https://www.linkedin.com/oauth/v2/authorization',
    'token_url' => 'https://www.linkedin.com/oauth/v2/accessToken',
    'userinfo_url' => 'https://api.linkedin.com/v2/userinfo',
    'api_url' => 'https://api.linkedin.com/rest',
    'timeout' => (int) env('LINKEDIN_TIMEOUT', 30),
    'import_max_kb' => (int) env('LINKEDIN_IMPORT_MAX_KB', 20480),
];
