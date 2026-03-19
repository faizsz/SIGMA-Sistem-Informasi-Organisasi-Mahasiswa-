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

$nim = $_SESSION['username'];
$id_ukm = $_GET['id_ukm'] ?? null;
$tahap = $_GET['tahap'] ?? '1';

if (!$id_ukm) {
    echo json_encode([
        'status' => 'error',
        'message' => 'ID UKM tidak ditemukan'
    ]);
    exit;
}

try {
    $query = "SELECT p.*, u.nama_ukm 
              FROM pendaftaran_ukm p
              JOIN ukm u ON p.id_ukm = u.id_ukm
              JOIN periode_pendaftaran_ukm per ON p.id_periode_pendaftaran = per.id_periode_pendaftaran
              WHERE p.nim = :nim 
              AND p.id_ukm = :id_ukm
              AND per.status = 'aktif'
              ORDER BY p.tanggal_pendaftaran DESC 
              LIMIT 1";
              
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'nim' => $nim,
        'id_ukm' => $id_ukm
    ]);
    
    $pendaftaran = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($pendaftaran) {
        $status_field = "status_tahap" . $tahap;
        $catatan_field = "catatan_reject_tahap" . $tahap;
        
        echo json_encode([
            'status' => 'success',
            'data' => [
                'nama_ukm' => $pendaftaran['nama_ukm'],
                'tahap' => $tahap,
                'status' => $pendaftaran[$status_field],
                'catatan' => $pendaftaran[$catatan_field] ?? null
            ]
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Data pendaftaran tidak ditemukan'
        ]);
    }

} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>