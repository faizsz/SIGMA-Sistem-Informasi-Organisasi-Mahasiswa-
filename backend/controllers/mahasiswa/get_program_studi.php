<?php
session_start();
require_once '../../config/config.php';
header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT id_program_studi, nama_program_studi FROM program_studi ORDER BY nama_program_studi");
    $programStudi = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'status' => 'success',
        'data' => $programStudi
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to fetch program studi: ' . $e->getMessage()
    ]);
}
?>