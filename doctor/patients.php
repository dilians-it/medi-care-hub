<?php
session_start();
require_once '../config/database.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    header('Location: ../index.php');
    exit();
}

$doctorId = $_SESSION['user_id'];

// Get doctor's patients with their last appointment
$stmt = $pdo->prepare("
    SELECT DISTINCT 
        p.*, 
        MAX(a.appointment_date) as last_visit,
        (SELECT COUNT(*) FROM appointments a2 
         WHERE a2.patient_id = p.id 
         AND a2.doctor_id = d.id) as visit_count
    FROM patients p
    JOIN appointments a ON p.id = a.patient_id
    JOIN doctors d ON a.doctor_id = d.id
    WHERE d.user_id = ?
    GROUP BY p.id
    ORDER BY last_visit DESC
");
$stmt->execute([$doctorId]);
$patients = $stmt->fetchAll();
?>

<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6">My Patients</h2>
    <div class="grid gap-6">
        <?php foreach ($patients as $patient): ?>
            <div class="card">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold text-lg">
                            <?= htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']) ?>
                        </h3>
                        <div class="mt-2 text-sm text-gray-600">
                            <p>Gender: <?= ucfirst(htmlspecialchars($patient['gender'])) ?></p>
                            <p>Blood Group: <?= htmlspecialchars($patient['blood_group']) ?></p>
                            <?php if ($patient['allergies']): ?>
                                <p>Allergies: <?= htmlspecialchars($patient['allergies']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="text-right text-sm text-gray-500">
                        <p>Last Visit: <?= date('M j, Y', strtotime($patient['last_visit'])) ?></p>
                        <p>Total Visits: <?= $patient['visit_count'] ?></p>
                    </div>
                </div>
                <div class="mt-4 flex gap-2">
                    <a href="reports.php?patient_id=<?= $patient['id'] ?>" 
                       class="btn-primary">View Reports</a>
                    <a href="appointments.php?patient_id=<?= $patient['id'] ?>" 
                       class="btn-secondary">View Appointments</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
