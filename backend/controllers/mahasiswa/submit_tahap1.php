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

try {
    // Validate required fields
    if (!isset($_POST['id_ukm'])) {
        throw new Exception('Missing required fields');
    }

    $nim = $_SESSION['username'];
    $id_ukm = $_POST['id_ukm'];
    $motivasi = $_POST['motivasi'] ?? '';

    // 1. Get active periode_pendaftaran
    $queryPeriode = "SELECT id_periode_pendaftaran FROM periode_pendaftaran_ukm 
                     WHERE id_ukm = :id_ukm AND status = 'aktif'
                     AND NOW() BETWEEN tanggal_buka AND tanggal_tutup";
    $stmtPeriode = $pdo->prepare($queryPeriode);
    $stmtPeriode->execute(['id_ukm' => $id_ukm]);
    $periode = $stmtPeriode->fetch(PDO::FETCH_ASSOC);

    if (!$periode) {
        throw new Exception('Periode pendaftaran tidak ditemukan atau sudah ditutup');
    }

    // 2. Check if user already registered in this period
    $queryCheck = "SELECT id_pendaftaran FROM pendaftaran_ukm 
                  WHERE nim = :nim 
                  AND id_ukm = :id_ukm 
                  AND id_periode_pendaftaran = :id_periode";
    $stmtCheck = $pdo->prepare($queryCheck);
    $stmtCheck->execute([
        'nim' => $nim,
        'id_ukm' => $id_ukm,
        'id_periode' => $periode['id_periode_pendaftaran']
    ]);

    if ($stmtCheck->fetch()) {
        throw new Exception('Anda sudah terdaftar pada UKM ini di periode pendaftaran yang aktif');
    }

    // 3. Begin transaction
    $pdo->beginTransaction();

    // Insert into pendaftaran_ukm with new status field
    $query = "INSERT INTO pendaftaran_ukm (
                nim, 
                id_ukm, 
                tahap_seleksi,
                status,
                motivasi, 
                id_periode_pendaftaran, 
                waktu_submit_tahap1
              ) VALUES (
                :nim, 
                :id_ukm, 
                'tahap1',
                'pending_tahap1',
                :motivasi, 
                :id_periode, 
                NOW()
              )";

    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'nim' => $nim,
        'id_ukm' => $id_ukm,
        'motivasi' => $motivasi,
        'id_periode' => $periode['id_periode_pendaftaran']
    ]);

    $id_pendaftaran = $pdo->lastInsertId();

    // Insert into history_pendaftaran with new status field
    $queryHistory = "INSERT INTO history_pendaftaran (
                      id_pendaftaran, 
                      nim, 
                      id_ukm,
                      status,
                      tanggal_pendaftaran, 
                      tanggal_update_status
                    ) VALUES (
                      :id_pendaftaran, 
                      :nim, 
                      :id_ukm,
                      'pending_tahap1',
                      NOW(), 
                      NOW()
                    )";

    $stmtHistory = $pdo->prepare($queryHistory);
    $stmtHistory->execute([
        'id_pendaftaran' => $id_pendaftaran,
        'nim' => $nim,
        'id_ukm' => $id_ukm
    ]);

    $pdo->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Pendaftaran tahap 1 berhasil',
        'data' => [
            'id_pendaftaran' => $id_pendaftaran
        ]
    ]);

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>