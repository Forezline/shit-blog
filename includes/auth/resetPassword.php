<?php
global $pdo;
session_start();
require_once('../../includes/database/db.php');
require_once('../../includes/function/functions.php');

$token = $_GET['token'] ?? '';

if (empty($token)) {
    $_SESSION['error'] = "Невірний токен.";
    redirect('/pages/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    resetPassword($pdo, $token, $_POST['new_password'], $_POST['confirm_password']);
}
