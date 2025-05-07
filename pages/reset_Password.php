<?php
global $pdo;
session_start();
require_once('../includes/database/db.php');
require_once('../includes/function/functions.php');

$token = $_GET['token'] ?? '';

if (empty($token)) {
    $_SESSION['error'] = "Невірний токен.";
    redirect('../pages/login.php');
}

// Перевірка токена
$stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ? AND created_at > NOW() - INTERVAL 1 HOUR");
$stmt->execute([$token]);
$reset_request = $stmt->fetch();

if (!$reset_request) {
    $_SESSION['error'] = "Токен недійсний або протермінований.";
    redirect('/pages/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = "Паролі не співпадають.";
        redirect("/pages/reset_password.php?token=" . $token);
    }

    // Хешування нового паролю
    $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);

    // Оновлення пароля користувача
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->execute([$new_password_hashed, $reset_request['email']]);

    // Видалення токена після використання
    $stmt = $pdo->prepare("DELETE FROM password_resets WHERE token = ?");
    $stmt->execute([$token]);

    $_SESSION['success'] = "Пароль успішно оновлено.";
    redirect('/pages/login.php');
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Скидання паролю</title>
</head>
<body>
    <h2>Скидання пароля</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>

    <form method="post">
        <label for="new_password">Новий пароль:</label>
        <input type="password" id="new_password" name="new_password" required placeholder="Введіть новий пароль"><br><br>

        <label for="confirm_password">Підтвердження пароля:</label>
        <input type="password" id="confirm_password" name="confirm_password" required placeholder="Підтвердіть новий пароль"><br><br>

        <button type="submit">Скинути пароль</button>
    </form>

</body>
</html>
