<?php
session_start();
require_once '../config/database.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header('Location: ../index.php');
    exit();
}

$patientId = $_SESSION['user_id'];

// Get patient's appointments
$stmt = $pdo->prepare("
    SELECT a.*, d.first_name as doctor_name, d.specialization
    FROM appointments a 
    JOIN patients p ON a.patient_id = p.id 
    JOIN doctors d ON a.doctor_id = d.id 
    WHERE p.user_id = ?
    ORDER BY a.appointment_date DESC
");
$stmt->execute([$patientId]);
$appointments = $stmt->fetchAll();

// Get available doctors grouped by hospital
$stmt = $pdo->query("
    SELECT d.id, d.first_name, d.last_name, d.specialization, h.name as hospital_name 
    FROM doctors d
    JOIN hospitals h ON d.hospital_id = h.id
    JOIN users u ON d.user_id = u.id
    WHERE u.status = 'active'
    ORDER BY h.name, d.first_name
");
$doctors = $stmt->fetchAll();

// Group doctors by hospital
$doctorsByHospital = [];
foreach($doctors as $doctor) {
    $doctorsByHospital[$doctor['hospital_name']][] = $doctor;
}
?>

<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="card">
            <h3 class="text-xl font-bold mb-4">Book New Appointment</h3>
            <form id="appointmentForm" class="space-y-4">
                <div class="form-group">
                    <label class="form-label">Select Doctor</label>
                    <select name="doctor_id" required class="form-input">
                        <option value="">Select a Doctor</option>
                        <?php foreach ($doctorsByHospital as $hospital => $hospitalDoctors): ?>
                            <optgroup label="<?= htmlspecialchars($hospital) ?>">
                                <?php foreach ($hospitalDoctors as $doctor): ?>
                                    <option value="<?= $doctor['id'] ?>">
                                        Dr. <?= htmlspecialchars($doctor['first_name'] . ' ' . $doctor['last_name']) ?> 
                                        (<?= htmlspecialchars($doctor['specialization']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Appointment Date</label>
                    <input type="datetime-local" name="appointment_date" required class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Reason</label>
                    <textarea name="reason" required class="form-input"></textarea>
                </div>
                <button type="submit" class="btn-primary w-full">Book Appointment</button>
            </form>
        </div>

        <div class="card">
            <h3 class="text-xl font-bold mb-4">My Appointments</h3>
            <div class="space-y-4">
                <?php foreach ($appointments as $apt): ?>
                    <div class="p-4 border rounded">
                        <div class="flex justify-between">
                            <h4 class="font-bold">Dr. <?= htmlspecialchars($apt['doctor_name']) ?></h4>
                            <span class="badge <?= $apt['status'] ?>"><?= ucfirst($apt['status']) ?></span>
                        </div>
                        <p class="text-gray-600"><?= htmlspecialchars($apt['reason']) ?></p>
                        <p class="text-sm text-gray-500"><?= date('M j, Y H:i', strtotime($apt['appointment_date'])) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script src="../assets/js/appointments.js"></script>
<?php require_once '../includes/footer.php'; ?>
