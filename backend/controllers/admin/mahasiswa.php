<?php
require_once '../../config/config.php';

// Set header JSON
header('Content-Type: application/json');

// Get action from URL parameter
$action = $_GET['action'] ?? '';

switch($action) {
    case 'get_all':
        getAllMahasiswa();
        break;
    case 'get_one':
        getOneMahasiswa();
        break;
    case 'get_prodi':
        getAllProdi();
        break;
    case 'add':
        addMahasiswa();
        break;
    case 'edit':
        editMahasiswa();
        break;
    case 'delete':
        deleteMahasiswa();
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Action not found']);
        break;
}

function getAllMahasiswa() {
    global $pdo;
    
    try {
        $query = "SELECT m.*, ps.nama_program_studi 
                 FROM mahasiswa m 
                 LEFT JOIN program_studi ps ON m.id_program_studi = ps.id_program_studi";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['data' => $data]);
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function getOneMahasiswa() {
    global $pdo;
    
    $nim = $_GET['nim'] ?? '';
    
    try {
        $query = "SELECT * FROM mahasiswa WHERE nim = :nim";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['nim' => $nim]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode($data);
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function getAllProdi() {
    global $pdo;
    
    try {
        $query = "SELECT * FROM program_studi";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Debug: Tampilkan jumlah data dan data
        error_log("Jumlah Program Studi: " . count($data));
        error_log("Data Program Studi: " . print_r($data, true));
        
        echo json_encode($data);
    } catch(PDOException $e) {
        error_log("Error Program Studi: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function addMahasiswa() {
    global $pdo;
    
    try {
        $pdo->beginTransaction();
        
        // Insert ke tabel mahasiswa
        // Insert ke tabel mahasiswa
        $queryMahasiswa = "INSERT INTO mahasiswa 
                          (nim, nama_lengkap, id_program_studi, kelas, jenis_kelamin, alamat, no_whatsapp, email) 
                          VALUES 
                          (:nim, :nama_lengkap, :id_program_studi, :kelas, :jenis_kelamin, :alamat, :no_whatsapp, :email)";
        
        $stmtMahasiswa = $pdo->prepare($queryMahasiswa);
        $stmtMahasiswa->execute([
            'nim' => $_POST['nim'],
            'nama_lengkap' => $_POST['nama_lengkap'],
            'id_program_studi' => $_POST['id_program_studi'],
            'kelas' => $_POST['kelas'],
            'jenis_kelamin' => $_POST['jenis_kelamin'],
            'alamat' => $_POST['alamat'],
            'no_whatsapp' => $_POST['no_whatsapp'],
            'email' => $_POST['email']
        ]);

        // Auto create user login untuk mahasiswa
        $queryUser = "INSERT INTO user_login (username, password, role) VALUES (:username, :password, 'mahasiswa')";
        
        $stmtUser = $pdo->prepare($queryUser);
        $stmtUser->execute([
            'username' => $_POST['nim'], // username adalah NIM
            'password' => '123' // password default
        ]);

        $pdo->commit();
        echo json_encode(['status' => 'success', 'message' => 'Data berhasil ditambahkan']);
        
    } catch(PDOException $e) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function editMahasiswa() {
    global $pdo;
    
    try {
        $query = "UPDATE mahasiswa SET 
                  nama_lengkap = :nama_lengkap,
                  id_program_studi = :id_program_studi,
                  kelas = :kelas,
                  jenis_kelamin = :jenis_kelamin,
                  alamat = :alamat,
                  no_whatsapp = :no_whatsapp,
                  email = :email
                  WHERE nim = :nim";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'nim' => $_POST['nim'],
            'nama_lengkap' => $_POST['nama_lengkap'],
            'id_program_studi' => $_POST['id_program_studi'],
            'kelas' => $_POST['kelas'],
            'jenis_kelamin' => $_POST['jenis_kelamin'],
            'alamat' => $_POST['alamat'],
            'no_whatsapp' => $_POST['no_whatsapp'],
            'email' => $_POST['email']
        ]);
        
        echo json_encode(['status' => 'success', 'message' => 'Data berhasil diupdate']);
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function deleteMahasiswa() {
    global $pdo;
    
    try {
        $pdo->beginTransaction();

        // Delete dari user_login terlebih dahulu karena ada foreign key
        $queryUser = "DELETE FROM user_login WHERE username = :nim";
        $stmtUser = $pdo->prepare($queryUser);
        $stmtUser->execute(['nim' => $_POST['nim']]);

        // Kemudian delete dari mahasiswa
        $queryMahasiswa = "DELETE FROM mahasiswa WHERE nim = :nim";
        $stmtMahasiswa = $pdo->prepare($queryMahasiswa);
        $stmtMahasiswa->execute(['nim' => $_POST['nim']]);

        $pdo->commit();
        echo json_encode(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        
    } catch(PDOException $e) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}