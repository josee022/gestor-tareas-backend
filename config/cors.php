<?php

return [
    'paths' => ['api/*', 'register', 'login', 'logout'], // Incluye todas las rutas necesarias
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
