<?php
session_start();
require_once 'config/database.php';
require_once 'includes/utils.php';
require_once 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$role = $_SESSION['role'];
$userId = $_SESSION['user_id'];

// Get available contacts based on role
$query = match($role) {
    'patient' => "
        SELECT u.id, d.first_name, d.last_name, d.specialization, 'doctor' as role 
        FROM doctors d
        JOIN users u ON d.user_id = u.id
        WHERE u.status = 'active'
        UNION
        SELECT u.id, 'Help and Support' as first_name, '' as last_name, '' as specialization, 'admin' as role
        FROM users u
        WHERE u.role = 'admin'
        LIMIT 1
    ",
    'doctor' => "
        SELECT u.id, p.first_name, p.last_name, '' as specialization, 'patient' as role
        FROM patients p
        JOIN users u ON p.user_id = u.id
        JOIN appointments a ON p.id = a.patient_id
        JOIN doctors d ON a.doctor_id = d.id
        WHERE d.user_id = ?
        GROUP BY u.id
    ",
    'hospital' => "
        SELECT u.id, d.first_name, d.last_name, d.specialization, 'doctor' as role
        FROM doctors d
        JOIN users u ON d.user_id = u.id
        WHERE d.hospital_id = (SELECT id FROM hospitals WHERE user_id = ?)
        UNION
        SELECT u.id, 'Help and Support' as first_name, '' as last_name, '' as specialization, 'admin' as role
        FROM users u
        WHERE u.role = 'admin'
        LIMIT 1
    ",
    'admin' => "
        SELECT u.id, 
        CASE 
            WHEN u.role = 'hospital' THEN h.name
            WHEN u.role = 'doctor' THEN CONCAT(d.first_name, ' ', d.last_name)
            WHEN u.role = 'patient' THEN CONCAT(p.first_name, ' ', p.last_name)
        END as first_name,
        '' as last_name,
        COALESCE(d.specialization, '') as specialization,
        u.role
        FROM users u
        LEFT JOIN hospitals h ON h.user_id = u.id
        LEFT JOIN doctors d ON d.user_id = u.id
        LEFT JOIN patients p ON p.user_id = u.id
        WHERE u.id != ? AND u.status = 'active'
    "
};

$stmt = $pdo->prepare($query);
$stmt->execute($role === 'admin' || $role === 'doctor' || $role === 'hospital' ? [$userId] : []);
$contacts = $stmt->fetchAll();
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="grid grid-cols-12">
                <!-- Contacts Sidebar -->
                <div class="col-span-4 bg-gray-50 border-r">
                    <div class="p-4 border-b">
                        <h3 class="text-lg font-semibold">Messages</h3>
                    </div>
                    <div class="overflow-y-auto" style="height: calc(80vh - 4rem);">
                        <?php foreach ($contacts as $contact): ?>
                            <div class="contact-item p-4 border-b hover:bg-gray-100 cursor-pointer transition-colors" 
                                 data-user-id="<?= $contact['id'] ?>">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                                        <?= strtoupper(substr($contact['first_name'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold">
                                            <?= htmlspecialchars($contact['first_name']) ?>
                                        </h4>
                                        <span class="text-sm text-gray-500">
                                            <?= ucfirst($contact['role']) ?>
                                            <?= $contact['specialization'] ? ' - ' . htmlspecialchars($contact['specialization']) : '' ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Chat Area -->
                <div class="col-span-8 flex flex-col">
                    <div class="p-4 border-b" id="chat-header">
                        <h3 class="text-lg font-semibold">Select a contact to start chatting</h3>
                    </div>
                    <div class="flex-1 overflow-y-auto p-4" id="chat-messages" style="height: calc(80vh - 12rem);">
                        <!-- Messages will be loaded here -->
                    </div>
                    <div class="p-4 border-t">
                        <form id="messageForm" class="flex gap-2">
                            <input type="hidden" id="receiver_id" name="receiver_id">
                            <input type="text" name="message" class="flex-1 rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:border-blue-500" 
                                   placeholder="Type your message..." required>
                            <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                                Send
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/chat.js"></script>
<?php require_once 'includes/footer.php'; ?>
