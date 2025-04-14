<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && $password === $user['password']) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        switch($user['role']) {
            case 'admin':
                header('Location: ../admin/dashboard.php');
                break;
            case 'hospital':
                header('Location: ../hospital/dashboard.php');
                break;
            case 'doctor':
                header('Location: ../doctor/dashboard.php');
                break;
            case 'patient':
                header('Location: ../patient/dashboard.php');
                break;
        }
        exit();
    } else {
        header('Location: ../index.php?error=1');
        exit();
    }
}
?>
