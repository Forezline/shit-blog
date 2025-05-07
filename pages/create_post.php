<?php
session_start();
include('../includes/database/db.php');
global $pdo;
include('../includes/function/functions.php');

if (!isLoggedIn()) {
    redirect('login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitizeInput($_POST['title']);
    $content = sanitizeInput($_POST['content']);
    $userId = $_SESSION['user']['id'];

    if ($title && $content) {
        $stmt = $pdo->prepare("INSERT INTO posts (title, content, user_id, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$title, $content, $userId]);
        $message = "Пост успішно створено!";
    } else {
        $message = "Усі поля обов’язкові!";
    }
}
?>

<?php include('../includes/blocks/header.php'); ?>

<h2>Створити новий пост</h2>

<?php if (!empty($message)) echo "<p>$message</p>"; ?>

<form method="post">
    <label>Заголовок:</label><br>
    <label>
        <input type="text" name="title" required>
    </label><br>

    <label>Текст:</label><br>
    <label>
        <textarea name="content" rows="8" required></textarea>
    </label><br><br>

    <button type="submit">Опублікувати</button>
</form>

<?php include('../includes/blocks/footer.php'); ?>
