<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_ukm'])) {
    header('HTTP/1.0 403 Forbidden');
    exit();
}

include '../config/config.php'; // Adjusted path based on your directory structure

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim = $_POST['nim'];
    $id_jabatan = $_POST['id_jabatan'];
    $id_periode = $_POST['id_periode'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_berakhir = $_POST['tanggal_berakhir'];

    // Handle file upload
    $foto = $_FILES['foto']['name'];
    $target_dir = "uploads/"; // Ganti dengan direktori upload yang sesuai
    $target_file = $target_dir . basename($foto);
    move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);

    $query = "INSERT INTO struktur_organisasi_ukm (nim, id_jabatan, id_periode, tanggal_mulai, tanggal_berakhir, foto) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssssss', $nim, $id_jabatan, $id_periode, $tanggal_mulai, $tanggal_berakhir, $foto);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    $stmt->close();
}
