<?php
// Salin file ini menjadi config.php dan sesuaikan dengan konfigurasi database kamu
// cp config.example.php config.php

$host     = 'localhost';
$dbname   = 'sigma';
$username = 'root';      // username database kamu
$password = '';          // password database kamu

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi ke database gagal: " . $e->getMessage());
}
?>