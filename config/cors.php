<?php

$origins = array_values(array_filter(array_map('trim', explode(',', env(
    'CORS_ALLOWED_ORIGINS',
    'http://localhost:3000,http://localhost:3001,http://localhost:5173,http://127.0.0.1:3000,http://127.0.0.1:3001,http://127.0.0.1:5173,https://cidcomitra.com,https://www.cidcomitra.com'
)))));

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => $origins,
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    // Bearer-token SPA clients do not need credentialed CORS
    'supports_credentials' => false,
];
