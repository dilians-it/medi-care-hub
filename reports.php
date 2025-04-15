<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$role = $_SESSION['role'];
if ($role === 'hospital') {
    header('Location: dashboard.php');
} else {
    header("Location: $role/reports.php");
}
exit();
