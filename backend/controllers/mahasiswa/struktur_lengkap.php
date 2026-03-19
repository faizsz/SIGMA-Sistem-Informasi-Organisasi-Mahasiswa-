<?php
header('Content-Type: application/json');
require_once '../../config/config.php';

$id_ukm = isset($_GET['id_ukm']) ? $_GET['id_ukm'] : null;

try {
    if (!$id_ukm) {
        echo json_encode([
            "status" => "error",
            "message" => "ID UKM tidak ditemukan"
        ]);
        exit;
    }

    // Query untuk mengambil data detail UKM
    $queryUkm = "SELECT id_ukm, nama_ukm, banner_path FROM ukm WHERE id_ukm = :id_ukm";
    $stmtUkm = $pdo->prepare($queryUkm);
    $stmtUkm->bindParam(':id_ukm', $id_ukm, PDO::PARAM_INT);
    $stmtUkm->execute();
    $ukm = $stmtUkm->fetch(PDO::FETCH_ASSOC);

    if (!$ukm) {
        echo json_encode([
            "status" => "error",
            "message" => "Data UKM tidak ditemukan"
        ]);
        exit;
    }

    // Query untuk mengambil semua struktur organisasi
    $queryStruktur = "
        SELECT 
            m.nama_lengkap,
            jd.nama_jabatan,
            jd.hierarki as jabatan_hierarki,
            s.foto_path,
            p.tahun_mulai,
            p.tahun_selesai,
            d.nama_divisi,
            d.hierarki as divisi_hierarki,
            d.deskripsi as divisi_deskripsi,
            d.id_divisi,
            s.id_ukm
        FROM struktur_organisasi_ukm s
        JOIN mahasiswa m ON s.nim = m.nim
        JOIN jabatan_divisi jd ON s.id_jabatan_divisi = jd.id_jabatan_divisi
        JOIN divisi_ukm d ON s.id_divisi = d.id_divisi AND d.id_ukm = s.id_ukm
        JOIN periode_kepengurusan p ON s.id_periode = p.id_periode
        WHERE s.id_ukm = :id_ukm 
        AND p.status = 'aktif'
        ORDER BY d.hierarki ASC, jd.hierarki ASC";
        
    $stmtStruktur = $pdo->prepare($queryStruktur);
    $stmtStruktur->bindParam(':id_ukm', $id_ukm, PDO::PARAM_INT);
    $stmtStruktur->execute();
    $struktur = $stmtStruktur->fetchAll(PDO::FETCH_ASSOC);

    // Debug: Print raw data
    error_log("Query result for UKM ID $id_ukm: " . print_r($struktur, true));

    echo json_encode([
        "status" => "success",
        "ukm_detail" => $ukm,
        "struktur_organisasi" => $struktur
    ]);

} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    echo json_encode([
        "status" => "error",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>