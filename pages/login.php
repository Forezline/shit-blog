<?php
global $pdo;

use Random\RandomException;

session_start();
require_once('../includes/database/db.php');
require_once('../includes/function/functions.php');
require_once('../includes/function/mail.php');

include('../includes/blocks/header.php');

// Обробка скидання паролю
if (isset($_POST['reset_password'])) {
    $email = sanitizeInput($_POST['email']);

    if (empty($email)) {
        $_SESSION['error'] = "Email не може бути порожнім.";
        redirect('../pages/login.php');
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        try {
            $token = bin2hex(random_bytes(32));
        } catch (RandomException $e) {
            echo "Помилка створення токену!";
        }
        $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$email, $token]);

        $reset_link = "http://shit-blog/pages/reset_password.php?token=" . $token;
        sendResetEmail($email, $reset_link);

        $_SESSION['success'] = "Лінк для скидання паролю надіслано на вашу електронну пошту.";
    } else {
        $_SESSION['error'] = "Користувача з таким email не знайдено.";
    }
    redirect('/pages/login.php');
}

// Обробка входу
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['reset_password'])) {
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
    
    <h2>Вхід</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <p style="color: green;"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
    <?php endif; ?>

    <!-- Форма входу -->
    <form method="post" style="margin-bottom: 20px;">
        <label for="login-email">Email:</label>
        <input type="email" id="login-email" name="email" required placeholder="Введіть ваш email">

        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" required placeholder="Введіть ваш пароль">

        <button type="submit">Увійти</button>
    </form>

    <!-- Кнопка для скидання паролю -->
    <p><a href="#" id="show-reset">Забули пароль?</a></p>

    <!-- Форма скидання пароля -->
    <form method="post" id="reset-password-form" style="display: none;">
        <input type="hidden" name="reset_password" value="1">

        <label for="reset-email">Email:</label>
        <input type="email" id="reset-email" name="email" required placeholder="Введіть ваш email для скидання">

        <button type="submit">Скинути пароль</button>
    </form>

<script>
    document.getElementById('show-reset').addEventListener('click', function (e) {
        e.preventDefault();
        document.getElementById('reset-password-form').style.display = 'block';
    });
</script>
<?php include '../includes/blocks/footer.php';?>
