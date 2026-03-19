<?php
session_start();
header('Content-Type: application/json');

// Cek apakah pengguna sudah login berdasarkan session
if (isset($_SESSION['id_login']) && isset($_SESSION['username'])) {
    // Jika terautentikasi, kirim respons dengan data login
    echo json_encode([
        'authenticated' => true,
        'id_login' => $_SESSION['id_login'],
        'username' => $_SESSION['username'],
        'role' => $_SESSION['role'],
        'id_ukm' => $_SESSION['id_ukm'] ?? null // Hanya jika ada
    ]);
} else {
    // Jika tidak terautentikasi, kirim respons gagal
    echo json_encode(['authenticated' => false]);
}
?>