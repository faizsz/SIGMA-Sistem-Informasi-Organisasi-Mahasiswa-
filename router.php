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

// Parse request URI
$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);
$file = __DIR__ . $path;

// Serve static files langsung
if ($path !== '/' && is_file($file)) {
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    
    // Untuk file PHP, set working directory ke folder file tersebut
    if ($ext === 'php') {
        chdir(dirname($file));
        include $file;
        return;
    }
    
    // File static lainnya, biar built-in server handle
    return false;
}

// Default: serve index.html
if ($path === '/' || !is_file($file)) {
    if (is_file(__DIR__ . '/index.html')) {
        include __DIR__ . '/index.html';
        return;
    }
}

http_response_code(404);
echo json_encode(['status' => 'error', 'message' => 'Not found']);
?>
