<?php
session_start();
require_once '../config/database.php';
require_once '../includes/utils.php';
require_once '../includes/header.php';

if ($_SESSION['role'] !== '[role]') {
    header('Location: ../index.php');
    exit();
}

// Page-specific PHP logic here

?>

<div class="container mx-auto px-4 py-8">
    <!-- Page-specific HTML here -->
</div>

<?php require_once '../includes/footer.php'; ?>
