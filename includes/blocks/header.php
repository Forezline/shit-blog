<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Мій Блог</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<header>
    <h1>Блог</h1>
    <nav class="navbar">
        <div class="navbar-left">
            <a href="/index.php">Головна</a>
        </div>

        <div class="navbar-right">
            <?php if (!empty($_SESSION['user'])): ?>
                <a href="/pages/profile.php">Профіль</a>
                <a href="/pages/create_post.php">Створити пост</a>
                <a href="/includes/auth/logout.php" class="logout-btn">Вийти</a>
            <?php else: ?>
                <a href="/pages/login.php">Увійти</a>
                <a href="/pages/register.php">Реєстрація</a>
            <?php endif; ?>
        </div>
    </nav>
</header>
    <main>
