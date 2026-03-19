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

// Function untuk validasi tanggal
function validateDate($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

// Fungsi untuk upload file notulensi
function uploadNotulensi($file) {
    $target_dir = "../../../frontend/public/assets/notulensi/";
    
    // Buat direktori jika belum ada
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $new_filename = "shafa-" . uniqid() . "." . $file_extension;
    $target_file = $target_dir . $new_filename;

    // Cek ukuran file (max 5MB)
    if ($file["size"] > 5000000) {
        return ['status' => false, 'message' => 'Ukuran file terlalu besar (max 5MB)'];
    }

    // Cek tipe file
    if ($file_extension != "pdf") {
        return ['status' => false, 'message' => 'File harus berformat PDF'];
    }

    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return ['status' => true, 'filename' => $new_filename];
    }
    return ['status' => false, 'message' => 'Gagal upload file'];
}

// Fungsi untuk upload foto dokumentasi
function uploadDokumentasi($file) {
    $target_dir = "../../../frontend/public/assets/dokumentasi/";
    
    // Buat direktori jika belum ada
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $new_filename = "dokumentasi-" . uniqid() . "." . $file_extension;
    $target_file = $target_dir . $new_filename;

    // Cek ukuran file (max 2MB)
    if ($file["size"] > 2000000) {
        return ['status' => false, 'message' => 'Ukuran file terlalu besar (max 2MB)'];
    }

    // Cek tipe file
    if (!in_array($file_extension, ["jpg", "jpeg", "png"])) {
        return ['status' => false, 'message' => 'File harus berformat JPG, JPEG, atau PNG'];
    }

    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return ['status' => true, 'filename' => $new_filename];
    }
    return ['status' => false, 'message' => 'Gagal upload file'];
}

// Fungsi untuk hapus file
function deleteFile($filepath, $type) {
    $base_path = "../../../frontend/public/assets/";
    
    // Tentukan folder berdasarkan tipe
    $folder = $type === 'notulensi' ? 'notulensi/' : 'dokumentasi/';
    
    $full_path = $base_path . $folder . $filepath;
    
    if (file_exists($full_path)) {
        return unlink($full_path);
    }
    return false;
}

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Get rapat by id_timeline
        if (isset($_GET['id_timeline'])) {
            $id_timeline = $_GET['id_timeline'];
            
            $query = "SELECT r.*, 
                     (SELECT COUNT(*) FROM dokumentasi_rapat WHERE id_rapat = r.id_rapat) as jumlah_foto
                     FROM rapat r 
                     WHERE r.id_timeline = ?
                     ORDER BY r.tanggal DESC";
            
            $stmt = $pdo->prepare($query);
            $stmt->execute([$id_timeline]);
            $rapat = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get dokumentasi untuk setiap rapat
            foreach ($rapat as &$r) {
                $query = "SELECT * FROM dokumentasi_rapat WHERE id_rapat = ?";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$r['id_rapat']]);
                $r['dokumentasi'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            jsonResponse('success', 'Data rapat berhasil diambil', $rapat);
        }

        // Get detail rapat by id_rapat
        if (isset($_GET['id_rapat'])) {
            $id_rapat = $_GET['id_rapat'];
            
            $query = "SELECT r.*, 
                     (SELECT COUNT(*) FROM dokumentasi_rapat WHERE id_rapat = r.id_rapat) as jumlah_foto
                     FROM rapat r 
                     WHERE r.id_rapat = ?";
            
            $stmt = $pdo->prepare($query);
            $stmt->execute([$id_rapat]);
            $rapat = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$rapat) {
                jsonResponse('error', 'Data rapat tidak ditemukan');
            }

            // Get dokumentasi
            $query = "SELECT * FROM dokumentasi_rapat WHERE id_rapat = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$id_rapat]);
            $rapat['dokumentasi'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            jsonResponse('success', 'Detail rapat berhasil diambil', $rapat);
        }

        jsonResponse('error', 'Invalid request');
    } catch (Exception $e) {
        jsonResponse('error', $e->getMessage());
    }
}

