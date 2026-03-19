<?php
// Koneksi database
require_once __DIR__ . '/../../config/config.php';

// Set header untuk response JSON
header('Content-Type: application/json');

// Error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fungsi untuk upload image
function uploadImage($file) {
    $target_dir = "../../../frontend/public/assets/";
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $new_filename = "event-" . uniqid() . "." . $file_extension;
    $target_file = $target_dir . $new_filename;

    // Cek ukuran file (max 2MB)
    if ($file["size"] > 2000000) {
        return false;
    }

    // Cek tipe file
    if ($file_extension != "jpg" && $file_extension != "jpeg" && $file_extension != "png") {
        return false;
    }

    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $new_filename;
    }
    return false;
}

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Log untuk debugging
        error_log("GET Request received");
        error_log("GET parameters: " . print_r($_GET, true));

        if (isset($_GET['limit'])) {
            try {
                $limit = (int)$_GET['limit'];
                $isLatest = isset($_GET['latest']) && $_GET['latest'] === 'true';
                $excludeIds = isset($_GET['exclude']) ? explode(',', $_GET['exclude']) : [];
                
                // Base query
                $query = "SELECT 
                    id_timeline,
                    judul_kegiatan,
                    deskripsi,
                    tanggal_kegiatan,
                    waktu_mulai,
                    waktu_selesai,
                    image_path,
                    jenis
                FROM timeline_ukm 
                WHERE 1=1";
                
                $params = [];
                
                // Add exclusion if needed
                if (!empty($excludeIds)) {
                    $placeholders = array_map(function($i) { 
                        return ':exclude' . $i;
                    }, array_keys($excludeIds));
                    
                    $query .= " AND id_timeline NOT IN (" . implode(',', $placeholders) . ")";
                    
                    // Prepare exclude parameters
                    foreach ($excludeIds as $key => $id) {
                        $params['exclude' . $key] = $id;
                    }
                }
                
                // Add ordering
                $query .= " ORDER BY id_timeline DESC";
                $query .= " LIMIT :limit";
                $params['limit'] = $limit;
                
                $stmt = $pdo->prepare($query);
                
                // Bind all parameters
                foreach ($params as $key => $value) {
                    $stmt->bindValue(':' . $key, $value, PDO::PARAM_INT);
                }
                
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo json_encode([
                    'status' => 'success',
                    'data' => $result
                ]);
                exit;
            } catch (Exception $e) {
                error_log("Error in timeline handler: " . $e->getMessage());
                http_response_code(500);
                echo json_encode([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
                exit;
            }
        }

        // Get data timeline berdasarkan UKM
        if (isset($_GET['id_ukm'])) {
            $id_ukm = $_GET['id_ukm'];
            $query = "SELECT t.*, 
                     (SELECT COUNT(*) FROM panitia_proker WHERE id_timeline = t.id_timeline) as jumlah_panitia
                     FROM timeline_ukm t
                     WHERE t.id_ukm = ?
                     ORDER BY t.tanggal_kegiatan DESC";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$id_ukm]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            exit;
        }

        // Get detail timeline untuk edit
        if (isset($_GET['id_timeline'])) {
            $id_timeline = $_GET['id_timeline'];
            $query = "SELECT * FROM timeline_ukm WHERE id_timeline = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$id_timeline]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
            exit;
        }

        // Get panitia untuk dropdown
        if (isset($_GET['action']) && $_GET['action'] === 'get_panitia') {
            $id_ukm = $_GET['id_ukm'];
            $query = "SELECT m.nim, m.nama_lengkap 
                     FROM keanggotaan_ukm k 
                     JOIN mahasiswa m ON k.nim = m.nim
                     WHERE k.id_ukm = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$id_ukm]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            exit;
        }

        // Get jabatan panitia untuk dropdown
        if (isset($_GET['action']) && $_GET['action'] === 'get_jabatan_panitia') {
            $query = "SELECT * FROM jabatan_panitia ORDER BY level";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            exit;
        }

        // Get panitia suatu kegiatan
        if (isset($_GET['action']) && $_GET['action'] === 'get_panitia_kegiatan') {
            try {
                $id_timeline = $_GET['id_timeline'];
                error_log("ID Timeline yang dicari: " . $id_timeline);

                $query = "SELECT p.id_panitia, p.nim, m.nama_lengkap, j.nama_jabatan
                        FROM panitia_proker p
                        JOIN mahasiswa m ON p.nim = m.nim
                        JOIN jabatan_panitia j ON p.id_jabatan_panitia = j.id_jabatan_panitia
                        WHERE p.id_timeline = ?
                        ORDER BY j.level ASC";
                
                $stmt = $pdo->prepare($query);
                $stmt->execute([$id_timeline]);
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                error_log("Query SQL: " . $query);
                error_log("Data panitia dari DB: " . print_r($data, true));
                
                echo json_encode($data ?: []);
            } catch (PDOException $e) {
                error_log("Error Database: " . $e->getMessage());
                echo json_encode(['error' => $e->getMessage()]);
            }
            exit;
        }

        // Jika tidak ada parameter yang cocok
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid request parameters'
        ]);
        exit;

    } catch (Exception $e) {
        error_log("General Error in GET handler: " . $e->getMessage());
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
        exit;
    }
}

