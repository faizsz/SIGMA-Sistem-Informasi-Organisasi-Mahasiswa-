<?php
// Koneksi database
require_once __DIR__ . '/../../config/config.php';

// Set header untuk response JSON
header('Content-Type: application/json');

// Function untuk response JSON
function jsonResponse($status, $message = '', $data = null) {
    $response = [
        'status' => $status,
        'message' => $message,
        'data' => $data
    ];
    echo json_encode($response);
    exit;
}

// Start session
if (!isset($_SESSION)) {
    session_start();
}

// Cek auth
if (!isset($_SESSION['id_ukm'])) {
    jsonResponse('error', 'Unauthorized access');
}

$id_ukm = $_SESSION['id_ukm'];

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Get periode untuk dropdown
        if (isset($_GET['action']) && $_GET['action'] === 'get_periode') {
            $query = "SELECT id_periode, tahun_mulai, tahun_selesai, status 
                     FROM periode_kepengurusan 
                     ORDER BY tahun_mulai DESC";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            jsonResponse('success', 'Data periode berhasil diambil', $data);
        }

        // Get mahasiswa untuk dropdown (yang belum jadi anggota dan belum gabung 3 UKM)
        if (isset($_GET['action']) && $_GET['action'] === 'get_mahasiswa') {
            $query = "SELECT DISTINCT m.nim, m.nama_lengkap 
                    FROM mahasiswa m
                    INNER JOIN pendaftaran_ukm p ON m.nim = p.nim
                    LEFT JOIN keanggotaan_ukm k ON m.nim = k.nim AND k.id_ukm = ?
                    WHERE p.id_ukm = ? 
                    AND p.status = 'acc_tahap3'
                    AND k.id_keanggotaan IS NULL
                    ORDER BY m.nama_lengkap";
                    
            $stmt = $pdo->prepare($query);
            $stmt->execute([$id_ukm, $id_ukm]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            jsonResponse('success', 'Data mahasiswa berhasil diambil', $data);
        }

        // Get detail anggota untuk edit
        if (isset($_GET['id_keanggotaan'])) {
            $id_keanggotaan = $_GET['id_keanggotaan'];
            $query = "SELECT k.*, m.nama_lengkap 
                     FROM keanggotaan_ukm k
                     JOIN mahasiswa m ON k.nim = m.nim
                     WHERE k.id_keanggotaan = ? AND k.id_ukm = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$id_keanggotaan, $id_ukm]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                jsonResponse('error', 'Data tidak ditemukan');
            }

            jsonResponse('success', 'Data anggota berhasil diambil', $data);
        }

        // Get all anggota with filter
        $where = ['k.id_ukm = ?'];
        $params = [$id_ukm];

        if (isset($_GET['status']) && !empty($_GET['status'])) {
            $where[] = "k.status = ?";
            $params[] = $_GET['status'];
        }

        if (isset($_GET['periode']) && !empty($_GET['periode'])) {
            $where[] = "k.id_periode = ?";
            $params[] = $_GET['periode'];
        }

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            $where[] = "(m.nim LIKE ? OR m.nama_lengkap LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        $whereClause = implode(' AND ', $where);

        $query = "SELECT k.*, m.nama_lengkap, p.tahun_mulai, p.tahun_selesai,
                 ps.nama_program_studi, 
                 CONCAT(p.tahun_mulai, ' - ', p.tahun_selesai) as periode
                 FROM keanggotaan_ukm k
                 JOIN mahasiswa m ON k.nim = m.nim
                 JOIN periode_kepengurusan p ON k.id_periode = p.id_periode
                 JOIN program_studi ps ON m.id_program_studi = ps.id_program_studi
                 WHERE $whereClause
                 ORDER BY p.tahun_mulai DESC, m.nama_lengkap ASC";

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        jsonResponse('success', 'Data anggota berhasil diambil', $data);

    } catch (Exception $e) {
        jsonResponse('error', $e->getMessage());
    }
}

// Handle POST request (Create/Update)
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id_keanggotaan = isset($_POST['id_keanggotaan']) ? $_POST['id_keanggotaan'] : null;
        
        if ($id_keanggotaan) {
            $status = $_POST['status'];
            $id_periode = $_POST['id_periode'];
            
            // Update
            $query = "UPDATE keanggotaan_ukm 
                     SET status = ?, id_periode = ?
                     WHERE id_keanggotaan = ? AND id_ukm = ?";
            $params = [$status, $id_periode, $id_keanggotaan, $id_ukm];
            $message = 'Data anggota berhasil diupdate';
        } else {
            $nim = $_POST['nim'];
            $status = $_POST['status'];
            $id_periode = $_POST['id_periode'];
            
            // Insert
            $query = "INSERT INTO keanggotaan_ukm 
                     (nim, id_ukm, status, id_periode)
                     VALUES (?, ?, ?, ?)";
            $params = [$nim, $id_ukm, $status, $id_periode];
            $message = 'Anggota baru berhasil ditambahkan';
        }
        
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);

        jsonResponse('success', $message);

    } catch (Exception $e) {
        jsonResponse('error', $e->getMessage());
    }
}

// Handle DELETE request
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    try {
        $id_keanggotaan = $_GET['id_keanggotaan'];

        // Start transaction
        $pdo->beginTransaction();

        // Check if member exists and get their status
        $query = "SELECT k.status, k.nim, k.id_ukm 
                 FROM keanggotaan_ukm k
                 WHERE k.id_keanggotaan = ? AND k.id_ukm = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id_keanggotaan, $id_ukm]);
        $member = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$member) {
            $pdo->rollBack();
            jsonResponse('error', 'Data tidak ditemukan');
        }

        // If member is "pengurus", delete from struktur_organisasi first
        if ($member['status'] === 'pengurus') {
            $query = "DELETE FROM struktur_organisasi_ukm 
                     WHERE nim = ? AND id_ukm = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$member['nim'], $member['id_ukm']]);
        }

        // Delete from keanggotaan_ukm
        $query = "DELETE FROM keanggotaan_ukm 
                 WHERE id_keanggotaan = ? AND id_ukm = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id_keanggotaan, $id_ukm]);

        // Commit transaction
        $pdo->commit();

        jsonResponse('success', 'Data anggota berhasil dihapus');

    } catch (Exception $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        jsonResponse('error', $e->getMessage());
    }
}
?>  