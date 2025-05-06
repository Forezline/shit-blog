<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('../includes/header.php');
include('../includes/db.php');
?>

<h1>Останні пости</h1>

<?php
$stmt = $pdo->query("SELECT posts.id, posts.title, posts.content, posts.created_at, users.username FROM posts JOIN users ON posts.user_id = users.id ORDER BY posts.created_at DESC");

while ($row = $stmt->fetch()):
?>
    <article>
        <h2><a href="post.php?id=<?= $row['id'] ?>"><?= htmlspecialchars($row['title']) ?></a></h2>
        <p><?= nl2br(htmlspecialchars(substr($row['content'], 0, 150))) ?>...</p>
        <p><small>Автор: <?= htmlspecialchars($row['username']) ?> | Дата: <?= $row['created_at'] ?></small></p>
    </article>
<?php endwhile; ?>

<?php include('../includes/footer.php'); ?>
