<?php
require __DIR__ . "/../db/config.php";
require __DIR__ . "/../utils/helpers.php";
include __DIR__ . "/../templates/notification.php";

if (isset($_SESSION['user_id'])) {
    header("Location: /rent.it/index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Rent.it</title>
    <link rel="stylesheet" href="/rent.it/styles/pages.css">
    <link rel="stylesheet" href="/rent.it/styles/main.css">
    <link rel="stylesheet" href="/rent.it/styles/card.css">
</head>

<body>

<?php include __DIR__ . "/../templates/header.php"; ?>

<div class="create-wrapper">

    <div class="create-card">

        <div class="neon-title">
            <h1>Login</h1>
        </div>

        <!-- Flash Message -->
        <?php if (!empty($_SESSION['flash_message'])): ?> 
            <div class="flash-message <?= sanitize($_SESSION['flash_type'] ?? 'info') ?>"> <!-- Ternary Operator that defaults to 'info' type if not set -->
                <?= sanitize($_SESSION['flash_message']); ?>
            </div>
            <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
        <?php endif; ?>


        <form method="POST" action="process_login.php">

            <label>Username</label>
            <input type="text" name="username" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>

        <br>

        <p><a href="/rent.it/account/forgot.php">Forgot your password?</a></p>
        <p>
            New user?
            <button onclick="window.location.href='register.php'">Create Account</button>
        </p>

    </div>
</div>

</body>
</html>
