<?php
// Koneksi database
require_once __DIR__ . '/../../config/config.php';

// Set header untuk response JSON
header('Content-Type: application/json');

// Fungsi untuk upload foto
function uploadFoto($file, $nim) {
    $target_dir = "../../../frontend/public/assets/";
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $new_filename = "foto-" . $nim . "." . $file_extension;
    $target_file = $target_dir . $new_filename;

    // Cek ukuran file (max 2MB)
    if ($file["size"] > 2000000) {
        return false;
    }

    // Cek tipe file
    if ($file_extension != "jpg" && $file_extension != "jpeg" && $file_extension != "png") {
        return false;
    }

    // Hapus file lama jika ada
    if (file_exists($target_file)) {
        unlink($target_file);
    }

    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $new_filename;
    }
    return false;
}

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Get Mahasiswa untuk dropdown
        if (isset($_GET['action']) && $_GET['action'] === 'get_mahasiswa') {
            $id_ukm = $_GET['id_ukm'];
            $query = "SELECT DISTINCT m.nim, m.nama_lengkap 
                     FROM mahasiswa m
                     JOIN keanggotaan_ukm k ON m.nim = k.nim 
                     WHERE k.id_ukm = ? 
                     AND k.status = 'anggota'
                     AND m.nim NOT IN (
                         SELECT nim 
                         FROM struktur_organisasi_ukm 
                         WHERE id_ukm = ?
                     )
                     ORDER BY m.nama_lengkap";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$id_ukm, $id_ukm]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            exit;
        }
        
        // Get Periode untuk dropdown
        if (isset($_GET['action']) && $_GET['action'] === 'get_periode') {
            $query = "SELECT id_periode, tahun_mulai, tahun_selesai 
                     FROM periode_kepengurusan 
                     ORDER BY tahun_mulai DESC";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            exit;
        }

        // Get detail struktur organisasi
        if (isset($_GET['id_struktur'])) {
            $id_struktur = $_GET['id_struktur'];
            $query = "SELECT s.*, m.nama_lengkap, d.id_divisi
                     FROM struktur_organisasi_ukm s
                     JOIN mahasiswa m ON s.nim = m.nim
                     JOIN divisi_ukm d ON s.id_divisi = d.id_divisi
                     WHERE s.id_struktur = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$id_struktur]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
            exit;
        }

    // Get all struktur organisasi berdasarkan UKM
    if (isset($_GET['id_ukm'])) {
        $id_ukm = $_GET['id_ukm'];
        $query = "SELECT s.*, m.nama_lengkap as nama, 
                d.nama_divisi, d.id_divisi, d.tipe_divisi,
                jd.nama_jabatan, jd.id_jabatan_divisi, jd.tipe_jabatan,
                d.hierarki as divisi_hierarki,
                jd.hierarki as jabatan_hierarki,
                CONCAT(p.tahun_mulai, ' - ', p.tahun_selesai) as periode
                FROM struktur_organisasi_ukm s
                JOIN mahasiswa m ON s.nim = m.nim
                JOIN divisi_ukm d ON s.id_divisi = d.id_divisi
                JOIN jabatan_divisi jd ON s.id_jabatan_divisi = jd.id_jabatan_divisi
                JOIN periode_kepengurusan p ON s.id_periode = p.id_periode
                WHERE s.id_ukm = ?
                ORDER BY d.hierarki, d.tipe_divisi DESC, jd.hierarki";

        $stmt = $pdo->prepare($query);
        $stmt->execute([$id_ukm]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result ?: []);
        exit;
    }
        } catch (Exception $e) {
            error_log("Error in struktur_organisasi.php GET: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            exit;
        }
    }

// Handle POST request (Create/Update)
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();
        
        $id_struktur = isset($_POST['id_struktur']) ? $_POST['id_struktur'] : null;
        $nim = $_POST['nim'];
        $id_ukm = $_POST['id_ukm'];
        $id_divisi = $_POST['id_divisi'];
        $id_jabatan_divisi = $_POST['id_jabatan_divisi'];
        $id_periode = $_POST['id_periode'];

        // Handle foto upload
        $foto_path = null;
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
            $foto_path = uploadFoto($_FILES['foto'], $nim);
            if (!$foto_path) {
                throw new Exception('Gagal upload foto');
            }
        }

        if ($id_struktur) {
            // Update existing record
            $query = "UPDATE struktur_organisasi_ukm 
                     SET nim = ?, id_divisi = ?, id_jabatan_divisi = ?, id_periode = ?";
            
            $params = [$nim, $id_divisi, $id_jabatan_divisi, $id_periode];
            
            if ($foto_path) {
                $query .= ", foto_path = ?";
                $params[] = $foto_path;
            }
            
            $query .= " WHERE id_struktur = ? AND id_ukm = ?";
            $params[] = $id_struktur;
            $params[] = $id_ukm;
            
        } else {
            // Insert new record
            $query = "INSERT INTO struktur_organisasi_ukm 
                     (nim, id_ukm, id_divisi, id_jabatan_divisi, id_periode, foto_path)
                     VALUES (?, ?, ?, ?, ?, ?)";
            $params = [$nim, $id_ukm, $id_divisi, $id_jabatan_divisi, $id_periode, $foto_path];
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);

        // Update status di keanggotaan_ukm
        $queryKeanggotaan = "UPDATE keanggotaan_ukm 
                            SET status = 'pengurus'
                            WHERE nim = ? AND id_ukm = ?";
        $stmtKeanggotaan = $pdo->prepare($queryKeanggotaan);
        $stmtKeanggotaan->execute([$nim, $id_ukm]);

        $pdo->commit();
        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Error in struktur_organisasi.php POST: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

// Handle DELETE request
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    try {
        $pdo->beginTransaction();
        
        if (isset($_GET['id_struktur'])) {
            $id_struktur = $_GET['id_struktur'];
            
            // Get nim dan id_ukm dari struktur yang akan dihapus
            $query = "SELECT nim, id_ukm, foto_path 
                     FROM struktur_organisasi_ukm 
                     WHERE id_struktur = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$id_struktur]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($data) {
                // Update status di keanggotaan_ukm kembali menjadi anggota
                $queryKeanggotaan = "UPDATE keanggotaan_ukm 
                                   SET status = 'anggota'
                                   WHERE nim = ? AND id_ukm = ?";
                $stmtKeanggotaan = $pdo->prepare($queryKeanggotaan);
                $stmtKeanggotaan->execute([$data['nim'], $data['id_ukm']]);
                
                // Delete record dari struktur_organisasi_ukm
                $queryDelete = "DELETE FROM struktur_organisasi_ukm 
                              WHERE id_struktur = ?";
                $stmtDelete = $pdo->prepare($queryDelete);
                $stmtDelete->execute([$id_struktur]);
                
                // Delete foto file if exists
                if ($data['foto_path']) {
                    $foto_path = "../../../frontend/public/assets/" . $data['foto_path'];
                    if (file_exists($foto_path)) {
                        unlink($foto_path);
                    }
                }
            }
            
            $pdo->commit();
            echo json_encode(['status' => 'success']);
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Error in struktur_organisasi.php DELETE: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>