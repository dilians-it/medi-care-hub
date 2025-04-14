<?php
session_start();
require_once '../config/database.php';
require_once '../includes/header.php';

if ($_SESSION['role'] !== 'hospital') {
    header('Location: ../index.php');
    exit();
}

$hospitalId = $_SESSION['user_id'];

// Get hospital statistics
$stmt = $pdo->prepare("SELECT COUNT(*) as doctor_count FROM doctors WHERE hospital_id = ?");
$stmt->execute([$hospitalId]);
$doctorCount = $stmt->fetch()['doctor_count'];

$stmt = $pdo->prepare("
    SELECT COUNT(*) as appointment_count 
    FROM appointments a 
    JOIN doctors d ON a.doctor_id = d.id 
    WHERE d.hospital_id = ?
");
$stmt->execute([$hospitalId]);
$appointmentCount = $stmt->fetch()['appointment_count'];
?>

<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="dashboard-card">
            <h3 class="text-xl font-bold mb-2">Doctors</h3>
            <p class="text-3xl text-blue-600"><?php echo $doctorCount; ?></p>
            <a href="doctors.php" class="btn-primary mt-4 inline-block">Manage Doctors</a>
        </div>
        <div class="dashboard-card">
            <h3 class="text-xl font-bold mb-2">Appointments</h3>
            <p class="text-3xl text-blue-600"><?php echo $appointmentCount; ?></p>
            <a href="appointments.php" class="btn-primary mt-4 inline-block">View Appointments</a>
        </div>
    </div>
    
    <div class="mt-8">
        <div class="dashboard-card">
            <h3 class="text-xl font-bold mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="post-feed.php" class="btn-primary text-center">Post to Feed</a>
                <a href="chat.php" class="btn-secondary text-center">Messages</a>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
