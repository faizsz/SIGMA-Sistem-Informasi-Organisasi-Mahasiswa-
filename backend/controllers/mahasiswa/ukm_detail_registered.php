<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../../config/config.php';

class UkmDetailAPI {
    private $pdo;
    private $response;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->response = [
            'status' => 'error',
            'message' => '',
            'data' => null
        ];
    }

    public function handleRequest() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }

            $action = $_POST['action'] ?? '';
            if (empty($action)) {
                throw new Exception('Action is required');
            }

            $ukmId = filter_input(INPUT_POST, 'ukm_id', FILTER_VALIDATE_INT);
            if (!$ukmId) {
                throw new Exception('Invalid UKM ID');
            }

            switch ($action) {
                case 'getTimeline':
                    $this->handleGetTimeline($ukmId);
                    break;
                    
                case 'getBanner':
                    $this->handleGetBanner($ukmId);
                    break;
                    
                default:
                    throw new Exception('Invalid action');
            }
        } catch (Exception $e) {
            $this->response['message'] = $e->getMessage();
        }

        echo json_encode($this->response);
        exit;
    }

    private function handleGetTimeline($ukmId) {
        try {
            $jenis = $_POST['jenis'] ?? '';
            if (!in_array($jenis, ['proker', 'agenda'])) {
                throw new Exception('Invalid jenis parameter');
            }
    
            $query = "SELECT 
                        t.id_timeline,
                        t.judul_kegiatan,
                        t.deskripsi,
                        DATE_FORMAT(t.tanggal_kegiatan, '%Y-%m-%d') as tanggal_kegiatan,
                        TIME_FORMAT(t.waktu_mulai, '%H:%i') as waktu_mulai,
                        TIME_FORMAT(t.waktu_selesai, '%H:%i') as waktu_selesai,
                        t.image_path,
                        t.jenis,
                        t.status,
                        (
                            SELECT JSON_ARRAYAGG(
                                JSON_OBJECT(
                                    'nama', m2.nama_lengkap,
                                    'jabatan', jp2.nama_jabatan,
                                    'level', jp2.level
                                )
                            )
                            FROM panitia_proker pp2
                            LEFT JOIN mahasiswa m2 ON pp2.nim = m2.nim
                            LEFT JOIN jabatan_panitia jp2 ON pp2.id_jabatan_panitia = jp2.id_jabatan_panitia
                            WHERE pp2.id_timeline = t.id_timeline
                        ) as panitia,
                        CASE 
                            WHEN t.jenis = 'agenda' THEN (
                                SELECT JSON_ARRAYAGG(
                                    JSON_OBJECT(
                                        'foto_path', dr.foto_path
                                    )
                                )
                                FROM rapat r
                                JOIN dokumentasi_rapat dr ON dr.id_rapat = r.id_rapat
                                WHERE r.id_timeline = t.id_timeline
                            )
                            ELSE (
                                SELECT JSON_ARRAYAGG(
                                    JSON_OBJECT(
                                        'id_rapat', r.id_rapat,
                                        'judul', r.judul,
                                        'tanggal', DATE_FORMAT(r.tanggal, '%Y-%m-%d'),
                                        'notulensi_path', r.notulensi_path,
                                        'dokumentasi', (
                                            SELECT JSON_ARRAYAGG(
                                                JSON_OBJECT(
                                                    'foto_path', dr.foto_path
                                                )
                                            )
                                            FROM dokumentasi_rapat dr
                                            WHERE dr.id_rapat = r.id_rapat
                                        )
                                    )
                                )
                                FROM rapat r
                                WHERE r.id_timeline = t.id_timeline
                            )
                        END as rapat
                    FROM timeline_ukm t
                    WHERE t.id_ukm = :ukm_id 
                    AND t.jenis = :jenis
                    ORDER BY t.tanggal_kegiatan DESC";
    
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':ukm_id' => $ukmId,
                ':jenis' => $jenis
            ]);
            
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            $processedResults = array_map(function($item) use ($jenis) {
                $item['panitia'] = json_decode($item['panitia'] ?? '[]', true) ?: [];
                
                if ($jenis === 'agenda') {
                    $item['dokumentasi'] = json_decode($item['rapat'] ?? '[]', true) ?: [];
                    unset($item['rapat']);
                } else {
                    $item['rapat'] = json_decode($item['rapat'] ?? '[]', true) ?: [];
                    
                    if (!empty($item['rapat'])) {
                        foreach ($item['rapat'] as &$rapat) {
                            $rapat['dokumentasi'] = $rapat['dokumentasi'] ?? [];
                        }
                    }
                }
                
                return $item;
            }, $result);
    
            $this->response = [
                'status' => 'success',
                'data' => $processedResults
            ];
    
        } catch (Exception $e) {
            error_log('Error in handleGetTimeline: ' . $e->getMessage());
            throw new Exception('Database error: ' . $e->getMessage());
        }
    }

    private function handleGetBanner($ukmId) {
        try {
            $query = "SELECT banner_path FROM ukm WHERE id_ukm = :ukm_id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(':ukm_id', $ukmId, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result && !empty($result['banner_path'])) {
                $this->response = [
                    'status' => 'success',
                    'message' => 'Banner retrieved successfully',
                    'data' => [
                        'banner_path' => $result['banner_path']
                    ]
                ];
            } else {
                $this->response = [
                    'status' => 'success',
                    'message' => 'No banner found',
                    'data' => [
                        'banner_path' => 'default-banner.jpg'
                    ]
                ];
            }
        } catch (PDOException $e) {
            error_log('Error in handleGetBanner: ' . $e->getMessage());
            throw new Exception('Database error: ' . $e->getMessage());
        }
    }
}

// Initialize and handle the request
try {
    $api = new UkmDetailAPI($pdo);
    $api->handleRequest();
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Server error: ' . $e->getMessage(),
        'data' => null
    ]);
}