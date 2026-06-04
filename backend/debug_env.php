<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

echo json_encode([
    'getenv_DB_HOST' => getenv('DB_HOST'),
    'ENV_DB_HOST' => $_ENV['DB_HOST'] ?? 'NOT SET',
    'SERVER_DB_HOST' => $_SERVER['DB_HOST'] ?? 'NOT SET',
    'port' => getenv('PORT'),
]);
