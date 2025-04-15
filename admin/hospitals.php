<?php
session_start();
require_once '../config/database.php';
require_once '../includes/header.php';

if ($_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

// Get all hospitals
$stmt = $pdo->query("
    SELECT h.*, u.email, u.username, u.status,
    (SELECT COUNT(*) FROM doctors WHERE hospital_id = h.id) as doctor_count
    FROM hospitals h
    JOIN users u ON h.user_id = u.id
    ORDER BY h.name
");
$hospitals = $stmt->fetchAll();
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Manage Hospitals</h2>
        <button class="btn-primary" onclick="openAddHospitalModal()">Add New Hospital</button>
    </div>

    <div class="grid gap-6">
        <?php foreach ($hospitals as $hospital): ?>
            <div class="card">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold text-lg"><?= htmlspecialchars($hospital['name']) ?></h3>
                        <div class="mt-2 text-sm text-gray-600">
                            <p>Email: <?= htmlspecialchars($hospital['email']) ?></p>
                            <p>Address: <?= htmlspecialchars($hospital['address']) ?></p>
                            <p>Phone: <?= htmlspecialchars($hospital['phone']) ?></p>
                            <p>Doctors: <?= $hospital['doctor_count'] ?></p>
                            <p>Status: <span class="badge <?= $hospital['status'] ?>"><?= ucfirst($hospital['status']) ?></span></p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button class="btn-secondary" onclick="editHospital(<?= $hospital['id'] ?>)">Edit</button>
                        <button class="btn-danger" onclick="deleteHospital(<?= $hospital['id'] ?>)">Delete</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="../assets/js/admin-hospitals.js"></script>
<?php require_once '../includes/footer.php'; ?>
