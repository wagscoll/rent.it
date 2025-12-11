<?php
session_start();
require __DIR__ . "/../db/config.php";
require __DIR__ . "/../utils/helpers.php";
include __DIR__ . "/../templates/notification.php";

$errors = [];
$email = "";
$username = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Collect + trim user input - this uses ternary operator to enforce empty string if not set, otehrwise trim() would error
    $email    = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    
    if ($email === '' || $username === '' || $password === '' || $confirm === '') {
        $errors[] = "All fields are required.";
    }

    // implemented from: https://www.w3schools.com/php/filter_validate_email.asp
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    if ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    }

    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }

    // Check for duplicate email or username
    if (empty($errors)) {

        if (email_exists($conn, $email)) {
            $errors[] = "An account with that email already exists.";
        }

        if (username_exists($conn, $username)) {
            $errors[] = "That username is already taken.";
        }
    }

  
    // Create account if no errors
    if (empty($errors)) {

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("
            INSERT INTO users (email, username, password_hash)
            VALUES (?, ?, ?)
        ");

                         //sss - string, string   , string
        $stmt->bind_param("sss", $email, $username, $hash);

        if ($stmt->execute()) {

            $_SESSION['flash_message'] = "Registration successful! You may now log in.";
            $_SESSION['flash_type'] = "success";

            header("Location: login.php");
            exit;

        } else {
            $errors[] = "Database error — unable to create account.";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register - Rent.it</title>
    <link rel="stylesheet" href="/rent.it/styles/pages.css">
    <link rel="stylesheet" href="/rent.it/styles/main.css">
    <link rel="stylesheet" href="/rent.it/styles/card.css">
</head>

<body>

<?php include __DIR__ . "/../templates/header.php"; ?>

<div class="register-wrapper">
    <div class="register-card">

        <h2>Create Account</h2>

        <!-- Errors -->
        <?php if (!empty($errors)): ?>
            <div class="flash-message error">
                <?php foreach ($errors as $e): ?>
                    <div><?= sanitize($e); ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="register.php">

            <label>Email:</label>
            <input
                type="email"
                name="email"
                required
                value="<?= sanitize($email); ?>">

            <label>Username:</label>
            <input
                type="text"
                name="username"
                required
                value="<?= sanitize($username); ?>">

            <label>Password:</label>
            <input type="password" name="password" required>

            <label>Confirm Password:</label>
            <input type="password" name="confirm_password" required>

            <input type="submit" value="Create Account">
        </form>

        <div class="register-links">
            <p>Already have an account?</p>
            <button onclick="window.location.href='login.php'">Login</button>
        </div>

    </div>
</div>

<?php include __DIR__ . "/../templates/footer.php"; ?>

</body>
</html>