// Handle POST request (Create/Update)
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();

        // Validasi input
        $required_fields = ['id_timeline_rapat', 'judul_rapat', 'tanggal_rapat'];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                throw new Exception("Field $field harus diisi");
            }
        }

        if (!validateDate($_POST['tanggal_rapat'])) {
            throw new Exception('Format tanggal tidak valid');
        }

        $id_rapat = isset($_POST['id_rapat']) ? $_POST['id_rapat'] : null;
        $id_timeline = $_POST['id_timeline_rapat'];
        $judul = $_POST['judul_rapat'];
        $tanggal = $_POST['tanggal_rapat'];
        
        // Upload notulensi jika ada
        $notulensi_path = null;
        if (isset($_FILES['notulensi']) && $_FILES['notulensi']['error'] === 0) {
            $uploadResult = uploadNotulensi($_FILES['notulensi']);
            if (!$uploadResult['status']) {
                throw new Exception($uploadResult['message']);
            }
            $notulensi_path = $uploadResult['filename'];
        }

        if ($id_rapat) {
            // Update existing record
            $query = "UPDATE rapat SET judul = ?, tanggal = ?";
            $params = [$judul, $tanggal];
            
            if ($notulensi_path) {
                // Get old notulensi path
                $stmt = $pdo->prepare("SELECT notulensi_path FROM rapat WHERE id_rapat = ?");
                $stmt->execute([$id_rapat]);
                $old_data = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Delete old file if exists
                if ($old_data['notulensi_path']) {
                    deleteFile($old_data['notulensi_path'], 'notulensi');
                }
                
                $query .= ", notulensi_path = ?";
                $params[] = $notulensi_path;
            }
            
            $query .= " WHERE id_rapat = ?";
            $params[] = $id_rapat;
            
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);

            $message = 'Rapat berhasil diupdate';
            
        } else {
            // Insert new record
            $query = "INSERT INTO rapat (id_timeline, judul, tanggal, notulensi_path) 
                     VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$id_timeline, $judul, $tanggal, $notulensi_path]);
            
            $id_rapat = $pdo->lastInsertId();
            $message = 'Rapat berhasil ditambahkan';
        }

        // Handle dokumentasi upload
        if (isset($_FILES['dokumentasi']) && !empty($_FILES['dokumentasi']['name'][0])) {
            $successUpload = 0;
            $failedUpload = 0;
            $total_files = count($_FILES['dokumentasi']['name']);
            
            for ($i = 0; $i < $total_files; $i++) {
                $file = [
                    "name" => $_FILES['dokumentasi']['name'][$i],
                    "type" => $_FILES['dokumentasi']['type'][$i],
                    "tmp_name" => $_FILES['dokumentasi']['tmp_name'][$i],
                    "error" => $_FILES['dokumentasi']['error'][$i],
                    "size" => $_FILES['dokumentasi']['size'][$i]
                ];

                if ($file['error'] === 0) {
                    $uploadResult = uploadDokumentasi($file);
                    if ($uploadResult['status']) {
                        $query = "INSERT INTO dokumentasi_rapat (id_rapat, foto_path) VALUES (?, ?)";
                        $stmt = $pdo->prepare($query);
                        $stmt->execute([$id_rapat, $uploadResult['filename']]);
                        $successUpload++;
                    } else {
                        $failedUpload++;
                    }
                }
            }
            
            if ($failedUpload > 0) {
                $message .= ". Berhasil upload $successUpload foto, gagal upload $failedUpload foto";
            }
        }

        $pdo->commit();
        jsonResponse('success', $message);

    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        jsonResponse('error', $e->getMessage());
    }
}

// Handle DELETE request
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    try {
        $pdo->beginTransaction();

        if (!isset($_GET['id_rapat'])) {
            throw new Exception('ID Rapat tidak ditemukan');
        }

        $id_rapat = $_GET['id_rapat'];

        // Get notulensi and dokumentasi paths
        $query = "SELECT notulensi_path FROM rapat WHERE id_rapat = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id_rapat]);
        $rapat = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$rapat) {
            throw new Exception('Data rapat tidak ditemukan');
        }

        // Get all dokumentasi paths before deleting
        $query = "SELECT foto_path FROM dokumentasi_rapat WHERE id_rapat = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id_rapat]);
        $dokumentasi = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Delete physical files first
        $deleteErrors = [];
        
        // Delete notulensi file if exists
        if ($rapat['notulensi_path']) {
            if (!deleteFile($rapat['notulensi_path'], 'notulensi')) {
                $deleteErrors[] = "Gagal menghapus file notulensi";
            }
        }

        // Delete all dokumentasi files
        foreach ($dokumentasi as $dok) {
            if ($dok['foto_path'] && !deleteFile($dok['foto_path'], 'dokumentasi')) {
                $deleteErrors[] = "Gagal menghapus file dokumentasi: " . $dok['foto_path'];
            }
        }

        // Delete database records
        $query = "DELETE FROM dokumentasi_rapat WHERE id_rapat = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id_rapat]);

        $query = "DELETE FROM rapat WHERE id_rapat = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id_rapat]);

        $pdo->commit();

        // If there were any file deletion errors, include them in the response
        if (!empty($deleteErrors)) {
            jsonResponse('warning', 'Rapat berhasil dihapus dari database, tetapi beberapa file tidak dapat dihapus: ' . implode(', ', $deleteErrors));
        } else {
            jsonResponse('success', 'Rapat dan semua file terkait berhasil dihapus');
        }

    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        jsonResponse('error', $e->getMessage());
    }
}

// Handle invalid request method
else {
    jsonResponse('error', 'Invalid request method');
}
?>