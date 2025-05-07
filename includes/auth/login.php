<?php
global $pdo;
session_start();
require_once('../../includes/database/db.php');
require_once('../../includes/function/functions.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Усі поля обов'язкові.";
        redirect('/pages/login.php');
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        unset($user['password']);
        $_SESSION['user'] = $user;
        redirect('/index.php');
    } else {
        $_SESSION['error'] = "Невірна пошта або пароль.";
        redirect('/pages/login.php');
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вхід</title>
</head>
<body>
    <h2>Вхід</h2>
    <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>
    
    <form method="post">
        <label for="email">Електронна пошта</label><br>
        <input type="email" name="email" id="email" required><br>
        
        <label for="password">Пароль</label><br>
        <input type="password" name="password" id="password" required><br>
        
        <button type="submit">Увійти</button>
    </form>
</body>
</html>
