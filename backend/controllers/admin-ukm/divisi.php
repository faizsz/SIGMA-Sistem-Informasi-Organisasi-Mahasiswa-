<?php
// Koneksi database
require_once __DIR__ . '/../../config/config.php';

// Set header untuk response JSON
header('Content-Type: application/json');

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Get detail divisi untuk edit
        if (isset($_GET['id_divisi'])) {
            $id_divisi = $_GET['id_divisi'];
            $query = "SELECT * FROM divisi_ukm WHERE id_divisi = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$id_divisi]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
            exit;
        }

        // Get all divisi berdasarkan UKM
        if (isset($_GET['id_ukm'])) {
            $id_ukm = $_GET['id_ukm'];
            $query = "SELECT * FROM divisi_ukm 
                     WHERE id_ukm = ? 
                     ORDER BY hierarki, nama_divisi";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$id_ukm]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result ?: []);
            exit;
        }
    } catch (Exception $e) {
        error_log("Error in divisi.php: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

// Handle POST request (Create/Update)
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id_divisi = isset($_POST['id_divisi']) ? $_POST['id_divisi'] : null;
        $id_ukm = $_POST['id_ukm'];
        $nama_divisi = $_POST['nama_divisi'];
        $tipe_divisi = $_POST['tipe_divisi'];
        // Set hierarki berdasarkan tipe
        $hierarki = ($tipe_divisi === 'inti') ? 1 : 2;
        $deskripsi = $_POST['deskripsi'];

        if ($id_divisi) {
            // Update existing record
            $query = "UPDATE divisi_ukm 
                     SET nama_divisi = ?, hierarki = ?, deskripsi = ?, tipe_divisi = ? 
                     WHERE id_divisi = ? AND id_ukm = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$nama_divisi, $hierarki, $deskripsi, $tipe_divisi, $id_divisi, $id_ukm]);
        } else {
            // Insert new record
            $query = "INSERT INTO divisi_ukm (id_ukm, nama_divisi, hierarki, deskripsi, tipe_divisi) 
                     VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$id_ukm, $nama_divisi, $hierarki, $deskripsi, $tipe_divisi]);
        }

        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        error_log("Error in divisi.php POST: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

// Handle DELETE request
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    try {
        if (isset($_GET['id_divisi'])) {
            $id_divisi = $_GET['id_divisi'];
            
            // Periksa apakah ada jabatan yang terkait
            $check_query = "SELECT COUNT(*) FROM jabatan_divisi WHERE id_divisi = ?";
            $check_stmt = $pdo->prepare($check_query);
            $check_stmt->execute([$id_divisi]);
            
            if ($check_stmt->fetchColumn() > 0) {
                throw new Exception('Tidak dapat menghapus divisi karena masih memiliki jabatan terkait');
            }
            
            // Hapus divisi
            $query = "DELETE FROM divisi_ukm WHERE id_divisi = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$id_divisi]);
            
            echo json_encode(['status' => 'success']);
        }
    } catch (Exception $e) {
        error_log("Error in divisi.php DELETE: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>