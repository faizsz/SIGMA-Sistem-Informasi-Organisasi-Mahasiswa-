<?php
session_start();
require_once '../../config/config.php';
header('Content-Type: application/json');

try {
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        
        // Query untuk data mahasiswa dan UKM yang diikuti
        $stmt = $pdo->prepare("
            SELECT 
                m.nim, m.nama_lengkap, m.email, m.kelas, m.foto_path, 
                m.jenis_kelamin, m.alamat, m.no_whatsapp, m.id_program_studi,
                u.nama_ukm, u.logo_path, k.status as status_keanggotaan,
                pk.tahun_mulai, pk.tahun_selesai, pk.status as status_periode
            FROM mahasiswa m
            LEFT JOIN keanggotaan_ukm k ON m.nim = k.nim
            LEFT JOIN ukm u ON k.id_ukm = u.id_ukm
            LEFT JOIN periode_kepengurusan pk ON k.id_periode = pk.id_periode
            WHERE m.nim = :username");
        
        $stmt->execute(['username' => $username]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Query tambahan untuk UKM yang sedang didaftar
        $stmtPendaftaran = $pdo->prepare("
            SELECT 
                p.*, u.nama_ukm, u.logo_path
            FROM pendaftaran_ukm p
            JOIN ukm u ON p.id_ukm = u.id_ukm
            WHERE p.nim = :username 
            AND p.status IN ('pending_tahap1', 'acc_tahap1', 'pending_tahap2', 'acc_tahap2', 'pending_tahap3', 'acc_tahap3')
            ORDER BY p.tanggal_pendaftaran DESC");
        
        $stmtPendaftaran->execute(['username' => $username]);
        $pendaftaranResult = $stmtPendaftaran->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($result) > 0) {
            $response = [
                'profile' => [
                    'nim' => $result[0]['nim'],
                    'nama_lengkap' => $result[0]['nama_lengkap'],
                    'email' => $result[0]['email'],
                    'kelas' => $result[0]['kelas'],
                    'foto_path' => $result[0]['foto_path'] ? $result[0]['foto_path'] : 'pp.jpg',
                    'jenis_kelamin' => $result[0]['jenis_kelamin'],
                    'alamat' => $result[0]['alamat'],
                    'no_whatsapp' => $result[0]['no_whatsapp'],
                    'id_program_studi' => $result[0]['id_program_studi']
                ],
                'ukm_aktif' => [],
                'ukm_histori' => [],
                'ukm_pendaftaran' => [] // Tambah array untuk UKM yang sedang didaftar
            ];
            
            // Kategorikan UKM yang diikuti
            foreach ($result as $row) {
                if ($row['nama_ukm']) {
                    $ukm_data = [
                        'nama_ukm' => $row['nama_ukm'],
                        'logo_ukm' => $row['logo_path'],
                        'status' => $row['status_keanggotaan'],
                        'status_periode' => $row['status_periode'],
                        'periode' => $row['tahun_mulai'] . '/' . $row['tahun_selesai']
                    ];
                    
                    if ($row['status_periode'] === 'aktif') {
                        $response['ukm_aktif'][] = $ukm_data;
                    } else {
                        $response['ukm_histori'][] = $ukm_data;
                    }
                }
            }

            // Tambahkan UKM yang sedang didaftar
            foreach ($pendaftaranResult as $pendaftaran) {
                $response['ukm_pendaftaran'][] = [
                    'nama_ukm' => $pendaftaran['nama_ukm'],
                    'logo_ukm' => $pendaftaran['logo_path'],
                    'status' => strtoupper($pendaftaran['status'])
                ];
            }
            
            echo json_encode($response);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
}
?>