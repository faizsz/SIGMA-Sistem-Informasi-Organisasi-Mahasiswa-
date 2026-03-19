<?php
session_start(); // Memulai session
require_once '../../config/config.php'; // Menyertakan konfigurasi database

header('Content-Type: application/json');

// Mendapatkan data JSON dari request POST
$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($data['username']) && isset($data['password'])) {
    $username = $data['username'];
    $password = $data['password'];

    try {
        // Menyiapkan query untuk mendapatkan user berdasarkan username
        $stmt = $pdo->prepare("SELECT id_login, username, password, role, id_ukm FROM user_login WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Memeriksa apakah user ditemukan dan password cocok
        if ($user) {
            if ($password === $user['password']) { // Perlu hashing di produksi
                // Menyimpan data login di session
                $_SESSION['id_login'] = $user['id_login'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['id_ukm'] = $user['role'] === 'admin_ukm' ? $user['id_ukm'] : null;

                // Mengembalikan respons berhasil dalam format JSON
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Login berhasil',
                    'user' => [
                        'id_login' => $user['id_login'],
                        'username' => $user['username'],
                        'role' => $user['role'],
                        'id_ukm' => $user['role'] === 'admin_ukm' ? $user['id_ukm'] : null
                    ]
                ]);
            } else {
                // Respons jika password salah
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Username atau password salah'
                ]);
            }
        } else {
            // Respons jika username tidak ditemukan
            echo json_encode([
                'status' => 'error',
                'message' => 'Username atau password salah'
            ]);
        }
    } catch (PDOException $e) {
        // Menangani kesalahan server dan menampilkan log
        error_log($e->getMessage());
        echo json_encode([
            'status' => 'error',
            'message' => 'Terjadi kesalahan pada server'
        ]);
    }
} else {
    // Respons jika request tidak valid
    echo json_encode([
        'status' => 'error',
        'message' => 'Request tidak valid'
    ]);
}
?>
