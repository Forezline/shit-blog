<?php

use JetBrains\PhpStorm\NoReturn;

#[NoReturn] function redirect($url): void
{
    header("Location: $url");
    exit;
}

function isLoggedIn(): bool
{
    return isset($_SESSION['user']);
}

function sanitizeInput($input): string
{
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function getFivePosts($pdo)
{
    $stmt = $pdo->prepare("SELECT posts.id, posts.title, posts.created_at, users.username FROM posts 
                       JOIN users ON posts.user_id = users.id 
                       ORDER BY posts.created_at DESC LIMIT 5");
    $stmt->execute();
    return $stmt->fetchAll();
}

function loginUser(PDO $pdo, string $email, string $password): array
{
    if (empty($email) || empty($password)) {
        return ['success' => false, 'error' => "Усі поля обов'язкові."];
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        unset($user['password']);
        return ['success' => true, 'user' => $user];
    }

    return ['success' => false, 'error' => "Невірна пошта або пароль."];
}

function registerUser($pdo, $username, $email, $password): void
{
    $username = sanitizeInput($username);
    $email = sanitizeInput($email);
    $password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    try {
        $stmt->execute([$username, $email, $password]);
        $_SESSION['success'] = "Успішна реєстрація. Увійдіть.";
        redirect('/pages/login.php');
    } catch (PDOException $e) {
        $_SESSION['error'] = "Користувач з такою поштою вже існує.";
        redirect('/pages/register.php');
    }
}

#[NoReturn] function resetPassword($pdo, $token, $new_password, $confirm_password): void {
    // Перевірка токену
    $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ? AND created_at > NOW() - INTERVAL 1 HOUR");
    $stmt->execute([$token]);
    $reset_request = $stmt->fetch();

    if (!$reset_request) {
        $_SESSION['error'] = "Токен недійсний або протермінований.";
        redirect('/pages/login.php');
    }

    // Перевірка збігу паролів
    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = "Паролі не співпадають.";
        redirect("/pages/reset_password.php?token=" . $token);
    }

    // Оновлення пароля
    $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->execute([$new_password_hashed, $reset_request['email']]);

    // Видалення токену скидання пароля
    $stmt = $pdo->prepare("DELETE FROM password_resets WHERE token = ?");
    $stmt->execute([$token]);

    $_SESSION['success'] = "Пароль успішно оновлено.";
    redirect('/pages/login.php');
}