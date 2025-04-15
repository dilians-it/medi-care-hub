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

function getPageTitle($page) {
    $titles = [
        'dashboard' => 'Dashboard',
        'hospitals' => 'Manage Hospitals',
        'doctors' => 'Manage Doctors',
        'patients' => 'Patients',
        'appointments' => 'Appointments',
        'reports' => 'Reports',
        'feed' => 'Hospital Feed',
        'chat' => 'Messages',
        'chatbot' => 'AI Assistant'
    ];
    return $titles[$page] ?? 'Page';
}

function getStatusBadge($status) {
    $classes = [
        'active' => 'badge-success',
        'pending' => 'badge-warning',
        'cancelled' => 'badge-danger'
    ];
    return '<span class="badge ' . ($classes[$status] ?? '') . '">' . ucfirst($status) . '</span>';
}

function getUserById($pdo, $userId) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetch();
}

function isAjaxRequest() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

function getRoleColor($role) {
    return match($role) {
        'patient' => 'bg-blue-500',
        'doctor' => 'bg-green-500',
        'hospital' => 'bg-yellow-500',
        'admin' => 'bg-red-500',
        default => 'bg-gray-500'
    };
}
