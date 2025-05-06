<?php
include('../includes/header.php');
include('../includes/db.php');

$post_id = $_GET['id'] ?? null;
if (!$post_id) redirect('home.php');

$stmt = $pdo->prepare("SELECT p.*, u.username FROM posts p JOIN users u ON p.user_id = u.id WHERE p.id = ?");
$stmt->execute([$post_id]);
$post = $stmt->fetch();

if (!$post) redirect('home.php');
?>

<article>
    <h1><?= htmlspecialchars($post['title']) ?></h1>
    <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
    <p><small>Автор: <?= htmlspecialchars($post['username']) ?> | <?= $post['created_at'] ?></small></p>
</article>

<?php include('comment.php'); ?>
<?php include('../includes/footer.php'); ?>
