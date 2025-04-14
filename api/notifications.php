<?php
session_start();
require_once '../config/database.php';
require_once '../includes/utils.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit('Unauthorized');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'mark_read') {
        $stmt = $pdo->prepare("UPDATE notifications SET is_read = TRUE WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        exit();
    }
}

// Get notifications
$stmt = $pdo->prepare("
    SELECT * FROM notifications 
    WHERE user_id = ? 
    ORDER BY created_at DESC 
    LIMIT 10
");
$stmt->execute([$_SESSION['user_id']]);
$notifications = $stmt->fetchAll();

// Get unread count
$stmt = $pdo->prepare("SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = FALSE");
$stmt->execute([$_SESSION['user_id']]);
$unreadCount = $stmt->fetch()['count'];

$html = '';
foreach ($notifications as $notification) {
    $html .= '<div class="p-2 border-b hover:bg-gray-50">';
    $html .= '<h4 class="font-bold">' . htmlspecialchars($notification['title']) . '</h4>';
    $html .= '<p class="text-sm">' . htmlspecialchars($notification['message']) . '</p>';
    $html .= '<span class="text-xs text-gray-500">' . formatDateTime($notification['created_at']) . '</span>';
    $html .= '</div>';
}

header('Content-Type: application/json');
echo json_encode(['html' => $html, 'unread' => $unreadCount]);
