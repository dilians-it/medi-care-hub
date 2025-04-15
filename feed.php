<?php
session_start();
require_once 'config/database.php';
require_once 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Get feed posts
$stmt = $pdo->query("
    SELECT p.*, u.username, u.role 
    FROM posts p 
    JOIN users u ON p.user_id = u.id 
    ORDER BY p.created_at DESC 
    LIMIT 20
");
$posts = $stmt->fetchAll();
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <?php if ($_SESSION['role'] === 'hospital' || $_SESSION['role'] === 'doctor'): ?>
        <div class="card mb-6">
            <h3 class="text-xl font-bold mb-4">Create Post</h3>
            <form id="postForm" class="space-y-4">
                <textarea name="content" class="form-input" rows="4" placeholder="Write your post..."></textarea>
                <button type="submit" class="btn-primary">Post</button>
            </form>
        </div>
        <?php endif; ?>

        <div class="space-y-6" id="feed-posts">
            <?php foreach ($posts as $post): ?>
            <div class="card">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h4 class="font-bold"><?= htmlspecialchars($post['username']) ?></h4>
                        <span class="text-sm text-gray-500"><?= ucfirst($post['role']) ?></span>
                    </div>
                    <span class="text-sm text-gray-500">
                        <?= date('M j, Y H:i', strtotime($post['created_at'])) ?>
                    </span>
                </div>
                <p class="text-gray-700"><?= htmlspecialchars($post['content']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script src="assets/js/feed.js"></script>
<?php require_once 'includes/footer.php'; ?>
