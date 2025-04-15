<?php
session_start();
require_once '../config/database.php';
require_once '../includes/utils.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(401);
    exit('Unauthorized');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get') {
    $hospitalId = $_GET['hospital_id'];
    $stmt = $pdo->prepare("
        SELECT h.*, u.email 
        FROM hospitals h
        JOIN users u ON h.user_id = u.id
        WHERE h.id = ?
    ");
    $stmt->execute([$hospitalId]);
    $hospital = $stmt->fetch(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($hospital);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'edit') {
        try {
            $pdo->beginTransaction();
            
            $hospitalId = $_POST['hospital_id'];
            
            // Get user_id for the hospital
            $stmt = $pdo->prepare("SELECT user_id FROM hospitals WHERE id = ?");
            $stmt->execute([$hospitalId]);
            $userId = $stmt->fetch()['user_id'];
            
            // Update hospital details
            $stmt = $pdo->prepare("
                UPDATE hospitals 
                SET name = ?, address = ?, phone = ?
                WHERE id = ?
            ");
            $stmt->execute([
                $_POST['name'],
                $_POST['address'],
                $_POST['phone'],
                $hospitalId
            ]);
            
            // Update user email
            $stmt = $pdo->prepare("UPDATE users SET email = ? WHERE id = ?");
            $stmt->execute([$_POST['email'], $userId]);
            
            $pdo->commit();
            exit('Success');
            
        } catch (Exception $e) {
            $pdo->rollBack();
            http_response_code(500);
            exit('Error: ' . $e->getMessage());
        }
    }
}
?>
