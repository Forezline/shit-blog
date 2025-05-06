<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$basePath = '/blog/';
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Мій Блог</title>
    <link rel="stylesheet" href="<?= $basePath ?>assets/css/style.css">
</head>
<body>
<header>
    <h1>Блог</h1>
    <nav class="navbar">
        <!-- Ліва частина (для кнопки "Головна") -->
        <div class="navbar-left">
            <a href="<?= $basePath ?>index.php">Головна</a>
        </div>

        <!-- Права частина (для кнопок входу/реєстрації або профілю) -->
        <div class="navbar-right">
            <?php if (!empty($_SESSION['user'])): ?>
                <a href="<?= $basePath ?>pages/profile.php">Профіль</a>
                <a href="<?= $basePath ?>pages/create_post.php">Створити пост</a>
                <a href="<?= $basePath ?>auth/logout.php" class="logout-btn">Вийти</a>
            <?php else: ?>
                <a href="<?= $basePath ?>pages/login.php">Увійти</a>
                <a href="<?= $basePath ?>pages/register.php">Реєстрація</a>
            <?php endif; ?>
        </div>
    </nav>
</header>
    <main>
