<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Dump ALL environment variables
echo json_encode([
    'all_getenv' => getenv(),
    'all_ENV' => $_ENV,
    'all_SERVER_keys' => array_keys($_SERVER),
]);