// Handle POST request (Create/Update)
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id_timeline = isset($_POST['id_timeline']) ? $_POST['id_timeline'] : null;
        $id_ukm = $_POST['id_ukm'];
        $judul_kegiatan = $_POST['judul_kegiatan'];
        $deskripsi = $_POST['deskripsi'];
        $tanggal_kegiatan = $_POST['tanggal_kegiatan'];
        $waktu_mulai = $_POST['waktu_mulai'];
        $waktu_selesai = $_POST['waktu_selesai'];
        $status = isset($_POST['status']) ? 'active' : 'inactive';
        $jenis = $_POST['jenis'];

        // Handle image upload
        $image_path = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $image_path = uploadImage($_FILES['image']);
            if (!$image_path) {
                throw new Exception('Gagal upload image');
            }
        }

        if ($id_timeline) {
            // Update
            $query = "UPDATE timeline_ukm SET 
                     judul_kegiatan = ?, deskripsi = ?, tanggal_kegiatan = ?,
                     waktu_mulai = ?, waktu_selesai = ?, status = ?, jenis = ?";
            
            $params = [$judul_kegiatan, $deskripsi, $tanggal_kegiatan, 
                      $waktu_mulai, $waktu_selesai, $status, $jenis];

            if ($image_path) {
                $query .= ", image_path = ?";
                $params[] = $image_path;
            }

            $query .= " WHERE id_timeline = ?";
            $params[] = $id_timeline;
        } else {
            // Insert
            $query = "INSERT INTO timeline_ukm 
                     (id_ukm, judul_kegiatan, deskripsi, tanggal_kegiatan, 
                      waktu_mulai, waktu_selesai, status, image_path, jenis)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $params = [$id_ukm, $judul_kegiatan, $deskripsi, $tanggal_kegiatan,
                      $waktu_mulai, $waktu_selesai, $status, $image_path, $jenis];
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);

        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        error_log("Error in POST handler: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

// Handle DELETE request
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    try {
        $pdo->beginTransaction();

        if (!isset($_GET['id_timeline'])) {
            throw new Exception('ID Timeline tidak ditemukan');
        }

        $id_timeline = $_GET['id_timeline'];

        // Get existing image_path
        $query = "SELECT image_path FROM timeline_ukm WHERE id_timeline = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id_timeline]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Delete panitia related to this timeline
        $query = "DELETE FROM panitia_proker WHERE id_timeline = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id_timeline]);

        // Delete all rapat and their dokumentasi
        $query = "SELECT id_rapat, notulensi_path FROM rapat WHERE id_timeline = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id_timeline]);
        $rapat_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rapat_list as $rapat) {
            // Delete dokumentasi files
            $query = "SELECT foto_path FROM dokumentasi_rapat WHERE id_rapat = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$rapat['id_rapat']]);
            $dokumentasi_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($dokumentasi_list as $dokumentasi) {
                if ($dokumentasi['foto_path']) {
                    $foto_path = "../../../frontend/public/assets/dokumentasi/" . $dokumentasi['foto_path'];
                    if (file_exists($foto_path)) {
                        unlink($foto_path);
                    }
                }
            }

            // Delete notulensi file if exists
            if ($rapat['notulensi_path']) {
                $notulensi_path = "../../../frontend/public/assets/notulensi/" . $rapat['notulensi_path'];
                if (file_exists($notulensi_path)) {
                    unlink($notulensi_path);
                }
            }

            // Delete dokumentasi records
            $query = "DELETE FROM dokumentasi_rapat WHERE id_rapat = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$rapat['id_rapat']]);
        }

        // Delete rapat records
        $query = "DELETE FROM rapat WHERE id_timeline = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id_timeline]);

        // Delete timeline
        $query = "DELETE FROM timeline_ukm WHERE id_timeline = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id_timeline]);

        // Delete image file if exists
        if ($data['image_path']) {
            $image_path = "../../../frontend/public/assets/" . $data['image_path'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }

        $pdo->commit();
        echo json_encode(['status' => 'success', 'message' => 'Timeline berhasil dihapus']);
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("Error in DELETE timeline: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>