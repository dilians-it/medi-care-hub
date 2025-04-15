<?php
session_start();
require_once '../config/database.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    header('Location: ../index.php');
    exit();
}

$doctorId = $_SESSION['user_id'];

// Get doctor's reports
$stmt = $pdo->prepare("
    SELECT vr.*, a.appointment_date, 
           p.first_name as patient_name, p.last_name
    FROM visit_reports vr
    JOIN appointments a ON vr.appointment_id = a.id
    JOIN doctors d ON a.doctor_id = d.id
    JOIN patients p ON a.patient_id = p.id
    WHERE d.user_id = ?
    ORDER BY vr.created_at DESC
");
$stmt->execute([$doctorId]);
$reports = $stmt->fetchAll();
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Medical Reports</h2>
        <a href="create_report.php" class="btn-primary">Create New Report</a>
    </div>

    <div class="grid gap-6">
        <?php foreach ($reports as $report): ?>
            <div class="card">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="font-bold">
                            <?= htmlspecialchars($report['patient_name'] . ' ' . $report['last_name']) ?>
                        </h3>
                        <p class="text-sm text-gray-500">
                            Visit Date: <?= date('M j, Y', strtotime($report['appointment_date'])) ?>
                        </p>
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
