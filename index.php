<?php
session_start();
include('includes/database/db.php');
global $pdo;
include('includes/function/functions.php');
include('includes/blocks/header.php');

$posts = getFivePosts($pdo);
?>

<h1>Останні пости</h1>
<hr>
<?php if (count($posts) > 0): ?>
    <?php foreach ($posts as $post): ?>
        <article>
            <h2><?= htmlspecialchars($post['title']); ?></h2>
            <p>Автор: <?= htmlspecialchars($post['username']); ?> | Дата: <?= date('d.m.Y H:i', strtotime($post['created_at'])); ?></p>
            <p><a href="pages/post.php?id=<?= $post['id']; ?>">Читати більше...</a></p>
        </article>
    <hr>
    <?php endforeach; ?>
<?php else: ?>
    <p>Пости не знайдено.</p>
<?php endif; ?>

<?php include('includes/blocks/footer.php'); ?>
