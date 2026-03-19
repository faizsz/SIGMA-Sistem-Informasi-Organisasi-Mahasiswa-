<?php
// Koneksi database
require_once __DIR__ . '/../../config/config.php';

// Set header untuk response JSON
header('Content-Type: application/json');

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Debug incoming parameters
        error_log("Received GET parameters: " . print_r($_GET, true));

        // Get data mahasiswa by UKM
        if (isset($_GET['id_ukm'])) {
            $id_ukm = $_GET['id_ukm'];
            
            $query = "SELECT DISTINCT m.nim, m.nama_lengkap 
                    FROM mahasiswa m
                    JOIN keanggotaan_ukm k ON m.nim = k.nim
                    WHERE k.id_ukm = ? 
                    AND k.status = 'pengurus'  -- Ubah menjadi pengurus
                    ORDER BY m.nama_lengkap ASC";
            
            $stmt = $pdo->prepare($query);
            $stmt->execute([$id_ukm]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode($data);
            exit;
        }

        // Get panitia by id_timeline
        if (isset($_GET['id_timeline'])) {
            $id_timeline = $_GET['id_timeline'];
            
            $query = "SELECT p.id_panitia, p.nim, m.nama_lengkap, j.nama_jabatan
                     FROM panitia_proker p
                     JOIN mahasiswa m ON p.nim = m.nim
                     JOIN jabatan_panitia j ON p.id_jabatan_panitia = j.id_jabatan_panitia
                     WHERE p.id_timeline = ?
                     ORDER BY j.level ASC";
            
            $stmt = $pdo->prepare($query);
            $stmt->execute([$id_timeline]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode($data);
            exit;
        }

        // Get jabatan panitia untuk dropdown
        if (isset($_GET['action']) && $_GET['action'] === 'get_jabatan') {
            $query = "SELECT id_jabatan_panitia, nama_jabatan 
                     FROM jabatan_panitia 
                     ORDER BY level ASC";
            
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            exit;
        }

        // If no condition matches
        echo json_encode(['error' => 'Invalid request parameters']);
        exit;

    } catch (Exception $e) {
        error_log("Error in GET request: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

// Rest of the code remains the same...

// Handle POST request (Create panitia)
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nim = $_POST['nim_panitia'];
        $id_timeline = $_POST['id_timeline_panitia'];
        $id_jabatan_panitia = $_POST['jabatan_panitia'];

        // Cek apakah panitia sudah ada
        $query = "SELECT COUNT(*) as count FROM panitia_proker 
                 WHERE nim = ? AND id_timeline = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$nim, $id_timeline]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] > 0) {
            throw new Exception('Mahasiswa sudah menjadi panitia di kegiatan ini');
        }

        // Insert panitia baru
        $query = "INSERT INTO panitia_proker (id_timeline, nim, id_jabatan_panitia) 
                 VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id_timeline, $nim, $id_jabatan_panitia]);

        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

// Handle DELETE request
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    try {
        $id_panitia = $_GET['id_panitia'];
        
        $query = "DELETE FROM panitia_proker WHERE id_panitia = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id_panitia]);

        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>