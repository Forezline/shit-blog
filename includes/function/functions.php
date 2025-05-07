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