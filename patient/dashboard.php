<?php
session_start();
require_once '../config/database.php';
require_once '../includes/header.php';

if ($_SESSION['role'] !== 'patient') {
    header('Location: ../index.php');
    exit();
}

$patientId = $_SESSION['user_id'];

// Get upcoming appointments
$stmt = $pdo->prepare("SELECT COUNT(*) as upcoming FROM appointments a 
    JOIN patients p ON a.patient_id = p.id 
    WHERE p.user_id = ? AND appointment_date > NOW()");
$stmt->execute([$patientId]);
$upcomingAppointments = $stmt->fetch()['upcoming'];

// Get latest reports
$stmt = $pdo->prepare("SELECT COUNT(*) as reports FROM visit_reports vr 
    JOIN appointments a ON vr.appointment_id = a.id 
    JOIN patients p ON a.patient_id = p.id 
    WHERE p.user_id = ?");
$stmt->execute([$patientId]);
$reportCount = $stmt->fetch()['reports'];
?>

<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="dashboard-card">
            <h3 class="text-xl font-bold mb-2">Upcoming Appointments</h3>
            <p class="text-3xl text-blue-600"><?php echo $upcomingAppointments; ?></p>
            <a href="appointments.php" class="btn-primary mt-4 inline-block">Book Appointment</a>
        </div>
        <div class="dashboard-card">
            <h3 class="text-xl font-bold mb-2">Medical Reports</h3>
            <p class="text-3xl text-blue-600"><?php echo $reportCount; ?></p>
            <a href="reports.php" class="btn-primary mt-4 inline-block">View Reports</a>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="dashboard-card">
            <h3 class="text-xl font-bold mb-4">Hospital Feed</h3>
            <div id="hospital-feed" class="space-y-4">
                <!-- Feed items will be loaded dynamically -->
            </div>
            <a href="feed.php" class="btn-secondary mt-4 inline-block">View All Updates</a>
        </div>
        <div class="dashboard-card">
            <h3 class="text-xl font-bold mb-4">Quick Help</h3>
            <div class="space-y-4">
                <a href="chat.php" class="btn-primary block text-center">Chat with Doctor</a>
                <a href="chatbot.php" class="btn-secondary block text-center">Ask AI Assistant</a>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
