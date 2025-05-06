<?php
include('../includes/functions.php');
if (!isset($post_id)) return; // має бути встановлено у post.php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isLoggedIn()) {
    $comment = sanitizeInput($_POST['comment']);
    $stmt = $pdo->prepare("INSERT INTO comments (post_id, user_id, content, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$post_id, $_SESSION['user']['id'], $comment]);
}

$stmt = $pdo->prepare("SELECT c.*, u.username FROM comments c JOIN users u ON c.user_id = u.id WHERE post_id = ? ORDER BY c.created_at DESC");
$stmt->execute([$post_id]);

echo "<h3>Коментарі</h3>";
while ($c = $stmt->fetch()):
?>
    <div>
        <p><?= nl2br(htmlspecialchars($c['content'])) ?></p>
        <p><small><?= htmlspecialchars($c['username']) ?> | <?= $c['created_at'] ?></small></p>
    </div>
<?php endwhile; ?>

<?php if (isLoggedIn()): ?>
<form method="post">
    <textarea name="comment" required></textarea><br>
    <button type="submit">Додати коментар</button>
</form>
<?php endif; ?>
