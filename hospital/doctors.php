<?php
session_start();
require_once '../config/database.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'hospital') {
    header('Location: ../index.php');
    exit();
}

$hospitalId = $_SESSION['user_id'];

// Get hospital's doctors
$stmt = $pdo->prepare("
    SELECT d.*, u.email, u.username 
    FROM doctors d
    JOIN users u ON d.user_id = u.id
    WHERE d.hospital_id = (SELECT id FROM hospitals WHERE user_id = ?)
    ORDER BY d.first_name
");
$stmt->execute([$hospitalId]);
$doctors = $stmt->fetchAll();
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Manage Doctors</h2>
        <button class="btn-primary" onclick="openAddDoctorModal()">Add New Doctor</button>
    </div>

    <div class="grid gap-6">
        <?php foreach ($doctors as $doctor): ?>
            <div class="card">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold text-lg">
                            Dr. <?= htmlspecialchars($doctor['first_name'] . ' ' . $doctor['last_name']) ?>
                        </h3>
                        <div class="mt-2 text-sm text-gray-600">
                            <p>Specialization: <?= htmlspecialchars($doctor['specialization']) ?></p>
                            <p>Experience: <?= htmlspecialchars($doctor['experience_years']) ?> years</p>
                            <p>Email: <?= htmlspecialchars($doctor['email']) ?></p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button class="btn-secondary" onclick="editDoctor(<?= $doctor['id'] ?>)">Edit</button>
                        <button class="btn-danger" onclick="deleteDoctor(<?= $doctor['id'] ?>)">Remove</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="../assets/js/hospital-doctors.js"></script>
<?php require_once '../includes/footer.php'; ?>
