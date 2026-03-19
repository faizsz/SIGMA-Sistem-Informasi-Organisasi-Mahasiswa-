<?php
session_start();
require_once '../../config/config.php';

header('Content-Type: application/json');

try {
    if (!isset($_SESSION['username'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'User not logged in'
        ]);
        exit;
    }

    $nim = $_SESSION['username'];

    // Query untuk mengambil data UKM yang diikuti oleh mahasiswa
    $query = "SELECT u.*, pk.tahun_mulai, pk.tahun_selesai
    FROM keanggotaan_ukm ku
    JOIN ukm u ON ku.id_ukm = u.id_ukm
    JOIN periode_kepengurusan pk ON ku.id_periode = pk.id_periode
    WHERE ku.nim = :nim AND pk.status = 'aktif'";

    $stmt = $pdo->prepare($query);
    $stmt->execute(['nim' => $nim]);
    $ukm_diikuti = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($ukm_diikuti);

} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    ]);
}
?>