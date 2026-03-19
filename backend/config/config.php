<?php
$host     = 'sigma-db-faizakmall-d.j.aivencloud.com';
$port     = '22892';
$dbname   = 'defaultdb';
$username = 'avnadmin';
$password = 'AVNS_m62ILEHcEWIeqFEcHyZ'; // ganti dengan password asli dari Aiven

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [PDO::MYSQL_ATTR_SSL_CA => true]
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi ke database gagal: " . $e->getMessage());
}
?>