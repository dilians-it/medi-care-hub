<?php
session_start();
require_once '../config/database.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    header('Location: ../index.php');
    exit();
}

$doctorId = $_SESSION['user_id'];

// Get doctor's appointments
$stmt = $pdo->prepare("
    SELECT a.*, p.first_name as patient_name, p.last_name 
    FROM appointments a 
    JOIN doctors d ON a.doctor_id = d.id 
    JOIN patients p ON a.patient_id = p.id 
    WHERE d.user_id = ? 
    ORDER BY a.appointment_date DESC
");
$stmt->execute([$doctorId]);
$appointments = $stmt->fetchAll();
?>

<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6">Appointments</h2>
    <div class="grid gap-6">
        <?php foreach ($appointments as $apt): ?>
            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="font-bold"><?= htmlspecialchars($apt['patient_name'] . ' ' . $apt['last_name']) ?></h3>
                        <p class="text-gray-600"><?= htmlspecialchars($apt['reason']) ?></p>
                        <p class="text-sm text-gray-500"><?= date('M j, Y H:i', strtotime($apt['appointment_date'])) ?></p>
                    </div>
                    <div class="flex gap-2">
                        <span class="badge <?= $apt['status'] ?>"><?= ucfirst($apt['status']) ?></span>
                        <?php if ($apt['status'] === 'pending'): ?>
                            <button class="btn-primary approve-btn" data-id="<?= $apt['id'] ?>">Approve</button>
                            <button class="btn-secondary reject-btn" data-id="<?= $apt['id'] ?>">Reject</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="../assets/js/doctor-appointments.js"></script>
<?php require_once '../includes/footer.php'; ?>
