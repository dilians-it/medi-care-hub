<?php
session_start();
require_once '../config/database.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header('Location: ../index.php');
    exit();
}

// Get available doctors for chat
$stmt = $pdo->prepare("
    SELECT DISTINCT d.*, u.id as user_id 
    FROM doctors d
    JOIN users u ON d.user_id = u.id
    WHERE u.status = 'active'
    ORDER BY d.first_name
");
$stmt->execute();
$doctors = $stmt->fetchAll();
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card md:col-span-1">
            <h3 class="text-xl font-bold mb-4">Doctors</h3>
            <div class="space-y-2" id="contacts-list">
                <?php foreach ($doctors as $doctor): ?>
                    <div class="p-3 hover:bg-gray-100 rounded cursor-pointer contact-item" 
                         data-user-id="<?= $doctor['user_id'] ?>">
                        <h4 class="font-bold">Dr. <?= htmlspecialchars($doctor['first_name']) ?></h4>
                        <span class="text-sm text-gray-500"><?= htmlspecialchars($doctor['specialization']) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="card md:col-span-2">
            <div id="chat-container" class="h-[500px] flex flex-col">
                <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4">
                    <!-- Messages will be loaded here -->
                </div>
                <div class="border-t p-4">
                    <form id="messageForm" class="flex gap-2">
                        <input type="hidden" id="receiver_id" name="receiver_id">
                        <input type="text" name="message" class="form-input flex-1" 
                               placeholder="Type your message...">
                        <button type="submit" class="btn-primary">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../assets/js/chat.js"></script>
<?php require_once '../includes/footer.php'; ?>
