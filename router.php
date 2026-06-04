<?php
// Router untuk PHP built-in server
// Tambah CORS headers ke semua request

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Serve file static (html, css, js, images) langsung
$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);
$file = __DIR__ . $path;

if ($path !== '/' && is_file($file)) {
    // Biar PHP built-in server handle file static sendiri
    return false;
}

// Default: serve index.html
if ($path === '/') {
    include __DIR__ . '/index.html';
    return;
}

// Kalau file tidak ada, return 404
http_response_code(404);
echo json_encode(['status' => 'error', 'message' => 'Not found']);
?>
