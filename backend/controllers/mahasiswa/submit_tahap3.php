<?php
session_start();
require_once '../../config/config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Method not allowed'
    ]);
    exit;
}

// Definisikan konstanta dan helper function
define('UPLOAD_DIR', dirname(dirname(dirname(__FILE__))) . '/frontend/public/uploads/dokumen_pendaftaran');

function handleFileUpload($file, $prefix, $allowedTypes = [], $maxSize = 2097152) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Error upload file: ' . $file['error']);
    }

    if (!empty($allowedTypes) && !in_array($file['type'], $allowedTypes)) {
        throw new Exception('Tipe file tidak diizinkan untuk ' . $prefix);
    }

    if ($file['size'] > $maxSize) {
        throw new Exception('Ukuran file ' . $prefix . ' melebihi batas maksimum (2MB)');
    }

    // Generate unique filename
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $fileName = $prefix . '-' . uniqid() . '.' . $ext;
    $targetPath = UPLOAD_DIR . '/' . $fileName;

    // Create directory if it doesn't exist
    if (!file_exists(UPLOAD_DIR)) {
        if (!mkdir(UPLOAD_DIR, 0755, true)) {
            throw new Exception('Gagal membuat direktori upload');
        }
    }

    // Validate directory is writable
    if (!is_writable(UPLOAD_DIR)) {
        throw new Exception('Direktori upload tidak writable');
    }

    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        throw new Exception('Gagal mengupload file ' . $prefix);
    }

    return $fileName;
}

try {
    $nim = $_SESSION['username'];
    $id_ukm = $_POST['id_ukm'];

    // Start transaction
    $pdo->beginTransaction();

    // 1. Get pendaftaran_id and verify current status
    $queryPendaftaran = "SELECT id_pendaftaran FROM pendaftaran_ukm 
                        WHERE nim = :nim 
                        AND id_ukm = :id_ukm
                        AND status = 'acc_tahap2'
                        ORDER BY tanggal_pendaftaran DESC LIMIT 1";
    $stmtPendaftaran = $pdo->prepare($queryPendaftaran);
    $stmtPendaftaran->execute([
        'nim' => $nim,
        'id_ukm' => $id_ukm
    ]);
    $pendaftaran = $stmtPendaftaran->fetch(PDO::FETCH_ASSOC);

    if (!$pendaftaran) {
        throw new Exception('Data pendaftaran tidak ditemukan atau status tidak sesuai');
    }

    // 2. Update pendaftaran_ukm with new status
    $queryUpdate = "UPDATE pendaftaran_ukm 
                   SET tahap_seleksi = 'tahap3',
                       status = 'pending_tahap3',
                       waktu_submit_tahap3 = NOW()
                   WHERE id_pendaftaran = :id_pendaftaran";

    $stmt = $pdo->prepare($queryUpdate);
    $stmt->execute([
        'id_pendaftaran' => $pendaftaran['id_pendaftaran']
    ]);

    // 3. Handle file uploads
    $fileFields = [
        'scan_ktm' => [
            'prefix' => 'ktm',
            'jenis' => 4,
            'allowedTypes' => ['application/pdf', 'image/jpeg', 'image/png']
        ],
        'scan_khs' => [
            'prefix' => 'khs',
            'jenis' => 5,
            'allowedTypes' => ['application/pdf', 'image/jpeg', 'image/png']
        ],
        'cv' => [
            'prefix' => 'cv',
            'jenis' => 6,
            'allowedTypes' => ['application/pdf']
        ],
        'motivation_letter' => [
            'prefix' => 'motivation',
            'jenis' => 7,
            'allowedTypes' => ['application/pdf']
        ]
    ];

    foreach ($fileFields as $fieldName => $config) {
        if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
            throw new Exception("File {$fieldName} wajib diupload");
        }

        try {
            $fileName = handleFileUpload(
                $_FILES[$fieldName],
                $config['prefix'],
                $config['allowedTypes']
            );

            // Insert document record
            $queryDokumen = "INSERT INTO dokumen_pendaftaran (
                                id_pendaftaran, 
                                id_jenis_dokumen, 
                                file_path
                            ) VALUES (
                                :id_pendaftaran,
                                :jenis_dokumen,
                                :file_path
                            )";
            
            $stmtDokumen = $pdo->prepare($queryDokumen);
            $stmtDokumen->execute([
                'id_pendaftaran' => $pendaftaran['id_pendaftaran'],
                'jenis_dokumen' => $config['jenis'],
                'file_path' => $fileName
            ]);

        } catch (Exception $e) {
            error_log("Error uploading file {$fieldName}: " . $e->getMessage());
            throw new Exception("Gagal mengupload file {$fieldName}: " . $e->getMessage());
        }
    }

    // 4. Insert into history_pendaftaran
    $queryHistory = "INSERT INTO history_pendaftaran (
                      id_pendaftaran, 
                      nim, 
                      id_ukm,
                      status,
                      tanggal_pendaftaran, 
                      tanggal_update_status
                    ) VALUES (
                      :id_pendaftaran, 
                      :nim, 
                      :id_ukm,
                      'pending_tahap3',
                      NOW(), 
                      NOW()
                    )";

    $stmtHistory = $pdo->prepare($queryHistory);
    $stmtHistory->execute([
        'id_pendaftaran' => $pendaftaran['id_pendaftaran'],
        'nim' => $nim,
        'id_ukm' => $id_ukm
    ]);

    $pdo->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Pendaftaran tahap 3 berhasil'
    ]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("Error in submit_tahap3.php: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}