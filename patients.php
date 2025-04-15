<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$role = $_SESSION['role'];

// Only doctors and hospitals should access patients page
if ($role !== 'doctor' && $role !== 'hospital') {
    header('Location: dashboard.php');
    exit();
}

header("Location: $role/patients.php");
exit();
