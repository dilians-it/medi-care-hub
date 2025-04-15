<?php
session_start();
require_once '../config/database.php';
require_once '../includes/header.php';

if ($_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

// Get all patients with their appointment counts
$stmt = $pdo->query("
    SELECT p.*, u.email, u.username, u.status,
    (SELECT COUNT(*) FROM appointments WHERE patient_id = p.id) as appointment_count
    FROM patients p
    JOIN users u ON p.user_id = u.id
    ORDER BY p.first_name, p.last_name
");
$patients = $stmt->fetchAll();
?>

<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6">Registered Patients</h2>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="px-6 py-3 border-b">Name</th>
                    <th class="px-6 py-3 border-b">Email</th>
                    <th class="px-6 py-3 border-b">Blood Group</th>
                    <th class="px-6 py-3 border-b">Date of Birth</th>
                    <th class="px-6 py-3 border-b">Appointments</th>
                    <th class="px-6 py-3 border-b">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($patients as $patient): ?>
                    <tr>
                        <td class="px-6 py-4 border-b">
                            <?= htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']) ?>
                        </td>
                        <td class="px-6 py-4 border-b"><?= htmlspecialchars($patient['email']) ?></td>
                        <td class="px-6 py-4 border-b"><?= htmlspecialchars($patient['blood_group']) ?></td>
                        <td class="px-6 py-4 border-b">
                            <?= date('M j, Y', strtotime($patient['date_of_birth'])) ?>
                        </td>
                        <td class="px-6 py-4 border-b"><?= $patient['appointment_count'] ?></td>
                        <td class="px-6 py-4 border-b">
                            <span class="badge <?= $patient['status'] ?>"><?= ucfirst($patient['status']) ?></span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
