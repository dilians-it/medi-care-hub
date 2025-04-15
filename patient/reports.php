<?php
session_start();
require_once '../config/database.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header('Location: ../index.php');
    exit();
}

$patientId = $_SESSION['user_id'];

// Get patient's reports
$stmt = $pdo->prepare("
    SELECT vr.*, a.appointment_date, d.first_name as doctor_name, d.specialization
    FROM visit_reports vr
    JOIN appointments a ON vr.appointment_id = a.id
    JOIN doctors d ON a.doctor_id = d.id
    JOIN patients p ON a.patient_id = p.id
    WHERE p.user_id = ?
    ORDER BY vr.created_at DESC
");
$stmt->execute([$patientId]);
$reports = $stmt->fetchAll();
?>

<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6">Medical Reports</h2>
    <div class="grid gap-6">
        <?php foreach ($reports as $report): ?>
            <div class="card">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="font-bold">Dr. <?= htmlspecialchars($report['doctor_name']) ?></h3>
                        <p class="text-sm text-gray-600"><?= htmlspecialchars($report['specialization']) ?></p>
                        <p class="text-sm text-gray-500">Visit Date: <?= date('M j, Y', strtotime($report['appointment_date'])) ?></p>
                    </div>
                </div>
                <div class="space-y-4">
                    <?php if ($report['diagnosis']): ?>
                        <div>
                            <h4 class="font-bold">Diagnosis</h4>
                            <p class="text-gray-600"><?= nl2br(htmlspecialchars($report['diagnosis'])) ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if ($report['prescription']): ?>
                        <div>
                            <h4 class="font-bold">Prescription</h4>
                            <p class="text-gray-600"><?= nl2br(htmlspecialchars($report['prescription'])) ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if ($report['treatment_plan']): ?>
                        <div>
                            <h4 class="font-bold">Treatment Plan</h4>
                            <p class="text-gray-600"><?= nl2br(htmlspecialchars($report['treatment_plan'])) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
