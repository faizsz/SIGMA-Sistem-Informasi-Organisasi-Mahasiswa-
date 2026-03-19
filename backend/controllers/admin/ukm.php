<?php
require_once '../../config/config.php';

// Set header JSON
header('Content-Type: application/json');

// Get action from URL parameter
$action = $_GET['action'] ?? '';

switch($action) {
    case 'get_all':
        getAllUkm();
        break;
    case 'get_one':
        getOneUkm();
        break;
    case 'add':
        addUkm();
        break;
    case 'edit':
        editUkm();
        break;
    case 'delete':
        deleteUkm();
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Action not found']);
        break;
}

function getAllUkm() {
    global $pdo;
    
    try {
        $query = "SELECT * FROM ukm";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['data' => $data]);
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function getOneUkm() {
    global $pdo;
    
    $id = $_GET['id'] ?? '';
    
    try {
        $query = "SELECT * FROM ukm WHERE id_ukm = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode($data);
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function addUkm() {
    global $pdo;
    
    try {
        // Handle logo upload
        $logo_path = '';
        if(isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
            // Validate file size (5MB)
            if($_FILES['logo']['size'] > 5 * 1024 * 1024) {
                throw new Exception('Ukuran file logo terlalu besar (maksimal 5MB)');
            }
            
            // Validate file type
            $file_type = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
            if($file_type != 'png') {
                throw new Exception('Format file logo harus PNG');
            }
            
            // Generate filename
            $logo_path = 'logo-' . strtolower(str_replace(' ', '-', $_POST['nama_ukm'])) . '.png';
            $target_path = $_SERVER['DOCUMENT_ROOT'] . '/frontend/public/assets/' . $logo_path;
            
            // Move uploaded file
            if(!move_uploaded_file($_FILES['logo']['tmp_name'], $target_path)) {
                throw new Exception('Gagal upload file logo');
            }
        }

        // Handle cover upload (BARU)
        $banner_path = '';
        if(isset($_FILES['cover']) && $_FILES['cover']['error'] == 0) {
            // Validate file size (5MB)
            if($_FILES['cover']['size'] > 5 * 1024 * 1024) {
                throw new Exception('Ukuran file cover terlalu besar (maksimal 5MB)');
            }
            
            // Validate file type
            $file_type = strtolower(pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION));
            if(!in_array($file_type, ['jpg', 'jpeg', 'png'])) {
                throw new Exception('Format file cover harus JPG atau PNG');
            }
            
            // Generate filename
            $banner_path = 'cover-' . strtolower(str_replace(' ', '-', $_POST['nama_ukm'])) . '.' . $file_type;
            $target_path = $_SERVER['DOCUMENT_ROOT'] . '/frontend/public/assets/' . $banner_path;
            
            // Move uploaded file
            if(!move_uploaded_file($_FILES['cover']['tmp_name'], $target_path)) {
                throw new Exception('Gagal upload file cover');
            }
        }

        // Convert date format from DD/MM/YYYY to YYYY-MM-DD
        $tanggal_berdiri = DateTime::createFromFormat('d/m/Y', $_POST['tanggal_berdiri']);
        $tanggal_berdiri = $tanggal_berdiri ? $tanggal_berdiri->format('Y-m-d') : null;
        
        // Insert ke database (UPDATED)
        $query = "INSERT INTO ukm (nama_ukm, deskripsi, visi, misi, tanggal_berdiri, logo_path, banner_path) 
                  VALUES (:nama_ukm, :deskripsi, :visi, :misi, :tanggal_berdiri, :logo_path, :banner_path)";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'nama_ukm' => $_POST['nama_ukm'],
            'deskripsi' => $_POST['deskripsi'],
            'visi' => $_POST['visi'],
            'misi' => $_POST['misi'],
            'tanggal_berdiri' => $tanggal_berdiri,
            'logo_path' => $logo_path,
            'banner_path' => $banner_path // BARU
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Data berhasil ditambahkan']);
        
    } catch(Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function editUkm() {
    global $pdo;
    
    try {
        // Get current data
        $query = "SELECT logo_path, banner_path FROM ukm WHERE id_ukm = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $_POST['id_ukm']]);
        $current_data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $logo_path = $current_data['logo_path'];
        $banner_path = $current_data['banner_path'];  // BARU
        
        // Handle logo upload if new logo is uploaded
        if(isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
            // ... kode validasi logo sama seperti sebelumnya ...

            // Delete old logo if exists
            if($current_data['logo_path']) {
                $old_file = $_SERVER['DOCUMENT_ROOT'] . '/frontend/public/assets/' . $current_data['logo_path'];
                if(file_exists($old_file)) {
                    unlink($old_file);
                }
            }
            
            // Generate filename
            $logo_path = 'logo-' . strtolower(str_replace(' ', '-', $_POST['nama_ukm'])) . '.png';
            $target_path = $_SERVER['DOCUMENT_ROOT'] . '/frontend/public/assets/' . $logo_path;
            
            if(!move_uploaded_file($_FILES['logo']['tmp_name'], $target_path)) {
                throw new Exception('Gagal upload file logo');
            }
        }

        // Handle cover upload if new cover is uploaded (BARU)
        if(isset($_FILES['cover']) && $_FILES['cover']['error'] == 0) {
            if($_FILES['cover']['size'] > 5 * 1024 * 1024) {
                throw new Exception('Ukuran file cover terlalu besar (maksimal 5MB)');
            }
            
            $file_type = strtolower(pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION));
            if(!in_array($file_type, ['jpg', 'jpeg', 'png'])) {
                throw new Exception('Format file cover harus JPG atau PNG');
            }
            
            // Delete old cover if exists
            if($current_data['banner_path']) {
                $old_file = $_SERVER['DOCUMENT_ROOT'] . '/frontend/public/assets/' . $current_data['banner_path'];
                if(file_exists($old_file)) {
                    unlink($old_file);
                }
            }
            
            // Generate filename
            $banner_path = 'cover-' . strtolower(str_replace(' ', '-', $_POST['nama_ukm'])) . '.' . $file_type;
            $target_path = $_SERVER['DOCUMENT_ROOT'] . '/frontend/public/assets/' . $banner_path;
            
            if(!move_uploaded_file($_FILES['cover']['tmp_name'], $target_path)) {
                throw new Exception('Gagal upload file cover');
            }
        }

        // Convert date format from DD/MM/YYYY to YYYY-MM-DD
        $tanggal_berdiri = DateTime::createFromFormat('d/m/Y', $_POST['tanggal_berdiri']);
        $tanggal_berdiri = $tanggal_berdiri ? $tanggal_berdiri->format('Y-m-d') : null;
        
        // Update database (UPDATED)
        $query = "UPDATE ukm SET 
                  nama_ukm = :nama_ukm,
                  deskripsi = :deskripsi,
                  visi = :visi,
                  misi = :misi,
                  tanggal_berdiri = :tanggal_berdiri,
                  logo_path = :logo_path,
                  banner_path = :banner_path
                  WHERE id_ukm = :id_ukm";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'id_ukm' => $_POST['id_ukm'],
            'nama_ukm' => $_POST['nama_ukm'],
            'deskripsi' => $_POST['deskripsi'],
            'visi' => $_POST['visi'],
            'misi' => $_POST['misi'],
            'tanggal_berdiri' => $tanggal_berdiri,
            'logo_path' => $logo_path,
            'banner_path' => $banner_path  // BARU
        ]);
        
        echo json_encode(['status' => 'success', 'message' => 'Data berhasil diupdate']);
    } catch(Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function deleteUkm() {
    global $pdo;
    
    try {
        // Validate input
        if (!isset($_POST['id_ukm']) || empty($_POST['id_ukm'])) {
            throw new Exception('ID UKM tidak valid');
        }

        $pdo->beginTransaction();

        // Get file paths
        $query = "SELECT logo_path, banner_path FROM ukm WHERE id_ukm = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $_POST['id_ukm']]);
        
        if ($stmt->rowCount() === 0) {
            throw new Exception('Data UKM tidak ditemukan');
        }
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Delete logo file if exists
        if($data['logo_path']) {
            $file_path = $_SERVER['DOCUMENT_ROOT'] . '/frontend/public/assets/' . $data['logo_path'];
            if(file_exists($file_path)) {
                if (!unlink($file_path)) {
                    throw new Exception('Gagal menghapus file logo');
                }
            }
        }

        // Delete cover file if exists
        if($data['banner_path']) {
            $file_path = $_SERVER['DOCUMENT_ROOT'] . '/frontend/public/assets/' . $data['banner_path'];
            if(file_exists($file_path)) {
                if (!unlink($file_path)) {
                    throw new Exception('Gagal menghapus file cover');
                }
            }
        }

        // Delete from database
        $query = "DELETE FROM ukm WHERE id_ukm = :id_ukm";
        $stmt = $pdo->prepare($query);
        $success = $stmt->execute(['id_ukm' => $_POST['id_ukm']]);
        
        if (!$success) {
            throw new Exception('Gagal menghapus data dari database');
        }

        $pdo->commit();
        echo json_encode(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        
    } catch(Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("Error deleting UKM: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>