<?php
// Load .env file jika ada (untuk Railway yang tulis env ke file saat startup)
$envFile = __DIR__ . '/../../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_contains($line, '=')) {
            [$key, $val] = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($val);
            putenv(trim($key) . '=' . trim($val));
        }
    }
}

// Helper: ambil env variable
function env($key, $default = '') {
    return getenv($key) ?: ($_ENV[$key] ?? ($_SERVER[$key] ?? $default));
}

$host     = env('DB_HOST', 'localhost');
$port     = env('DB_PORT', '3306');
$dbname   = env('DB_NAME', 'sigma');
$username = env('DB_USER', 'root');
$password = env('DB_PASS', '');

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    // SSL wajib untuk Aiven
    if ($host && str_contains($host, 'aivencloud.com')) {
        $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
        $options[PDO::MYSQL_ATTR_SSL_CA] = true;
    }

    $pdo = new PDO($dsn, $username, $password, $options);

} catch (PDOException $e) {
    die(json_encode([
        'status' => 'error',
        'message' => 'Koneksi ke database gagal: ' . $e->getMessage()
    ]));
}
?>
