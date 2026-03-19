<?php
require_once '../../config/config.php';

// Set header JSON
header('Content-Type: application/json');

// Tangani request
$action = $_GET['action'] ?? '';

if ($action === 'get_data') {
    // Ambil semua data periode
    try {
        $stmt = $pdo->query("SELECT * FROM periode_kepengurusan ORDER BY id_periode DESC");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['status' => 'success', 'data' => $data]);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} elseif ($action === 'add') {
    // Tambah periode baru
    $tahun_mulai = $_POST['tahun_mulai'] ?? null;
    $tahun_selesai = $_POST['tahun_selesai'] ?? null;

    if ($tahun_mulai && $tahun_selesai) {
        try {
            $stmt = $pdo->prepare("INSERT INTO periode_kepengurusan (tahun_mulai, tahun_selesai, status) VALUES (?, ?, 'Tidak Aktif')");
            $stmt->execute([$tahun_mulai, $tahun_selesai]);
            echo json_encode(['status' => 'success', 'message' => 'Periode berhasil ditambahkan']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
    }
} elseif ($action === 'update_status') {
    // Ubah status aktif
    $id_periode = $_POST['id_periode'] ?? null;

    if ($id_periode) {
        try {
            $pdo->beginTransaction();

            // Set semua status ke 'Tidak Aktif'
            $pdo->exec("UPDATE periode_kepengurusan SET status = 'Tidak Aktif'");

            // Set status aktif untuk ID yang dipilih
            $stmt = $pdo->prepare("UPDATE periode_kepengurusan SET status = 'Aktif' WHERE id_periode = ?");
            $stmt->execute([$id_periode]);

            $pdo->commit();
            echo json_encode(['status' => 'success', 'message' => 'Status periode berhasil diubah']);
        } catch (Exception $e) {
            $pdo->rollBack();
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID periode tidak ditemukan']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Aksi tidak valid']);
}
?>
