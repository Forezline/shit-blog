<?php
global $pdo;
session_start();
include('../../includes/database/db.php');
include('../../includes/function/functions.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

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
