<?php
global $pdo;
session_start();
require_once('../../includes/database/db.php');
require_once('../../includes/function/functions.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $result = loginUser($pdo, $email, $password);

    if ($result['success']) {
        $_SESSION['user'] = $result['user'];
        redirect('/index.php');
    } else {
        $_SESSION['error'] = $result['error'];
        redirect('/pages/login.php');
    }
}

