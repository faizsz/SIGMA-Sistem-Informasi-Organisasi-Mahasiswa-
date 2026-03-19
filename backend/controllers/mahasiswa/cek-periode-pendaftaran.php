<?php
require_once '../../config/config.php';
header('Content-Type: application/json');

$id_ukm = $_GET['id_ukm'] ?? null;

if (!$id_ukm) {
    echo json_encode([
        'status' => 'error',
        'message' => 'ID UKM tidak ditemukan'
    ]);
    exit;
}

try {
    // Debug: Tampilkan waktu server
    $currentTime = date('Y-m-d H:i:s');
    
    // Cek periode pendaftaran yang aktif
    $query = "SELECT * FROM periode_pendaftaran_ukm 
              WHERE id_ukm = :id_ukm 
              AND status = 'aktif'
              AND NOW() BETWEEN tanggal_buka AND tanggal_tutup";
              
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id_ukm' => $id_ukm]);
    $periode = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($periode) {
        echo json_encode([
            'status' => 'success',
            'is_open' => true,
            'data' => [
                'id_periode' => $periode['id_periode_pendaftaran'],
                'tanggal_tutup' => $periode['tanggal_tutup'],
                'batas_waktu_tahap1' => $periode['batas_waktu_tahap1'],
                'batas_waktu_tahap2' => $periode['batas_waktu_tahap2'],
                'batas_waktu_tahap3' => $periode['batas_waktu_tahap3']
            ],
            'debug' => [
                'current_time' => $currentTime,
                'id_ukm' => $id_ukm,
                'raw_query' => $query
            ]
        ]);
    } else {
        echo json_encode([
            'status' => 'success',
            'is_open' => false,
            'message' => 'Pendaftaran belum dibuka atau sudah ditutup',
            'debug' => [
                'current_time' => $currentTime,
                'id_ukm' => $id_ukm,
                'raw_query' => $query
            ]
        ]);
    }

} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}