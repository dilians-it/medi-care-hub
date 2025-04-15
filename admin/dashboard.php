<?php
session_start();
require_once '../config/database.php';
require_once '../includes/header.php';

if ($_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

// Get statistics
$stmt = $pdo->query("SELECT COUNT(*) as hospital_count FROM hospitals");
$hospitalCount = $stmt->fetch()['hospital_count'];

$stmt = $pdo->query("SELECT COUNT(*) as doctor_count FROM doctors");
$doctorCount = $stmt->fetch()['doctor_count'];

$stmt = $pdo->query("SELECT COUNT(*) as patient_count FROM patients");
$patientCount = $stmt->fetch()['patient_count'];
?>

<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Statistics Cards -->
        <div class="dashboard-card">
            <h3 class="text-xl font-bold mb-2">Hospitals</h3>
            <p class="text-3xl text-blue-600"><?php echo $hospitalCount; ?></p>
            <a href="hospitals.php" class="btn-primary mt-4 inline-block">Manage Hospitals</a>
        </div>
        <div class="dashboard-card">
            <h3 class="text-xl font-bold mb-2">Doctors</h3>
            <p class="text-3xl text-blue-600"><?php echo $doctorCount; ?></p>
            <a href="doctors.php" class="btn-primary mt-4 inline-block">Manage Doctors</a>
        </div>
        <div class="dashboard-card">
            <h3 class="text-xl font-bold mb-2">Patients</h3>
            <p class="text-3xl text-blue-600"><?php echo $patientCount; ?></p>
            <a href="patients.php" class="btn-primary mt-4 inline-block">View Patients</a>
        </div>
    </div>
    
    <div class="mt-8">
        <div class="dashboard-card">
            <h3 class="text-xl font-bold mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="../chat.php" class="btn-primary text-center">Messages & Support</a>
                <a href="reports.php" class="btn-secondary text-center">View Reports</a>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
