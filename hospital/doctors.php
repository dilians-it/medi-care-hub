<?php
session_start();
require_once '../config/database.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'hospital') {
    header('Location: ../index.php');
    exit();
}

$hospitalId = $_SESSION['user_id'];

// Get hospital's doctors
$stmt = $pdo->prepare("
    SELECT d.*, u.email, u.username 
    FROM doctors d
    JOIN users u ON d.user_id = u.id
    WHERE d.hospital_id = (SELECT id FROM hospitals WHERE user_id = ?)
    ORDER BY d.first_name
");
$stmt->execute([$hospitalId]);
$doctors = $stmt->fetchAll();
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Manage Doctors</h2>
        <button class="btn-primary" onclick="openAddDoctorModal()">Add New Doctor</button>
    </div>

    <div class="grid gap-6">
        <?php foreach ($doctors as $doctor): ?>
            <div class="card">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold text-lg">
                            Dr. <?= htmlspecialchars($doctor['first_name'] . ' ' . $doctor['last_name']) ?>
                        </h3>
                        <div class="mt-2 text-sm text-gray-600">
                            <p>Specialization: <?= htmlspecialchars($doctor['specialization']) ?></p>
                            <p>Experience: <?= htmlspecialchars($doctor['experience_years']) ?> years</p>
                            <p>Email: <?= htmlspecialchars($doctor['email']) ?></p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button class="btn-secondary" onclick="editDoctor(<?= $doctor['id'] ?>)">Edit</button>
                        <button class="btn-danger" onclick="deleteDoctor(<?= $doctor['id'] ?>)">Remove</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Add Doctor Modal -->
    <div id="addDoctorModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-bold mb-4">Add New Doctor</h3>
                <form id="addDoctorForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" name="first_name" required class="form-input">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" name="last_name" required class="form-input">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" required class="form-input">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Specialization</label>
                        <input type="text" name="specialization" required class="form-input">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Experience (Years)</label>
                        <input type="number" name="experience_years" required class="form-input">
                    </div>
                    <div class="flex gap-2 justify-end">
                        <button type="button" onclick="closeAddDoctorModal()" class="btn-secondary">Cancel</button>
                        <button type="submit" class="btn-primary">Add Doctor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Edit Doctor Modal -->
    <div id="editDoctorModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-bold mb-4">Edit Doctor</h3>
                <form id="editDoctorForm" class="space-y-4">
                    <input type="hidden" name="doctor_id" id="edit_doctor_id">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" name="first_name" id="edit_first_name" required class="form-input">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" name="last_name" id="edit_last_name" required class="form-input">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="edit_email" required class="form-input">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Specialization</label>
                        <input type="text" name="specialization" id="edit_specialization" required class="form-input">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Experience (Years)</label>
                        <input type="number" name="experience_years" id="edit_experience_years" required class="form-input">
                    </div>
                    <div class="flex gap-2 justify-end">
                        <button type="button" onclick="closeEditDoctorModal()" class="btn-secondary">Cancel</button>
                        <button type="submit" class="btn-primary">Update Doctor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="../assets/js/hospital-doctors.js"></script>
<?php require_once '../includes/footer.php'; ?>
