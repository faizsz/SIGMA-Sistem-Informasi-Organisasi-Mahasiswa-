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

try {
    // Query untuk mengambil data mahasiswa untuk form tanpa fakultas
    $query = "SELECT 
                m.*,
                ps.nama_program_studi
              FROM mahasiswa m
              JOIN program_studi ps ON m.id_program_studi = ps.id_program_studi
              WHERE m.nim = :nim";
              
    $stmt = $pdo->prepare($query);
    $stmt->execute(['nim' => $_SESSION['username']]);
    $mahasiswa = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($mahasiswa) {
        echo json_encode([
            'status' => 'success',
            'data' => [
                'nim' => $mahasiswa['nim'],
                'nama_lengkap' => $mahasiswa['nama_lengkap'],
                'program_studi' => $mahasiswa['nama_program_studi'],
                'kelas' => $mahasiswa['kelas'],
                'jenis_kelamin' => $mahasiswa['jenis_kelamin'],
                'alamat' => $mahasiswa['alamat'],
                'no_whatsapp' => $mahasiswa['no_whatsapp'],
                'email' => $mahasiswa['email']
            ]
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Data mahasiswa tidak ditemukan'
        ]);
    }

} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>