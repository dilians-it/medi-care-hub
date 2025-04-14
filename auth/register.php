<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $blood_group = $_POST['blood_group'];

    try {
        $pdo->beginTransaction();

        // Insert user
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, 'patient')");
        $stmt->execute([$username, $password, $email]);
        $userId = $pdo->lastInsertId();

        // Insert patient details
        $stmt = $pdo->prepare("INSERT INTO patients (user_id, date_of_birth, blood_group) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $dob, $blood_group]);

        $pdo->commit();
        header('Location: ../index.php?registered=1');
        exit();
    } catch(Exception $e) {
        $pdo->rollBack();
        header('Location: register.php?error=1');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - MediCare Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg w-96">
            <h1 class="text-2xl font-bold text-center mb-6">Patient Registration</h1>
            <form action="register.php" method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                    <input type="text" name="username" required class="form-input">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" name="email" required class="form-input">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input type="password" name="password" required class="form-input">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Date of Birth</label>
                    <input type="date" name="dob" required class="form-input">
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Blood Group</label>
                    <select name="blood_group" required class="form-input">
                        <option value="">Select Blood Group</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                    </select>
                </div>
                <button class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">Register</button>
            </form>
            <p class="mt-4 text-center">
                <a href="../index.php" class="text-blue-500">Back to Login</a>
            </p>
        </div>
    </div>
</body>
</html>
