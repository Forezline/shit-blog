<?php
session_start();
include('includes/db.php');
include('includes/functions.php');
include('includes/header.php');

// Отримання останніх 5 постів для головної сторінки
$stmt = $pdo->prepare("SELECT posts.id, posts.title, posts.created_at, users.username FROM posts 
                       JOIN users ON posts.user_id = users.id 
                       ORDER BY posts.created_at DESC LIMIT 5");
$stmt->execute();
$posts = $stmt->fetchAll();
?>

<h1>Останні пости</h1>

<?php if (count($posts) > 0): ?>
    <?php foreach ($posts as $post): ?>
        <article>
            <h2><a href="pages/post.php?id=<?= $post['id']; ?>"><?= htmlspecialchars($post['title']); ?></a></h2>
            <p>Автор: <?= htmlspecialchars($post['username']); ?> | Дата: <?= date('d.m.Y H:i', strtotime($post['created_at'])); ?></p>
            <p><a href="pages/post.php?id=<?= $post['id']; ?>">Читати більше...</a></p>
        </article>
    <?php endforeach; ?>
<?php else: ?>
    <p>Пости не знайдено.</p>
<?php endif; ?>

<?php include('includes/footer.php'); ?>
