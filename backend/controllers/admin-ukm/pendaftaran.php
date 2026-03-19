<?php
session_start();
require_once __DIR__ . '/../../config/config.php';
header('Content-Type: application/json');

// Check authentication
if (!isset($_SESSION['id_ukm'])) {
    http_response_code(401);
    exit(json_encode(['status' => 'error', 'message' => 'Unauthorized']));
}

$id_ukm = $_SESSION['id_ukm'];
$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

// Status mapping constants
const STATUS_MAPPING = [
    'tahap1' => [
        'acc' => 'acc_tahap1',
        'reject' => 'ditolak'
    ],  
    'tahap2' => [
        'acc' => 'acc_tahap2',
        'reject' => 'ditolak'
    ],
    'tahap3' => [
        'acc' => 'acc_tahap3',
        'reject' => 'ditolak'
    ]
];

try {
    switch($action) {
        case 'get_periode':
            $query = "SELECT * FROM periode_pendaftaran_ukm 
                     WHERE id_ukm = :id_ukm AND status = 'aktif'
                     ORDER BY created_at DESC LIMIT 1";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['id_ukm' => $id_ukm]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($data) {
                // Format dates for frontend
                $data['tanggal_buka'] = date('Y-m-d\TH:i', strtotime($data['tanggal_buka']));
                $data['tanggal_tutup'] = date('Y-m-d\TH:i', strtotime($data['tanggal_tutup']));
            }
            
            exit(json_encode([
                'status' => 'success',
                'data' => $data
            ]));

            case 'update_periode':
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                    throw new Exception('Invalid request method');
                }
            
                // Validate required fields
                $required_fields = [
                    'tanggal_buka', 
                    'batas_waktu_tahap1',
                    'batas_waktu_tahap2', 
                    'batas_waktu_tahap3'
                ];
            
                foreach ($required_fields as $field) {
                    if (empty($_POST[$field])) {
                        throw new Exception("Field {$field} is required");
                    }
                }
            
                // Validate and process dates
                $tanggal_buka = DateTime::createFromFormat('Y-m-d\TH:i', $_POST['tanggal_buka']);
                if (!$tanggal_buka) {
                    throw new Exception('Format tanggal buka tidak valid');
                }
            
                // Validate duration limits
                $durations = [
                    'batas_waktu_tahap1' => intval($_POST['batas_waktu_tahap1']),
                    'batas_waktu_tahap2' => intval($_POST['batas_waktu_tahap2']),
                    'batas_waktu_tahap3' => intval($_POST['batas_waktu_tahap3'])
                ];
            
                foreach ($durations as $field => $value) {
                    if ($value < 1) {
                        throw new Exception("$field harus lebih dari 0 hari");
                    }
                }
            
                // Calculate end dates for each phase
                $total_days = array_sum($durations);
                $tanggal_tutup = clone $tanggal_buka;
                $tanggal_tutup->modify("+$total_days days");
            
                // Calculate phase transition dates
                $tahap1_end = clone $tanggal_buka;
                $tahap1_end->modify("+{$durations['batas_waktu_tahap1']} days");
                
                $tahap2_start = clone $tahap1_end;
                $tahap2_end = clone $tahap2_start;
                $tahap2_end->modify("+{$durations['batas_waktu_tahap2']} days");
                
                $tahap3_start = clone $tahap2_end;
                $tahap3_end = clone $tahap3_start;
                $tahap3_end->modify("+{$durations['batas_waktu_tahap3']} days");
            
                // Begin transaction
                $pdo->beginTransaction();
            
                try {
                    // Set all current active periods to inactive
                    $stmt = $pdo->prepare("UPDATE periode_pendaftaran_ukm 
                                         SET status = 'tidak aktif' 
                                         WHERE id_ukm = :id_ukm AND status = 'aktif'");
                    $stmt->execute(['id_ukm' => $id_ukm]);
            
                    // Insert new period
                    $query = "INSERT INTO periode_pendaftaran_ukm 
                             (id_ukm, tanggal_buka, tanggal_tutup, 
                              batas_waktu_tahap1, batas_waktu_tahap2, batas_waktu_tahap3,
                              tahap1_end, tahap2_start, tahap2_end, tahap3_start, tahap3_end,
                              status) 
                             VALUES 
                             (:id_ukm, :tanggal_buka, :tanggal_tutup,
                              :batas_waktu_tahap1, :batas_waktu_tahap2, :batas_waktu_tahap3,
                              :tahap1_end, :tahap2_start, :tahap2_end, :tahap3_start, :tahap3_end,
                              'aktif')";
                    
                    $stmt = $pdo->prepare($query);
                    $result = $stmt->execute([
                        'id_ukm' => $id_ukm,
                        'tanggal_buka' => $tanggal_buka->format('Y-m-d H:i:s'),
                        'tanggal_tutup' => $tanggal_tutup->format('Y-m-d H:i:s'),
                        'batas_waktu_tahap1' => $durations['batas_waktu_tahap1'],
                        'batas_waktu_tahap2' => $durations['batas_waktu_tahap2'],
                        'batas_waktu_tahap3' => $durations['batas_waktu_tahap3'],
                        'tahap1_end' => $tahap1_end->format('Y-m-d H:i:s'),
                        'tahap2_start' => $tahap2_start->format('Y-m-d H:i:s'),
                        'tahap2_end' => $tahap2_end->format('Y-m-d H:i:s'),
                        'tahap3_start' => $tahap3_start->format('Y-m-d H:i:s'),
                        'tahap3_end' => $tahap3_end->format('Y-m-d H:i:s')
                    ]);
            
                    if (!$result) {
                        throw new Exception('Gagal menyimpan periode pendaftaran');
                    }
            
                    $pdo->commit();
                    
                    exit(json_encode([
                        'status' => 'success',
                        'message' => 'Periode pendaftaran berhasil disimpan',
                        'data' => [
                            'tanggal_buka' => $tanggal_buka->format('Y-m-d H:i'),
                            'tanggal_tutup' => $tanggal_tutup->format('Y-m-d H:i'),
                            'tahap1_end' => $tahap1_end->format('Y-m-d H:i'),
                            'tahap2_end' => $tahap2_end->format('Y-m-d H:i'),
                            'tahap3_end' => $tahap3_end->format('Y-m-d H:i')
                        ]
                    ]));
            
                } catch (Exception $e) {
                    $pdo->rollBack();
                    throw $e;
                }
        case 'get_tahap1':
            try {
                $query = "SELECT p.*, m.nama_lengkap, m.jenis_kelamin, m.alamat, ps.nama_program_studi 
                        FROM pendaftaran_ukm p
                        JOIN mahasiswa m ON p.nim = m.nim
                        LEFT JOIN program_studi ps ON m.id_program_studi = ps.id_program_studi
                        WHERE p.id_ukm = :id_ukm 
                        AND p.tahap_seleksi = 'tahap1'
                        AND p.id_periode_pendaftaran = (
                            SELECT id_periode_pendaftaran 
                            FROM periode_pendaftaran_ukm 
                            WHERE id_ukm = :id_ukm 
                            AND status = 'aktif'
                        )";
                
                $stmt = $pdo->prepare($query);
                $stmt->execute(['id_ukm' => $id_ukm]);
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Debug info
                error_log("Query result count: " . count($data));
                
                exit(json_encode([
                    'draw' => isset($_GET['draw']) ? intval($_GET['draw']) : 1,
                    'recordsTotal' => count($data),
                    'recordsFiltered' => count($data),
                    'data' => $data
                ]));
                
            } catch (PDOException $e) {
                error_log("Database Error: " . $e->getMessage());
                http_response_code(500);
                exit(json_encode([
                    'error' => true,
                    'message' => 'Database error: ' . $e->getMessage()
                ]));
            }
            break;
            
        // Di pendaftaran.php:
        case 'get_tahap2':
            try {
                $query = "SELECT p.*, m.nama_lengkap, m.jenis_kelamin, m.alamat, ps.nama_program_studi,
                                d.nama_divisi,
                                dp1.file_path as izin_ortu_path,
                                dp2.file_path as sertifikat_warna_path
                        FROM pendaftaran_ukm p
                        JOIN mahasiswa m ON p.nim = m.nim
                        LEFT JOIN program_studi ps ON m.id_program_studi = ps.id_program_studi
                        LEFT JOIN divisi_ukm d ON p.id_divisi_pilihan_1 = d.id_divisi
                        LEFT JOIN dokumen_pendaftaran dp1 ON p.id_pendaftaran = dp1.id_pendaftaran 
                            AND dp1.id_jenis_dokumen = 1
                        LEFT JOIN dokumen_pendaftaran dp2 ON p.id_pendaftaran = dp2.id_pendaftaran 
                            AND dp2.id_jenis_dokumen = 2
                        WHERE p.id_ukm = :id_ukm 
                        AND p.tahap_seleksi = 'tahap2'
                        AND p.id_periode_pendaftaran = (
                            SELECT id_periode_pendaftaran 
                            FROM periode_pendaftaran_ukm 
                            WHERE id_ukm = :id_ukm 
                            AND status = 'aktif'
                        )";
                
                $stmt = $pdo->prepare($query);
                $stmt->execute(['id_ukm' => $id_ukm]);
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                exit(json_encode([
                    'draw' => isset($_GET['draw']) ? intval($_GET['draw']) : 1,
                    'recordsTotal' => count($data),
                    'recordsFiltered' => count($data),
                    'data' => $data
                ]));
                
            } catch (PDOException $e) {
                error_log("Database Error: " . $e->getMessage());
                http_response_code(500);
                exit(json_encode([
                    'error' => true,
                    'message' => 'Database error: ' . $e->getMessage()
                ]));
            }
            break;

        case 'get_tahap3':
            try {
                $query = "SELECT p.*, m.nama_lengkap, m.jenis_kelamin, m.alamat, ps.nama_program_studi,
                                dp6.file_path as cv_path,
                                dp7.file_path as motivation_letter_path
                        FROM pendaftaran_ukm p
                        JOIN mahasiswa m ON p.nim = m.nim
                        LEFT JOIN program_studi ps ON m.id_program_studi = ps.id_program_studi
                        LEFT JOIN dokumen_pendaftaran dp6 ON p.id_pendaftaran = dp6.id_pendaftaran 
                            AND dp6.id_jenis_dokumen = 6
                        LEFT JOIN dokumen_pendaftaran dp7 ON p.id_pendaftaran = dp7.id_pendaftaran 
                            AND dp7.id_jenis_dokumen = 7
                        WHERE p.id_ukm = :id_ukm 
                        AND p.tahap_seleksi = 'tahap3'
                        AND p.id_periode_pendaftaran = (
                            SELECT id_periode_pendaftaran 
                            FROM periode_pendaftaran_ukm 
                            WHERE id_ukm = :id_ukm 
                            AND status = 'aktif'
                        )";
                
                $stmt = $pdo->prepare($query);
                $stmt->execute(['id_ukm' => $id_ukm]);
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                exit(json_encode([
                    'draw' => isset($_GET['draw']) ? intval($_GET['draw']) : 1,
                    'recordsTotal' => count($data),
                    'recordsFiltered' => count($data),
                    'data' => $data
                ]));
                
            } catch (PDOException $e) {
                error_log("Database Error: " . $e->getMessage());
                http_response_code(500);
                exit(json_encode([
                    'error' => true,
                    'message' => 'Database error: ' . $e->getMessage()
                ]));
            }
            break;
            
            case 'review':
                error_log('Review action received. POST data: ' . print_r($_POST, true));
                
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                    throw new Exception('Invalid request method: ' . $_SERVER['REQUEST_METHOD']);
                }
            
                $id_pendaftaran = $_POST['id_pendaftaran'] ?? null;
                $tahap = $_POST['tahap'] ?? null;
                $status = $_POST['status'] ?? null;
                $catatan = $_POST['catatan'] ?? null;
            
                // Log received parameters
                error_log("Review parameters: " . json_encode([
                    'id_pendaftaran' => $id_pendaftaran,
                    'tahap' => $tahap,
                    'status' => $status,
                    'catatan' => $catatan
                ]));
            
                // Validate parameters
                if (!$id_pendaftaran) {
                    throw new Exception('Missing id_pendaftaran parameter');
                }
                if (!$tahap) {
                    throw new Exception('Missing tahap parameter');
                }
                if (!$status) {
                    throw new Exception('Missing status parameter');
                }
            
                // Validate tahap value
                if (!in_array($status, ['acc', 'reject'])) {
                    throw new Exception('Invalid status value: ' . $status);
                }
                if (!in_array($tahap, ['1', '2', '3'])) {
                    throw new Exception('Invalid tahap value: ' . $tahap);
                }
            
                // Map the incoming status to the correct database value
                $new_status = STATUS_MAPPING["tahap{$tahap}"][$status] ?? null;
                if (!$new_status) {
                    throw new Exception('Invalid status mapping for tahap ' . $tahap . ' and status ' . $status);
                }
                
                // Verify ownership and get current status
                $check_query = "SELECT p.id_ukm, p.tahap_seleksi, p.nim, p.status
                                FROM pendaftaran_ukm p
                                WHERE p.id_pendaftaran = :id_pendaftaran";
                $stmt = $pdo->prepare($check_query);
                $stmt->execute(['id_pendaftaran' => $id_pendaftaran]);
                $pendaftaran = $stmt->fetch(PDO::FETCH_ASSOC);
            
                error_log("Pendaftaran data: " . json_encode($pendaftaran));
            
                if (!$pendaftaran) {
                    throw new Exception('Pendaftaran tidak ditemukan');
                }
            
                if ($pendaftaran['id_ukm'] != $id_ukm) {
                    throw new Exception('Unauthorized access: UKM mismatch');
                }
    
                // Begin transaction
                $pdo->beginTransaction();
    
                try {
                    // Determine next stage
                    $next_stage = $status === 'reject' ? "tahap{$tahap}" : ($tahap < 3 ? "tahap" . ($tahap + 1) : "tahap3");
    
                    // Update status and tahap_seleksi in pendaftaran_ukm table
                    $catatan_field = "catatan_tahap" . $tahap;
                    $update_query = "UPDATE pendaftaran_ukm 
                                    SET status = :new_status,
                                        tahap_seleksi = :next_stage,
                                        {$catatan_field} = :catatan
                                    WHERE id_pendaftaran = :id_pendaftaran";
    
                    $stmt = $pdo->prepare($update_query);
                    $stmt->execute([
                        'new_status' => $new_status,
                        'next_stage' => $next_stage,
                        'catatan' => $catatan,
                        'id_pendaftaran' => $id_pendaftaran
                    ]);
    
                    // Add to history_pendaftaran
                    $history_query = "INSERT INTO history_pendaftaran 
                                    (id_pendaftaran, nim, id_ukm, status, 
                                     tanggal_pendaftaran, tanggal_update_status,
                                     catatan_tahap1, catatan_tahap2, catatan_tahap3)
                                    VALUES 
                                    (:id_pendaftaran, :nim, :id_ukm, :new_status,
                                     NOW(), NOW(), :catatan_tahap1, :catatan_tahap2, :catatan_tahap3)";
    
                    $catatan_values = [
                        'catatan_tahap1' => $tahap == 1 ? $catatan : null,
                        'catatan_tahap2' => $tahap == 2 ? $catatan : null,
                        'catatan_tahap3' => $tahap == 3 ? $catatan : null
                    ];
    
                    $stmt = $pdo->prepare($history_query);
                    $stmt->execute(array_merge([
                        'id_pendaftaran' => $id_pendaftaran,
                        'nim' => $pendaftaran['nim'],
                        'id_ukm' => $id_ukm,
                        'new_status' => $new_status
                    ], $catatan_values));
    
                    $pdo->commit();
    
                    exit(json_encode([
                        'status' => 'success',
                        'message' => 'Review berhasil disimpan'
                    ]));
    
                } catch (Exception $e) {
                    $pdo->rollBack();
                    throw $e;
                }
                break;
    
            default:
                throw new Exception('Invalid action');
    }

    // Handle DataTables requests for tahap1, tahap2, tahap3
    if (in_array($action, ['get_tahap1', 'get_tahap2', 'get_tahap3'])) {
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id_ukm' => $id_ukm]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        exit(json_encode([
            'draw' => isset($_GET['draw']) ? intval($_GET['draw']) : 1,
            'recordsTotal' => count($data),
            'recordsFiltered' => count($data),
            'data' => $data
        ]));
    }

    throw new Exception('Invalid action');
    
} catch (Exception $e) {
    http_response_code(400);
    error_log($e->getMessage());
    exit(json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]));
}