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
        SELECT u.id, 
            CASE 
                WHEN u.role = 'doctor' THEN CONCAT('Dr. ', d.first_name, ' ', d.last_name)
                WHEN u.role = 'hospital' THEN h.name
                WHEN u.role = 'admin' THEN 'Help & Support'
            END as name,
            u.role,
            COALESCE(d.specialization, '') as specialization
        FROM users u
        LEFT JOIN doctors d ON d.user_id = u.id
        LEFT JOIN hospitals h ON h.user_id = u.id
        WHERE u.status = 'active' 
        AND u.role IN ('doctor', 'hospital', 'admin')
    ",
    'doctor' => "
        SELECT u.id,
            CASE 
                WHEN u.role = 'patient' THEN CONCAT(p.first_name, ' ', p.last_name)
                WHEN u.role = 'hospital' THEN h.name
                WHEN u.role = 'admin' THEN 'Help & Support'
            END as name,
            u.role,
            '' as specialization
        FROM users u
        LEFT JOIN patients p ON p.user_id = u.id
        LEFT JOIN hospitals h ON h.user_id = u.id
        WHERE u.status = 'active'
        AND (
            (u.role = 'patient' AND p.id IN (
                SELECT patient_id FROM appointments WHERE doctor_id = (
                    SELECT id FROM doctors WHERE user_id = ?
                )
            ))
            OR u.role IN ('hospital', 'admin')
        )
    ",
    'hospital' => "
        SELECT u.id,
            CASE 
                WHEN u.role = 'doctor' THEN CONCAT('Dr. ', d.first_name, ' ', d.last_name)
                WHEN u.role = 'admin' THEN 'Help & Support'
            END as name,
            u.role,
            COALESCE(d.specialization, '') as specialization
        FROM users u
        LEFT JOIN doctors d ON d.user_id = u.id
        WHERE u.status = 'active'
        AND (
            (u.role = 'doctor' AND d.hospital_id = (
                SELECT id FROM hospitals WHERE user_id = ?
            ))
            OR u.role = 'admin'
        )
    ",
    'admin' => "
        SELECT u.id,
            CASE 
                WHEN u.role = 'patient' THEN CONCAT(p.first_name, ' ', p.last_name)
                WHEN u.role = 'doctor' THEN CONCAT('Dr. ', d.first_name, ' ', d.last_name)
                WHEN u.role = 'hospital' THEN h.name
            END as name,
            u.role,
            COALESCE(d.specialization, '') as specialization,
            (SELECT COUNT(*) FROM chat_messages WHERE 
                ((sender_id = u.id AND receiver_id = ?) OR 
                (sender_id = ? AND receiver_id = u.id)) AND 
                created_at > DATE_SUB(NOW(), INTERVAL 30 DAY)) as message_count
        FROM users u
        LEFT JOIN patients p ON p.user_id = u.id
        LEFT JOIN doctors d ON d.user_id = u.id
        LEFT JOIN hospitals h ON h.user_id = u.id
        WHERE u.status = 'active'
        AND u.role IN ('patient', 'doctor', 'hospital')
        ORDER BY message_count DESC
    "
};

$stmt = $pdo->prepare($query);
$stmt->execute($role === 'admin' ? [$userId, $userId] : ($role === 'doctor' || $role === 'hospital' ? [$userId] : []));
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
                                    <div class="w-10 h-10 rounded-full <?= getRoleColor($contact['role']) ?> flex items-center justify-center text-white font-bold">
                                        <?= strtoupper(substr($contact['name'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold"><?= htmlspecialchars($contact['name']) ?></h4>
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
