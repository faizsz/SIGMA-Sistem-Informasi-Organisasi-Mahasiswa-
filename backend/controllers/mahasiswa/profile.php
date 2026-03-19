<?php
session_start();
require_once '../../config/config.php';
header('Content-Type: application/json');

try {
    if (!isset($_SESSION['username'])) {
        throw new Exception('User not logged in');
    }

    // Debug logging for request
    error_log("Processing profile update for user: " . $_SESSION['username']);
    error_log("POST data: " . print_r($_POST, true));
    error_log("FILES data: " . print_r($_FILES, true));

    $nim = $_SESSION['username'];
    
    // Define base path and upload directory
    define('BASE_PATH', realpath(__DIR__ . '/../../'));
    $uploadDir = BASE_PATH . '/frontend/public/assets/profile/';
    
    // Debug logging for directory
    error_log("Upload directory: " . $uploadDir);
    error_log("Directory exists: " . (is_dir($uploadDir) ? 'yes' : 'no'));
    error_log("Directory writable: " . (is_writable($uploadDir) ? 'yes' : 'no'));

    // Start transaction
    $pdo->beginTransaction();

    // Get form data
    $nama = isset($_POST['nama']) ? trim($_POST['nama']) : null;
    $jenis_kelamin = isset($_POST['jenis_kelamin']) ? trim($_POST['jenis_kelamin']) : null;
    $program_studi = isset($_POST['program_studi']) ? trim($_POST['program_studi']) : null;
    $kelas = isset($_POST['kelas']) ? trim($_POST['kelas']) : null;
    $alamat = isset($_POST['alamat']) ? trim($_POST['alamat']) : null;
    $no_whatsapp = isset($_POST['no_whatsapp']) ? trim($_POST['no_whatsapp']) : null;
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    
    // Initialize update arrays
    $updateFields = [];
    $params = [];

    // Handle file upload first
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['profile_picture'];
        error_log("Processing file upload: " . print_r($file, true));
        
        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Invalid file type. Only JPG, PNG and GIF are allowed.');
        }
        
        // Validate file size
        $maxFileSize = 5 * 1024 * 1024; // 5MB
        if ($file['size'] > $maxFileSize) {
            throw new Exception('File is too large. Maximum size is 5MB.');
        }
        
        // Create upload directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            error_log("Creating upload directory: " . $uploadDir);
            if (!mkdir($uploadDir, 0777, true)) {
                throw new Exception('Failed to create upload directory.');
            }
            chmod($uploadDir, 0777);
            error_log("Directory created successfully");
        }

        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'profile_' . $nim . '_' . time() . '.' . $extension;
        $targetPath = $uploadDir . $filename;
        error_log("Target path for upload: " . $targetPath);

        // Delete old profile picture
        $stmt = $pdo->prepare("SELECT foto_path FROM mahasiswa WHERE nim = ?");
        $stmt->execute([$nim]);
        $oldPhoto = $stmt->fetchColumn();
        error_log("Old photo path: " . $oldPhoto);
        
        if ($oldPhoto && $oldPhoto !== 'pp.jpg' && file_exists($uploadDir . $oldPhoto)) {
            error_log("Attempting to delete old photo: " . $uploadDir . $oldPhoto);
            if (unlink($uploadDir . $oldPhoto)) {
                error_log("Old photo deleted successfully");
            } else {
                error_log("Failed to delete old photo");
            }
        }

        // Upload new file
        error_log("Attempting to move uploaded file to: " . $targetPath);
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            $uploadError = error_get_last();
            error_log("Upload failed. PHP Error: " . ($uploadError ? print_r($uploadError, true) : 'No error details available'));
            throw new Exception('Failed to upload file. Check server logs for details.');
        }
        error_log("File uploaded successfully");

        // Verify file exists
        if (!file_exists($targetPath)) {
            error_log("File does not exist after upload: " . $targetPath);
            throw new Exception('File upload verification failed.');
        }

        // Update database with new photo path
        $updatePhotoStmt = $pdo->prepare("UPDATE mahasiswa SET foto_path = ? WHERE nim = ?");
        if (!$updatePhotoStmt->execute([$filename, $nim])) {
            error_log("Database update failed. Error: " . print_r($updatePhotoStmt->errorInfo(), true));
            throw new Exception('Failed to update photo in database.');
        }
        error_log("Database updated with new photo path");

        // Verify database update
        $verifyStmt = $pdo->prepare("SELECT foto_path FROM mahasiswa WHERE nim = ?");
        $verifyStmt->execute([$nim]);
        $newPhotoPath = $verifyStmt->fetchColumn();
        error_log("Verified new photo path in database: " . $newPhotoPath);
    }

    // Build query for other fields
    if ($nama !== null) {
        $updateFields[] = 'nama_lengkap = ?';
        $params[] = $nama;
    }
    if ($jenis_kelamin !== null) {
        $updateFields[] = 'jenis_kelamin = ?';
        $params[] = $jenis_kelamin;
    }
    if ($program_studi !== null) {
        $updateFields[] = 'id_program_studi = ?';
        $params[] = $program_studi;
    }
    if ($kelas !== null) {
        $updateFields[] = 'kelas = ?';
        $params[] = $kelas;
    }
    if ($alamat !== null) {
        $updateFields[] = 'alamat = ?';
        $params[] = $alamat;
    }
    if ($no_whatsapp !== null) {
        $updateFields[] = 'no_whatsapp = ?';
        $params[] = $no_whatsapp;
    }
    if ($email !== null) {
        $updateFields[] = 'email = ?';
        $params[] = $email;
    }

    // Update other data if there are fields to update
    if (!empty($updateFields)) {
        $sql = "UPDATE mahasiswa SET " . implode(', ', $updateFields) . " WHERE nim = ?";
        $params[] = $nim;
        
        error_log("Executing profile update query: " . $sql);
        error_log("Query parameters: " . print_r($params, true));
        
        $stmt = $pdo->prepare($sql);
        if (!$stmt->execute($params)) {
            error_log("Profile update failed. Error: " . print_r($stmt->errorInfo(), true));
            throw new Exception('Failed to update profile data.');
        }
        error_log("Profile data updated successfully");
    }
    
    // Commit transaction
    $pdo->commit();
    error_log("Transaction committed successfully");
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Profile updated successfully'
    ]);
    
} catch (Exception $e) {
    error_log("Profile update error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
        error_log("Transaction rolled back");
    }
    
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage(),
        'details' => debug_backtrace()
    ]);
}
?>