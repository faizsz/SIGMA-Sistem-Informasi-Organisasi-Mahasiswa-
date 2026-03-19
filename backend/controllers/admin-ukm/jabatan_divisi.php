<?php
// Koneksi database
require_once __DIR__ . '/../../config/config.php';

// Set header untuk response JSON
header('Content-Type: application/json');

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Get detail jabatan untuk edit
        if (isset($_GET['id_jabatan_divisi'])) {
            $id_jabatan_divisi = $_GET['id_jabatan_divisi'];
            $query = "SELECT * FROM jabatan_divisi WHERE id_jabatan_divisi = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$id_jabatan_divisi]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
            exit;
        }

        // Get jabatan berdasarkan divisi
        if (isset($_GET['id_divisi'])) {
            $id_divisi = $_GET['id_divisi'];
            error_log("Request jabatan untuk divisi: " . $id_divisi);
            
            $query = "SELECT * FROM jabatan_divisi 
                     WHERE id_divisi = ? 
                     ORDER BY hierarki, nama_jabatan";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$id_divisi]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("Hasil query jabatan: " . print_r($result, true));
            echo json_encode($result ?: []);
            exit;
        }

        // Get semua jabatan berdasarkan UKM (untuk listing)
        if (isset($_GET['id_ukm'])) {
            $id_ukm = $_GET['id_ukm'];
            $query = "SELECT jd.*, d.nama_divisi 
                     FROM jabatan_divisi jd
                     JOIN divisi_ukm d ON jd.id_divisi = d.id_divisi
                     WHERE d.id_ukm = ?
                     ORDER BY d.hierarki, jd.hierarki";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$id_ukm]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            exit;
        }
    } catch (Exception $e) {
        error_log("Error in jabatan_divisi.php GET: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

// Handle POST request (Create/Update)
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id_jabatan_divisi = isset($_POST['id_jabatan_divisi']) ? $_POST['id_jabatan_divisi'] : null;
        $id_divisi = $_POST['id_divisi'];
        $nama_jabatan = $_POST['nama_jabatan'];
        $hierarki = $_POST['hierarki'];

        if ($id_jabatan_divisi) {
            // Update existing record
            $query = "UPDATE jabatan_divisi 
                     SET nama_jabatan = ?, hierarki = ?
                     WHERE id_jabatan_divisi = ? AND id_divisi = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$nama_jabatan, $hierarki, $id_jabatan_divisi, $id_divisi]);
        } else {
            // Check if hierarki already exists in this divisi
            $check_query = "SELECT COUNT(*) FROM jabatan_divisi 
                          WHERE id_divisi = ? AND hierarki = ?";
            $check_stmt = $pdo->prepare($check_query);
            $check_stmt->execute([$id_divisi, $hierarki]);
            if ($check_stmt->fetchColumn() > 0) {
                throw new Exception('Hierarki ini sudah ada dalam divisi tersebut');
            }

            // Insert new record
            $query = "INSERT INTO jabatan_divisi (id_divisi, nama_jabatan, hierarki) 
                     VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$id_divisi, $nama_jabatan, $hierarki]);
        }

        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        error_log("Error in jabatan_divisi.php POST: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

// Handle DELETE request
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    try {
        if (isset($_GET['id_jabatan_divisi'])) {
            $id_jabatan_divisi = $_GET['id_jabatan_divisi'];
            
            // Periksa apakah jabatan sedang digunakan di struktur organisasi
            $check_query = "SELECT COUNT(*) FROM struktur_organisasi_ukm 
                          WHERE id_jabatan_divisi = ?";
            $check_stmt = $pdo->prepare($check_query);
            $check_stmt->execute([$id_jabatan_divisi]);
            
            if ($check_stmt->fetchColumn() > 0) {
                throw new Exception('Tidak dapat menghapus jabatan karena sedang digunakan oleh pengurus');
            }
            
            // Hapus jabatan
            $query = "DELETE FROM jabatan_divisi WHERE id_jabatan_divisi = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$id_jabatan_divisi]);
            
            echo json_encode(['status' => 'success']);
        }
    } catch (Exception $e) {
        error_log("Error in jabatan_divisi.php DELETE: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>