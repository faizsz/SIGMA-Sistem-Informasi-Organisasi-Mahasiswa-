<?php
session_start();
require '../../config/config.php'; // Menghubungkan file koneksi database

$id_ukm = $_SESSION['id_ukm'] ?? null; 
if (!$id_ukm) {
    http_response_code(403);
    echo json_encode(['error' => 'Akses ditolak. ID UKM tidak ditemukan.']);
    exit;
}

// Mendapatkan data dari permintaan POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST; // Ambil data yang dikirim dari form

    // Mendapatkan file logo
    $logo_path = null;
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == UPLOAD_ERR_OK) {
        $logo_path = 'path/to/save/' . basename($_FILES['logo']['name']); // Tentukan path yang benar
        move_uploaded_file($_FILES['logo']['tmp_name'], $logo_path); // Pindahkan file ke path yang diinginkan
    }

    // Mendapatkan file banner
    $banner_path = null;
    if (isset($_FILES['banner']) && $_FILES['banner']['error'] == UPLOAD_ERR_OK) {
        $banner_path = 'path/to/save/' . basename($_FILES['banner']['name']); // Tentukan path yang benar
        move_uploaded_file($_FILES['banner']['tmp_name'], $banner_path); // Pindahkan file ke path yang diinginkan
    }

    // Menyiapkan query untuk memperbarui data UKM
    $query = "
        UPDATE ukm SET 
            nama_ukm = :nama_ukm,
            deskripsi = :deskripsi,
            visi = :visi,
            misi = :misi,
            tanggal_berdiri = :tanggal_berdiri,
            logo_path = COALESCE(:logo_path, logo_path), -- Pertahankan yang lama jika tidak ada
            banner_path = COALESCE(:banner_path, banner_path) -- Pertahankan yang lama jika tidak ada
        WHERE id_ukm = :id_ukm
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array_merge($data, [
        ':logo_path' => $logo_path,
        ':banner_path' => $banner_path,
        ':id_ukm' => $id_ukm
    ]));

    echo json_encode(['success' => $stmt->rowCount() > 0 ? 'Data UKM berhasil diperbarui.' : 'Tidak ada perubahan pada data.']);
    exit;
}

// Query untuk mengambil data UKM
$query = "SELECT nama_ukm, deskripsi, visi, misi, tanggal_berdiri, logo_path, banner_path FROM ukm WHERE id_ukm = :id_ukm";
$stmt = $pdo->prepare($query);
$stmt->execute([':id_ukm' => $id_ukm]);
$ukm = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($ukm ? array_merge($ukm, [
    'misi' => explode("\n", $ukm['misi']),
    'logo' => '/frontend/public/assets/' . $ukm['logo_path'],
    'cover' => '/frontend/public/assets/' . $ukm['banner_path'],
]) : ['error' => 'Data UKM tidak ditemukan.']);
?>
