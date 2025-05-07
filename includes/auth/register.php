<?php
global $pdo;
session_start();
include('../../includes/database/db.php');
include('../../includes/function/functions.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    registerUser($pdo, $_POST['username'], $_POST['email'], $_POST['password']);
}
