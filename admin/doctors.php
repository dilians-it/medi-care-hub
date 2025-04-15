<?php
session_start();
require_once '../config/database.php';
require_once '../includes/header.php';

if ($_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

// Get all doctors with their hospital info
$stmt = $pdo->query("
    SELECT d.*, u.email, u.username, u.status, h.name as hospital_name,
    (SELECT COUNT(*) FROM appointments WHERE doctor_id = d.id) as appointment_count
    FROM doctors d
    JOIN users u ON d.user_id = u.id
    JOIN hospitals h ON d.hospital_id = h.id
    ORDER BY d.first_name, d.last_name
");
$doctors = $stmt->fetchAll();
?>

<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6">Manage Doctors</h2>

    <div class="grid gap-6">
        <?php foreach ($doctors as $doctor): ?>
            <div class="card">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold text-lg">
                            Dr. <?= htmlspecialchars($doctor['first_name'] . ' ' . $doctor['last_name']) ?>
                        </h3>
                        <div class="mt-2 text-sm text-gray-600">
                            <p>Hospital: <?= htmlspecialchars($doctor['hospital_name']) ?></p>
                            <p>Specialization: <?= htmlspecialchars($doctor['specialization']) ?></p>
                            <p>Experience: <?= htmlspecialchars($doctor['experience_years']) ?> years</p>
                            <p>Email: <?= htmlspecialchars($doctor['email']) ?></p>
                            <p>Total Appointments: <?= $doctor['appointment_count'] ?></p>
                            <p>Status: <span class="badge <?= $doctor['status'] ?>"><?= ucfirst($doctor['status']) ?></span></p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button class="btn-secondary" onclick="toggleDoctorStatus(<?= $doctor['id'] ?>)">
                            <?= $doctor['status'] === 'active' ? 'Deactivate' : 'Activate' ?>
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="../assets/js/admin-doctors.js"></script>
<?php require_once '../includes/footer.php'; ?>
