<?php
session_start();
require_once '../../config/config.php';
header('Content-Type: application/json');

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'User belum login'
    ]);
    exit;
}

try {
    $username = $_SESSION['username'];
    
    // Query untuk mendapatkan UKM yang TIDAK diikuti oleh mahasiswa pada periode aktif
    $query = "
        SELECT DISTINCT u.id_ukm, u.nama_ukm, u.deskripsi, u.logo_path,
               pk.tahun_mulai, pk.tahun_selesai
        FROM ukm u
        CROSS JOIN periode_kepengurusan pk
        WHERE pk.status = 'aktif'
        AND NOT EXISTS (
            SELECT 1
            FROM keanggotaan_ukm ku
            WHERE ku.id_ukm = u.id_ukm
            AND ku.nim = :username
            AND ku.id_periode = pk.id_periode
        )
        ORDER BY u.nama_ukm ASC
    ";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    
    $ukm_rekomendasi = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($ukm_rekomendasi) {
        echo json_encode([
            'status' => 'success',
            'data' => $ukm_rekomendasi
        ]);
    } else {
        echo json_encode([
            'status' => 'success',
            'data' => [],
            'message' => 'Tidak ada UKM rekomendasi'
        ]);
    }

} catch (PDOException $e) {
    error_log($e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'Terjadi kesalahan pada server'
    ]);
}
?>