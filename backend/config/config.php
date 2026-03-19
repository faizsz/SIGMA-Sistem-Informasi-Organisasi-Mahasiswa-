<?php
// Koneksi ke database menggunakan PDO
$host = 'localhost';
$dbname = 'sigma';
$username = 'root';
$password = '';

try {
    // Membuat koneksi PDO ke database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Mengatur atribut PDO untuk menampilkan error jika terjadi kesalahan
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Menampilkan pesan error jika koneksi gagal
    die("Koneksi ke database gagal: " . $e->getMessage());
}
?>
