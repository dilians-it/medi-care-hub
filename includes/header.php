<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCare Hub - <?php echo ucfirst($role); ?> Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">MediCare Hub</h1>
            <div class="flex items-center space-x-4">
                <span><?php echo ucfirst($role); ?></span>
                <a href="../auth/logout.php" class="hover:text-gray-200">Logout</a>
            </div>
        </div>
    </nav>
