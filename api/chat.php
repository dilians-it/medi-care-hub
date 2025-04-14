<?php
session_start();
require_once '../config/database.php';
require_once '../includes/utils.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit('Unauthorized');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $receiverId = $_POST['receiver_id'];
    $message = $_POST['message'];
    
    // Handle file upload if present
    $attachmentPath = null;
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === 0) {
        try {
            $attachmentPath = uploadFile(
                $_FILES['attachment'], 
                '../uploads/chat/',
                ['image/jpeg', 'image/png', 'application/pdf', 'application/msword']
            );
        } catch (Exception $e) {
            http_response_code(400);
            exit($e->getMessage());
        }
    }

    // Insert message
    $stmt = $pdo->prepare("
        INSERT INTO chat_messages (sender_id, receiver_id, message, attachment_path) 
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$_SESSION['user_id'], $receiverId, $message, $attachmentPath]);

    // Create notification for receiver
    createNotification(
        $pdo,
        $receiverId,
        'New Message',
        'You have received a new message',
        'chat'
    );

    exit('Message sent');
}

// Get chat messages
$otherId = $_GET['user_id'];
$stmt = $pdo->prepare("
    SELECT cm.*, u.username, u.profile_picture 
    FROM chat_messages cm
    JOIN users u ON cm.sender_id = u.id
    WHERE (sender_id = ? AND receiver_id = ?)
    OR (sender_id = ? AND receiver_id = ?)
    ORDER BY created_at DESC
    LIMIT 50
");
$stmt->execute([$_SESSION['user_id'], $otherId, $otherId, $_SESSION['user_id']]);
$messages = $stmt->fetchAll();

header('Content-Type: application/json');
echo json_encode($messages);
