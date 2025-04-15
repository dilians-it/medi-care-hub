<?php
session_start();
require_once '../config/database.php';
require_once '../includes/header.php';

if ($_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

// Get system statistics
$stats = [
    'hospitals' => $pdo->query("SELECT COUNT(*) FROM hospitals")->fetchColumn(),
    'doctors' => $pdo->query("SELECT COUNT(*) FROM doctors")->fetchColumn(),
    'patients' => $pdo->query("SELECT COUNT(*) FROM patients")->fetchColumn(),
    'appointments' => $pdo->query("SELECT COUNT(*) FROM appointments")->fetchColumn(),
    'completed_appointments' => $pdo->query("SELECT COUNT(*) FROM appointments WHERE status = 'completed'")->fetchColumn(),
    'pending_appointments' => $pdo->query("SELECT COUNT(*) FROM appointments WHERE status = 'pending'")->fetchColumn()
];

// Get monthly appointments data
$stmt = $pdo->query("
    SELECT DATE_FORMAT(appointment_date, '%Y-%m') as month,
           COUNT(*) as count
    FROM appointments
    GROUP BY month
    ORDER BY month DESC
    LIMIT 12
");
$monthlyData = $stmt->fetchAll();
?>

<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6">System Reports</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="card text-center">
            <h3 class="text-xl font-bold mb-2">Total Hospitals</h3>
            <p class="text-3xl text-blue-600"><?= $stats['hospitals'] ?></p>
        </div>
        <div class="card text-center">
            <h3 class="text-xl font-bold mb-2">Total Doctors</h3>
            <p class="text-3xl text-blue-600"><?= $stats['doctors'] ?></p>
        </div>
        <div class="card text-center">
            <h3 class="text-xl font-bold mb-2">Total Patients</h3>
            <p class="text-3xl text-blue-600"><?= $stats['patients'] ?></p>
        </div>
    </div>

    <div class="card mb-8">
        <h3 class="text-xl font-bold mb-4">Appointments Overview</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="text-center">
                <p class="text-gray-600">Total</p>
                <p class="text-2xl font-bold"><?= $stats['appointments'] ?></p>
            </div>
            <div class="text-center">
                <p class="text-gray-600">Completed</p>
                <p class="text-2xl font-bold text-green-600"><?= $stats['completed_appointments'] ?></p>
            </div>
            <div class="text-center">
                <p class="text-gray-600">Pending</p>
                <p class="text-2xl font-bold text-yellow-600"><?= $stats['pending_appointments'] ?></p>
            </div>
        </div>
    </div>

    <div class="card">
        <h3 class="text-xl font-bold mb-4">Monthly Appointments</h3>
        <div class="h-64" id="appointmentsChart"></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const monthlyData = <?= json_encode($monthlyData) ?>;
const ctx = document.getElementById('appointmentsChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: monthlyData.map(d => d.month),
        datasets: [{
            label: 'Appointments',
            data: monthlyData.map(d => d.count),
            borderColor: 'rgb(59, 130, 246)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
    }
});
</script>

<?php require_once '../includes/footer.php'; ?>
