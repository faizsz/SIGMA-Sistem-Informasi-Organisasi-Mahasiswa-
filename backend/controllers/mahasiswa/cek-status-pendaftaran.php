<?php
// Disable all error reporting for production
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');
require_once '../../config/config.php';

if (!isset($_GET['id_ukm'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'ID UKM tidak ditemukan'
    ]);
    exit;
}

$id_ukm = $_GET['id_ukm'];
session_start();
$nim = $_SESSION['username'] ?? $_GET['nim'] ?? null;

if (!$nim) {
    echo json_encode([
        'status' => 'error',
        'message' => 'NIM tidak ditemukan'
    ]);
    exit;
}

try {
    // Get active period first
    $periodQuery = "SELECT id_periode_pendaftaran 
                   FROM periode_pendaftaran_ukm 
                   WHERE id_ukm = :id_ukm 
                   AND status = 'aktif'
                   AND NOW() BETWEEN tanggal_buka AND tanggal_tutup";
    $periodStmt = $pdo->prepare($periodQuery);
    $periodStmt->execute(['id_ukm' => $id_ukm]);
    $activePeriod = $periodStmt->fetch(PDO::FETCH_ASSOC);

    if (!$activePeriod) {
        echo json_encode([
            'status' => 'success',
            'data' => [
                'status' => 'PERIODE_TUTUP',
                'message' => 'Tidak ada periode pendaftaran yang aktif'
            ]
        ]);
        exit;
    }

    // Check registration status
    $query = "SELECT p.*, per.batas_waktu_tahap2, per.batas_waktu_tahap3
              FROM pendaftaran_ukm p
              JOIN periode_pendaftaran_ukm per ON p.id_periode_pendaftaran = per.id_periode_pendaftaran
              WHERE p.nim = :nim 
              AND p.id_ukm = :id_ukm
              AND p.id_periode_pendaftaran = :periode_id
              ORDER BY p.tanggal_pendaftaran DESC, p.id_pendaftaran DESC
              LIMIT 1";
             
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'nim' => $nim,
        'id_ukm' => $id_ukm,
        'periode_id' => $activePeriod['id_periode_pendaftaran']
    ]);
    
    $pendaftaran = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $response = [
        'status' => 'success',
        'data' => [
            'status' => 'BELUM_DAFTAR',
            'is_tahap2_valid' => null,
            'is_tahap3_valid' => null,
            'catatan' => null
        ]
    ];

    if ($pendaftaran) {
        // Langsung gunakan status dari database
        $response['data']['status'] = strtoupper($pendaftaran['status']);
        
        // Tambahkan catatan sesuai status
        switch ($pendaftaran['status']) {
            case 'pending_tahap1':
                $response['data']['catatan'] = $pendaftaran['catatan_tahap1'] ?? null;
                break;
            
            case 'acc_tahap1':
                $response['data']['catatan'] = $pendaftaran['catatan_tahap1'] ?? null;
                if (!empty($pendaftaran['waktu_submit_tahap1'])) {
                    $batasTahap2 = strtotime($pendaftaran['waktu_submit_tahap1'] . " +" . $pendaftaran['batas_waktu_tahap2'] . " days");
                    $response['data']['is_tahap2_valid'] = time() < $batasTahap2;
                }
                break;
            
            case 'pending_tahap2':
                $response['data']['catatan'] = $pendaftaran['catatan_tahap2'] ?? null;
                break;
            
            case 'acc_tahap2':
                $response['data']['catatan'] = $pendaftaran['catatan_tahap2'] ?? null;
                if (!empty($pendaftaran['waktu_submit_tahap2'])) {
                    $batasTahap3 = strtotime($pendaftaran['waktu_submit_tahap2'] . " +" . $pendaftaran['batas_waktu_tahap3'] . " days");
                    $response['data']['is_tahap3_valid'] = time() < $batasTahap3;
                }
                break;
            
            case 'pending_tahap3':
                $response['data']['catatan'] = $pendaftaran['catatan_tahap3'] ?? null;
                break;
            
            case 'acc_tahap3':
                $response['data']['catatan'] = $pendaftaran['catatan_tahap3'] ?? null;
                break;
            
                case 'ditolak':
                    // Cek catatan dari tahap tertinggi ke terendah
                    if (!empty($pendaftaran['catatan_tahap3'])) {
                        $response['data']['catatan'] = $pendaftaran['catatan_tahap3'];
                    } elseif (!empty($pendaftaran['catatan_tahap2'])) {
                        $response['data']['catatan'] = $pendaftaran['catatan_tahap2'];
                    } elseif (!empty($pendaftaran['catatan_tahap1'])) {
                        $response['data']['catatan'] = $pendaftaran['catatan_tahap1'];
                    }
                    // Jika tidak ada catatan sama sekali
                    if (empty($response['data']['catatan'])) {
                        $response['data']['catatan'] = 'Tidak ada catatan dari admin.';
                    }
                    break;
        }
    }

    echo json_encode($response);

} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error occurred'
    ]);
} catch (Exception $e) {
    error_log("General Error: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'An error occurred'
    ]);
}