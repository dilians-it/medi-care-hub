<?php
session_start();
require_once '../config/database.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header('Location: ../index.php');
    exit();
}

// Get feed posts
$stmt = $pdo->prepare("
    SELECT fp.*, h.name as hospital_name 
    FROM feed_posts fp
    JOIN hospitals h ON fp.hospital_id = h.id
    WHERE fp.status = 'published'
    ORDER BY fp.created_at DESC
");
$stmt->execute();
$posts = $stmt->fetchAll();
?>

<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6">Hospital Updates</h2>
    <div class="max-w-3xl mx-auto space-y-6">
        <?php foreach ($posts as $post): ?>
            <div class="card">
                <div class="mb-4">
                    <h3 class="text-xl font-bold"><?= htmlspecialchars($post['title']) ?></h3>
                    <p class="text-sm text-gray-500">
                        <?= htmlspecialchars($post['hospital_name']) ?> • 
                        <?= date('M j, Y', strtotime($post['created_at'])) ?>
                    </p>
                </div>
                <?php if ($post['image_path']): ?>
                    <img src="<?= htmlspecialchars($post['image_path']) ?>" 
                         alt="Post image" 
                         class="w-full rounded-lg mb-4">
                <?php endif; ?>
                <p class="text-gray-600"><?= nl2br(htmlspecialchars($post['description'])) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
