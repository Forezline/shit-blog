<?php
include('../includes/header.php');
include('../includes/db.php');
include('../includes/functions.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    try {
        $stmt->execute([$username, $email, $password]);
        redirect('login.php');
    } catch (PDOException $e) {
        $error = "Користувач з такою поштою вже існує.";
    }
}
?>

<h2>Реєстрація</h2>
<form method="post">
    <input type="text" name="username" required placeholder="Ім'я користувача"><br>
    <input type="email" name="email" required placeholder="Email"><br>
    <input type="password" name="password" required placeholder="Пароль"><br>
    <button type="submit">Зареєструватися</button>
</form>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

<?php include('../includes/footer.php'); ?>
