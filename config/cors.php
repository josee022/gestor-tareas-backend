<?php

return [
    'paths' => ['api/*', 'register', 'login', 'sanctum/csrf-cookie'], // Incluye todas las rutas necesarias
    'allowed_methods' => ['*'],
    'allowed_origins' => ['https://gestor-tareas-beige.vercel.app', 'http://localhost:5173', 'http://localhost:5174'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true, // Esto es importante porque usas autenticación con Sanctum
];

