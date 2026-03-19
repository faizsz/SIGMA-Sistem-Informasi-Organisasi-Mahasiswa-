<?php
require_once '../../config/config.php';

// Set header JSON
header('Content-Type: application/json');
// Tambahkan header CORS jika diperlukan
header('Access-Control-Allow-Origin: *');
// Get action dari parameter URL
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'get_stats':
        getStats();
        break;
    case 'get_ukm_members':
        getUkmMembers();
        break;
    case 'get_yearly_members':
        getYearlyMembers();
        break;
    case 'get_yearly_events':
        getYearlyEvents();
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Action tidak ditemukan']);
        break;
}

function getStats() {
    global $pdo;

    try {
        // Debug: Log query execution
        error_log("Executing getStats");

        $query_mahasiswa = "SELECT COUNT(*) as total FROM mahasiswa";
        $stmt = $pdo->query($query_mahasiswa);
        $total_mahasiswa = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        $query_ukm = "SELECT COUNT(*) as total FROM ukm";
        $stmt = $pdo->query($query_ukm);
        $total_ukm = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        $query_users = "SELECT role, COUNT(*) as total FROM user_login GROUP BY role";
        $stmt = $pdo->query($query_users);
        $users_by_role = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'status' => 'success',
            'data' => [
                'total_mahasiswa' => $total_mahasiswa,
                'total_ukm' => $total_ukm,
                'users_by_role' => $users_by_role
            ]
        ]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function getYearlyMembers() {
    global $pdo;
    try {
        // Menggunakan JOIN dengan periode_kepengurusan untuk mendapatkan data keanggotaan
        $query = "SELECT 
                    p.tahun_mulai as tahun,
                    COUNT(DISTINCT k.nim) as jumlah_anggota
                FROM periode_kepengurusan p
                LEFT JOIN keanggotaan_ukm k ON p.id_periode = k.id_periode
                WHERE p.tahun_mulai IS NOT NULL
                GROUP BY p.tahun_mulai
                ORDER BY p.tahun_mulai ASC";

        $stmt = $pdo->query($query);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Transform data to show the year properly
        $data = array_map(function($item) {
            return [
                'tahun' => date('Y', strtotime($item['tahun'])),
                'jumlah_anggota' => (int)$item['jumlah_anggota']
            ];
        }, $result);

        // Log untuk debugging
        error_log('Yearly Members Query: ' . $query);
        error_log('Yearly Members Data: ' . print_r($data, true));

        echo json_encode([
            'status' => 'success',
            'data' => $data
        ]);
    } catch (PDOException $e) {
        error_log('Error in getYearlyMembers: ' . $e->getMessage());
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
}

function getYearlyEvents() {
    global $pdo;
    try {
        $query = "SELECT 
                    YEAR(tanggal_kegiatan) as tahun,
                    COUNT(*) as jumlah_event
                 FROM timeline_ukm
                 GROUP BY YEAR(tanggal_kegiatan)
                 ORDER BY tahun";
        
        $stmt = $pdo->query($query);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'status' => 'success',
            'data' => $data
        ]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function getUkmMembers() {
    global $pdo;

    try {
        $query = "SELECT u.nama_ukm, COUNT(k.nim) as total_anggota 
                  FROM ukm u 
                  LEFT JOIN keanggotaan_ukm k ON u.id_ukm = k.id_ukm 
                  GROUP BY u.id_ukm, u.nama_ukm";
        $stmt = $pdo->query($query);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'status' => 'success',
            'data' => $data
        ]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>
