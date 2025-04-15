<?php
session_start();
require_once '../config/database.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'hospital') {
    header('Location: ../index.php');
    exit();
}

$hospitalId = $_SESSION['user_id'];

// Get all appointments for doctors in this hospital
$stmt = $pdo->prepare("
    SELECT a.*, 
           d.first_name as doctor_name, d.specialization,
           p.first_name as patient_name, p.last_name as patient_last_name
    FROM appointments a 
    JOIN doctors d ON a.doctor_id = d.id 
    JOIN patients p ON a.patient_id = p.id 
    WHERE d.hospital_id = ?
    ORDER BY a.appointment_date DESC
");
$stmt->execute([$hospitalId]);
$appointments = $stmt->fetchAll();
?>

<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6">Hospital Appointments</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="px-6 py-3 border-b">Doctor</th>
                    <th class="px-6 py-3 border-b">Patient</th>
                    <th class="px-6 py-3 border-b">Date</th>
                    <th class="px-6 py-3 border-b">Status</th>
                    <th class="px-6 py-3 border-b">Reason</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $apt): ?>
                    <tr>
                        <td class="px-6 py-4 border-b">
                            Dr. <?= htmlspecialchars($apt['doctor_name']) ?><br>
                            <span class="text-sm text-gray-500"><?= htmlspecialchars($apt['specialization']) ?></span>
                        </td>
                        <td class="px-6 py-4 border-b">
                            <?= htmlspecialchars($apt['patient_name'] . ' ' . $apt['patient_last_name']) ?>
                        </td>
                        <td class="px-6 py-4 border-b">
                            <?= date('M j, Y H:i', strtotime($apt['appointment_date'])) ?>
                        </td>
                        <td class="px-6 py-4 border-b">
                            <span class="badge <?= $apt['status'] ?>"><?= ucfirst($apt['status']) ?></span>
                        </td>
                        <td class="px-6 py-4 border-b">
                            <?= htmlspecialchars($apt['reason']) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
