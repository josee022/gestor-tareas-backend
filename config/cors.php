<?php

/*
return [
    'paths' => ['api/*', 'register', 'login', 'logout', 'tasks'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
*/

return [
    'paths' => ['api/*', 'register', 'login', 'logout', 'tasks'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['https://gestor-tareas-beige.vercel.app'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
