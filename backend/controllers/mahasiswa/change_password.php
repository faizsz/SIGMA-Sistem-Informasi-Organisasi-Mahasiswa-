<?php
session_start();
require_once '../../config/config.php';
header('Content-Type: application/json');

try {
    if (!isset($_SESSION['username'])) {
        throw new Exception('User not logged in');
    }

    $nim = $_SESSION['username'];
    
    // Get JSON input
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!isset($data['old_password']) || !isset($data['new_password'])) {
        throw new Exception('Invalid request data');
    }

    $old_password = $data['old_password'];
    $new_password = $data['new_password'];

    // Verify old password
    $stmt = $pdo->prepare("SELECT password FROM user_login WHERE username = ?");
    $stmt->execute([$nim]);
    $current_password = $stmt->fetchColumn();

    if ($current_password !== $old_password) {
        throw new Exception('Password lama tidak sesuai');
    }

    // Update password
    $stmt = $pdo->prepare("UPDATE user_login SET password = ? WHERE username = ?");
    $stmt->execute([$new_password, $nim]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Password successfully updated'
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>