<?php
session_start();
require_once '../config/database.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'hospital') {
    header('Location: ../index.php');
    exit();
}

$hospitalId = $_SESSION['user_id'];

// Get hospital's feed posts
$stmt = $pdo->prepare("
    SELECT fp.*, h.name as hospital_name 
    FROM feed_posts fp
    JOIN hospitals h ON fp.hospital_id = h.id
    WHERE h.user_id = ?
    ORDER BY fp.created_at DESC
");
$stmt->execute([$hospitalId]);
$posts = $stmt->fetchAll();
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="card mb-6">
            <h3 class="text-xl font-bold mb-4">Create New Post</h3>
            <form id="feedForm" class="space-y-4">
                <div class="form-group">
                    <input type="text" name="title" class="form-input" placeholder="Post Title" required>
                </div>
                <div class="form-group">
                    <textarea name="description" class="form-input" rows="4" 
                        placeholder="Write your post content..." required></textarea>
                </div>
                <div class="form-group">
                    <input type="file" name="image" class="form-input" accept="image/*">
                </div>
                <button type="submit" class="btn-primary">Publish Post</button>
            </form>
        </div>

        <div class="space-y-6" id="feed-posts">
            <?php foreach ($posts as $post): ?>
                <div class="card">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold"><?= htmlspecialchars($post['title']) ?></h3>
                            <p class="text-sm text-gray-500">
                                <?= date('M j, Y', strtotime($post['created_at'])) ?>
                            </p>
                        </div>
                        <div class="flex gap-2">
                            <button class="btn-secondary" onclick="editPost(<?= $post['id'] ?>)">Edit</button>
                            <button class="btn-danger" onclick="deletePost(<?= $post['id'] ?>)">Delete</button>
                        </div>
                    </div>
                    <?php if ($post['image_path']): ?>
                        <img src="<?= htmlspecialchars($post['image_path']) ?>" 
                             alt="Post image" class="w-full rounded-lg mb-4">
                    <?php endif; ?>
                    <p class="text-gray-600"><?= nl2br(htmlspecialchars($post['description'])) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script src="../assets/js/hospital-feed.js"></script>
<?php require_once '../includes/footer.php'; ?>
