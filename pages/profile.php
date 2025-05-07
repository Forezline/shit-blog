<?php
global $pdo;
include('../includes/blocks/header.php');
include('../includes/database/db.php');
include('../includes/function/functions.php');

if (!isLoggedIn()) {
    redirect('login.php');
}

$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newName = sanitizeInput($_POST['username']);
    $newEmail = sanitizeInput($_POST['email']);
    $newFirstName = sanitizeInput($_POST['first_name']);
    $newLastName = sanitizeInput($_POST['last_name']);
    $newAge = sanitizeInput($_POST['age']);
    $newGender = sanitizeInput($_POST['gender']);
    $newAddress = sanitizeInput($_POST['address']);
    $newWebsite = sanitizeInput($_POST['website']);

    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, first_name = ?, last_name = ?, age = ?, gender = ?, address = ?, website = ? WHERE id = ?");
    $stmt->execute([$newName, $newEmail, $newFirstName, $newLastName, $newAge, $newGender, $newAddress, $newWebsite, $user['id']]);

    $_SESSION['user'] = array_merge($_SESSION['user'], [
        'username' => $newName,
        'email' => $newEmail,
        'first_name' => $newFirstName,
        'last_name' => $newLastName,
        'age' => $newAge,
        'gender' => $newGender,
        'address' => $newAddress,
        'website' => $newWebsite
    ]);
    
    $message = "Профіль оновлено успішно!";
}
?>

<h2>Ваш профіль</h2>

<?php if (!empty($message)) echo "<p>$message</p>"; ?>

<form method="post">
    <div>
        <label for="username">Ім'я користувача:</label>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($_SESSION['user']['username']) ?>" readonly><br>
    </div>
    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= isset($_SESSION['user']['email']) ? htmlspecialchars($_SESSION['user']['email']) : '' ?>" readonly><br>
    </div>
    <div>
        <label for="first_name">Ім'я:</label>
        <input type="text" id="first_name" name="first_name" value="<?= isset($_SESSION['user']['first_name']) && $_SESSION['user']['first_name'] ? htmlspecialchars($_SESSION['user']['first_name']) : '' ?>" placeholder="Заповніть дані"><br>
    </div>
    <div>
        <label for="last_name">Прізвище:</label>
        <input type="text" id="last_name" name="last_name" value="<?= isset($_SESSION['user']['last_name']) && $_SESSION['user']['last_name'] ? htmlspecialchars($_SESSION['user']['last_name']) : '' ?>" placeholder="Заповніть дані"><br>
    </div>
    <div>
        <label for="age">Вік:</label>
        <input type="number" id="age" name="age" min="0" max="120" 
               value="<?= isset($_SESSION['user']['age']) && $_SESSION['user']['age'] ? htmlspecialchars($_SESSION['user']['age']) : '' ?>"
               placeholder="Заповніть дані"><br>
    </div>
    <div>
        <label for="gender">Стать:</label>
        <select id="gender" name="gender">
            <option value="Male" <?= isset($_SESSION['user']['gender']) && $_SESSION['user']['gender'] === 'Male' ? 'selected' : '' ?>>Чоловік</option>
            <option value="Female" <?= isset($_SESSION['user']['gender']) && $_SESSION['user']['gender'] === 'Female' ? 'selected' : '' ?>>Жінка</option>
            <option value="Other" <?= isset($_SESSION['user']['gender']) && $_SESSION['user']['gender'] === 'Other' ? 'selected' : '' ?>>Інше</option>
        </select><br>
    </div>
    <button type="submit">Оновити профіль</button>
</form>



<?php include('../includes/blocks/footer.php'); ?>
