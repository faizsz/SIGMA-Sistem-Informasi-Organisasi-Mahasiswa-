<?php
require_once '../../config/config.php';

// Validasi ID UKM dari request
$id_ukm = isset($_GET['id_ukm']) ? $_GET['id_ukm'] : null;
if (!$id_ukm) {
    echo json_encode(["status" => "error", "message" => "ID UKM tidak ditemukan"]);
    exit;
}

try {
    // Query untuk mengambil data detail UKM
    $queryUkm = "SELECT id_ukm, nama_ukm, banner_path, deskripsi, visi, misi FROM ukm WHERE id_ukm = :id_ukm";
    $stmtUkm = $pdo->prepare($queryUkm);
    $stmtUkm->bindParam(':id_ukm', $id_ukm, PDO::PARAM_INT);
    $stmtUkm->execute();
    $ukm = $stmtUkm->fetch(PDO::FETCH_ASSOC);

    if (!$ukm) {
        echo json_encode(["status" => "error", "message" => "Data UKM tidak ditemukan"]);
        exit;
    }

    // Query untuk mengambil data struktur organisasi
    // Dimodifikasi untuk hanya mengambil divisi dengan hierarki 1
    $queryStruktur = "
        SELECT 
            m.nama_lengkap,
            jd.nama_jabatan,
            jd.hierarki as jabatan_hierarki,
            s.foto_path,
            p.tahun_mulai,
            p.tahun_selesai,
            d.nama_divisi,
            d.hierarki as divisi_hierarki
        FROM struktur_organisasi_ukm s
        JOIN mahasiswa m ON s.nim = m.nim
        JOIN jabatan_divisi jd ON s.id_jabatan_divisi = jd.id_jabatan_divisi
        JOIN divisi_ukm d ON s.id_divisi = d.id_divisi
        JOIN periode_kepengurusan p ON s.id_periode = p.id_periode
        WHERE s.id_ukm = :id_ukm 
        AND p.status = 'aktif'
        AND d.hierarki = 1
        ORDER BY jd.hierarki ASC";
        
    $stmtStruktur = $pdo->prepare($queryStruktur);
    $stmtStruktur->bindParam(':id_ukm', $id_ukm, PDO::PARAM_INT);
    $stmtStruktur->execute();
    $struktur = $stmtStruktur->fetchAll(PDO::FETCH_ASSOC);

    // Query untuk mengambil timeline kegiatan
    $queryTimeline = "
    SELECT 
        id_timeline,
        judul_kegiatan,
        deskripsi,
        tanggal_kegiatan,
        waktu_mulai,
        waktu_selesai,
        image_path,
        jenis
    FROM timeline_ukm 
    WHERE id_ukm = :id_ukm 
    AND status IN ('active', 'inactive')
    ORDER BY tanggal_kegiatan DESC";
    
    $stmtTimeline = $pdo->prepare($queryTimeline);
    $stmtTimeline->bindParam(':id_ukm', $id_ukm, PDO::PARAM_INT);
    $stmtTimeline->execute();
    $timeline = $stmtTimeline->fetchAll(PDO::FETCH_ASSOC);

    // Format tanggal dan waktu untuk timeline
    foreach ($timeline as &$event) {
        $event['tanggal_kegiatan'] = date('d F Y', strtotime($event['tanggal_kegiatan']));
        $event['waktu_mulai'] = date('H:i', strtotime($event['waktu_mulai']));
        $event['waktu_selesai'] = date('H:i', strtotime($event['waktu_selesai']));
    }

    // Gabungkan semua data
    $response = [
        "status" => "success",
        "ukm_detail" => $ukm,
        "struktur_organisasi" => $struktur,
        "timeline" => $timeline
    ];

    echo json_encode($response);

} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>