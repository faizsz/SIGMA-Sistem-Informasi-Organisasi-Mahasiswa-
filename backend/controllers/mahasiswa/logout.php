<?php
session_start();

// Menambahkan header CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Hapus semua variabel sesi
$_SESSION = array();

// Hancurkan sesi
session_destroy();

// Kirim respons JSON
header('Content-Type: application/json');
echo json_encode(['status' => 'success', 'message' => 'Logged out successfully']);
?>
