<?php
global $pdo;
include('../includes/blocks/header.php');
include('../includes/database/db.php');
include('../includes/function/functions.php');

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
    <label>
        <input type="text" name="username" required placeholder="Ім'я користувача">
    </label><br>
    <label>
        <input type="email" name="email" required placeholder="Email">
    </label><br>
    <label>
        <input type="password" name="password" required placeholder="Пароль">
    </label><br>
    <button type="submit">Зареєструватися</button>
</form>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

<?php include('../includes/blocks/footer.php'); ?>
