<?php
return [
    'paths' => [
        'api/*', // Your regular API routes
        'oauth/*' // Passport's routes 
    ],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
