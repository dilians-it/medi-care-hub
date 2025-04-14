<?php
function uploadFile($file, $targetDir, $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf']) {
    $targetFile = $targetDir . basename($file["name"]);
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check file type
    if (!in_array($file['type'], $allowedTypes)) {
        throw new Exception('Invalid file type');
    }

    // Generate unique filename
    $filename = uniqid() . '.' . $fileType;
    $targetFile = $targetDir . $filename;

    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        return $filename;
    }
    
    throw new Exception('Failed to upload file');
}

function createNotification($pdo, $userId, $title, $message, $type) {
    $stmt = $pdo->prepare("INSERT INTO notifications (user_id, title, message, type) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$userId, $title, $message, $type]);
}

function formatDateTime($datetime) {
    return date('M j, Y g:i A', strtotime($datetime));
}

function sanitizeInput($input) {
    return htmlspecialchars(trim($input));
}
