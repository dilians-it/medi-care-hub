<?php
session_start();
require_once '../config/database.php';
require_once '../includes/utils.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'hospital') {
    http_response_code(401);
    exit('Unauthorized');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get') {
    $doctorId = $_GET['doctor_id'];
    $stmt = $pdo->prepare("
        SELECT d.*, u.email 
        FROM doctors d
        JOIN users u ON d.user_id = u.id
        WHERE d.id = ?
    ");
    $stmt->execute([$doctorId]);
    $doctor = $stmt->fetch(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($doctor);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add') {
        try {
            $pdo->beginTransaction();
            
            // Generate username and password
            $username = strtolower(substr($_POST['first_name'], 0, 1) . $_POST['last_name']);
            $password = bin2hex(random_bytes(4)); // 8 characters
            
            // Insert user
            $stmt = $pdo->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, 'doctor')");
            $stmt->execute([$username, $password, $_POST['email']]);
            $userId = $pdo->lastInsertId();
            
            // Get hospital ID
            $stmt = $pdo->prepare("SELECT id FROM hospitals WHERE user_id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $hospitalId = $stmt->fetch()['id'];
            
            // Insert doctor
            $stmt = $pdo->prepare("INSERT INTO doctors (user_id, hospital_id, first_name, last_name, specialization, experience_years) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $userId,
                $hospitalId,
                $_POST['first_name'],
                $_POST['last_name'],
                $_POST['specialization'],
                $_POST['experience_years']
            ]);
            
            // Send notification to new doctor (you'll need to implement email sending here)
            createNotification(
                $pdo, 
                $userId, 
                'Account Created', 
                "Your doctor account has been created. Username: $username, Password: $password", 
                'account'
            );
            
            $pdo->commit();
            exit('Success');
            
        } catch (Exception $e) {
            $pdo->rollBack();
            http_response_code(500);
            exit('Error: ' . $e->getMessage());
        }
    }
    
    if ($action === 'edit') {
        try {
            $pdo->beginTransaction();
            
            $doctorId = $_POST['doctor_id'];
            
            // Update doctor details
            $stmt = $pdo->prepare("
                UPDATE doctors 
                SET first_name = ?, last_name = ?, specialization = ?, experience_years = ?
                WHERE id = ?
            ");
            $stmt->execute([
                $_POST['first_name'],
                $_POST['last_name'],
                $_POST['specialization'],
                $_POST['experience_years'],
                $doctorId
            ]);
            
            // Get user_id for the doctor
            $stmt = $pdo->prepare("SELECT user_id FROM doctors WHERE id = ?");
            $stmt->execute([$doctorId]);
            $userId = $stmt->fetch()['user_id'];
            
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
    
    // ... existing action handlers ...
}
