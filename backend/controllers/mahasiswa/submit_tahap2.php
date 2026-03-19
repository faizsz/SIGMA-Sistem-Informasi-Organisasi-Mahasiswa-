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

// Definisikan konstanta dan helper function di awal
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
    $divisi1 = $_POST['divisi_pilihan_1'];
    $divisi2 = $_POST['divisi_pilihan_2'];

    // Start transaction
    $pdo->beginTransaction();

    // 1. Get pendaftaran_id and verify current status
    $queryPendaftaran = "SELECT id_pendaftaran FROM pendaftaran_ukm 
                        WHERE nim = :nim 
                        AND id_ukm = :id_ukm
                        AND status = 'acc_tahap1'
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
                   SET tahap_seleksi = 'tahap2',
                       status = 'pending_tahap2',
                       id_divisi_pilihan_1 = :divisi1,
                       id_divisi_pilihan_2 = :divisi2,
                       waktu_submit_tahap2 = NOW()
                   WHERE id_pendaftaran = :id_pendaftaran";

    $stmt = $pdo->prepare($queryUpdate);
    $stmt->execute([
        'divisi1' => $divisi1,
        'divisi2' => $divisi2,
        'id_pendaftaran' => $pendaftaran['id_pendaftaran']
    ]);

    // 3. Handle file uploads
    $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];
    
    $fileFields = [
        'izin_ortu' => ['prefix' => 'izin_ortu', 'jenis' => 1],
        'sertifikat_wa_rna' => ['prefix' => 'sertifikat_wa_rna', 'jenis' => 2],
        'sertifikat_lkmm' => ['prefix' => 'sertifikat_lkmm', 'jenis' => 3]
    ];

    foreach ($fileFields as $fieldName => $config) {
        if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]['error'] === UPLOAD_ERR_OK) {
            try {
                $fileName = handleFileUpload(
                    $_FILES[$fieldName],
                    $config['prefix'],
                    $allowedTypes
                );

                // Check if document already exists
                $queryCheck = "SELECT id_dokumen FROM dokumen_pendaftaran 
                             WHERE id_pendaftaran = :id_pendaftaran 
                             AND id_jenis_dokumen = :jenis_dokumen";
                $stmtCheck = $pdo->prepare($queryCheck);
                $stmtCheck->execute([
                    'id_pendaftaran' => $pendaftaran['id_pendaftaran'],
                    'jenis_dokumen' => $config['jenis']
                ]);
                
                if ($stmtCheck->fetch()) {
                    $queryUpdate = "UPDATE dokumen_pendaftaran 
                                  SET file_path = :file_path 
                                  WHERE id_pendaftaran = :id_pendaftaran 
                                  AND id_jenis_dokumen = :jenis_dokumen";
                } else {
                    $queryUpdate = "INSERT INTO dokumen_pendaftaran (
                                    id_pendaftaran, id_jenis_dokumen, file_path
                                  ) VALUES (
                                    :id_pendaftaran, :jenis_dokumen, :file_path
                                  )";
                }
                
                $stmtDokumen = $pdo->prepare($queryUpdate);
                $stmtDokumen->execute([
                    'id_pendaftaran' => $pendaftaran['id_pendaftaran'],
                    'jenis_dokumen' => $config['jenis'],
                    'file_path' => $fileName
                ]);

            } catch (Exception $e) {
                error_log("Error uploading file {$fieldName}: " . $e->getMessage());
                throw $e;
            }
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
                      'pending_tahap2',
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
        'message' => 'Pendaftaran tahap 2 berhasil'
    ]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("Error in submit_tahap2.php: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'Error: ' . $e->getMessage()
    ]);
}