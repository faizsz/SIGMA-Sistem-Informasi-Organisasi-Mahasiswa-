<?php
session_start();
require_once '../../config/config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized'
    ]);
    exit;
}

$id_ukm = $_GET['id_ukm'] ?? null;

if (!$id_ukm) {
    echo json_encode([
        'status' => 'error',
        'message' => 'ID UKM tidak ditemukan'
    ]);
    exit;
}

try {
    $query = "SELECT id_divisi, nama_divisi, deskripsi 
              FROM divisi_ukm 
              WHERE id_ukm = :id_ukm";
              
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id_ukm' => $id_ukm]);
    $divisi = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'data' => $divisi
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>