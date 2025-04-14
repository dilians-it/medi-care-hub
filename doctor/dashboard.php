<?php
session_start();
require_once '../config/database.php';
require_once '../includes/header.php';

if ($_SESSION['role'] !== 'doctor') {
    header('Location: ../index.php');
    exit();
}

$doctorId = $_SESSION['user_id'];

// Get doctor's appointments
$stmt = $pdo->prepare("SELECT COUNT(*) as today_appointments FROM appointments a 
    JOIN doctors d ON a.doctor_id = d.id 
    WHERE d.user_id = ? AND DATE(appointment_date) = CURDATE()");
$stmt->execute([$doctorId]);
$todayAppointments = $stmt->fetch()['today_appointments'];

// Get total patients
$stmt = $pdo->prepare("SELECT COUNT(DISTINCT patient_id) as patient_count FROM appointments a 
    JOIN doctors d ON a.doctor_id = d.id 
    WHERE d.user_id = ?");
$stmt->execute([$doctorId]);
$patientCount = $stmt->fetch()['patient_count'];
?>

<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="dashboard-card">
            <h3 class="text-xl font-bold mb-2">Today's Appointments</h3>
            <p class="text-3xl text-blue-600"><?php echo $todayAppointments; ?></p>
            <a href="appointments.php" class="btn-primary mt-4 inline-block">View Schedule</a>
        </div>
        <div class="dashboard-card">
            <h3 class="text-xl font-bold mb-2">Total Patients</h3>
            <p class="text-3xl text-blue-600"><?php echo $patientCount; ?></p>
            <a href="patients.php" class="btn-primary mt-4 inline-block">View Patients</a>
        </div>
    </div>

    <div class="mt-8">
        <div class="dashboard-card">
            <h3 class="text-xl font-bold mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="reports.php" class="btn-primary text-center">Create Report</a>
                <a href="chat.php" class="btn-secondary text-center">Messages</a>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
