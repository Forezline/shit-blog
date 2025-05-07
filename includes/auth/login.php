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
