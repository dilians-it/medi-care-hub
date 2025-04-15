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
    
    <div class="mb-6 flex gap-4">
        <select id="filterStatus" class="form-input w-48">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="confirmed">Confirmed</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
        </select>
        <input type="date" id="filterDate" class="form-input w-48">
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="px-6 py-3 border-b">Doctor</th>
                    <th class="px-6 py-3 border-b">Patient</th>
                    <th class="px-6 py-3 border-b">Date & Time</th>
                    <th class="px-6 py-3 border-b">Status</th>
                    <th class="px-6 py-3 border-b">Reason</th>
                    <th class="px-6 py-3 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $apt): ?>
                    <tr class="appointment-row" 
                        data-status="<?= $apt['status'] ?>"
                        data-date="<?= date('Y-m-d', strtotime($apt['appointment_date'])) ?>">
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
                        <td class="px-6 py-4 border-b">
                            <?php if ($apt['status'] === 'pending'): ?>
                                <div class="flex gap-2">
                                    <button class="btn-primary btn-sm" onclick="updateStatus(<?= $apt['id'] ?>, 'confirmed')">
                                        Confirm
                                    </button>
                                    <button class="btn-danger btn-sm" onclick="updateStatus(<?= $apt['id'] ?>, 'cancelled')">
                                        Cancel
                                    </button>
                                </div>
                            <?php elseif ($apt['status'] === 'confirmed'): ?>
                                <button class="btn-success btn-sm" onclick="updateStatus(<?= $apt['id'] ?>, 'completed')">
                                    Mark Completed
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.getElementById('filterStatus').addEventListener('change', filterAppointments);
document.getElementById('filterDate').addEventListener('change', filterAppointments);

function filterAppointments() {
    const status = document.getElementById('filterStatus').value;
    const date = document.getElementById('filterDate').value;
    
    document.querySelectorAll('.appointment-row').forEach(row => {
        const statusMatch = !status || row.dataset.status === status;
        const dateMatch = !date || row.dataset.date === date;
        row.style.display = statusMatch && dateMatch ? '' : 'none';
    });
}

function updateStatus(appointmentId, status) {
    fetch('../api/appointments.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=update_status&appointment_id=${appointmentId}&status=${status}`
    })
    .then(response => response.text())
    .then(() => {
        showAlert('Appointment status updated successfully');
        setTimeout(() => window.location.reload(), 2000);
    })
    .catch(error => {
        showAlert('Error updating appointment status', 'error');
    });
}
</script>

<?php require_once '../includes/footer.php'; ?>
