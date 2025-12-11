<?php
require __DIR__ . "/../db/config.php";
require __DIR__ . "/../utils/helpers.php";
include __DIR__ . "/../templates/notification.php";

$token = $_GET['token'] ?? '';
$error = '';
$success = '';


// if no token provided, redirect to forgot pw
if ($token === '') {
    $_SESSION['flash_message'] = "Invalid password reset link.";
    $_SESSION['flash_type'] = "error";
    header("Location: /rent.it/account/forgot.php");
    exit;
}

$user = get_user_by_reset_token($conn, $token);

if (!$user) {
    $_SESSION['flash_message'] = "Invalid or expired password reset link.";
    $_SESSION['flash_type'] = "error";
    header("Location: /rent.it/account/forgot.php");
    exit;
}

// not fully implemented, checks empiry time 
if (strtotime($user['reset_expires']) < time()) {
    $_SESSION['flash_message'] = "Your reset link has expired. Please request a new one.";
    $_SESSION['flash_type'] = "error";
    clear_reset_token($conn, $user['id']);
    header("Location: /rent.it/account/forgot.php");
    exit;
}

$user_id = $user['id'];


// Handles password reset submission posts new password to db if validated 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $password_1 = $_POST['password'] ?? '';
    $pass2 = $_POST['confirm_password'] ?? '';

    if ($password_1 === '' || $password_2 === '') //pw empty
    {
        $error = "Both fields are required.";
    } 
    elseif ($password_1 !== $password_2) //no match
    {
        $error = "Passwords do not match.";
    } 
    elseif (strlen($password_1) < 6) 
    {
        $error = "Password must be at least 6 characters long.";
    } 
    else 
    {
        $hash = password_hash($password_1, PASSWORD_DEFAULT);

        // Update and clear token from db
        $update = $conn->prepare("
            UPDATE users
            SET password_hash = ?, reset_token = NULL, reset_expires = NULL
            WHERE id = ?
        ");
                                  //str, int
        $update->bind_param("si", $hash, $user_id);

        if ($update->execute()) {
            $success = "Password reset successful! You may now log in.";
        } else {
            $error = "Unexpected database error. Please try again.";
        }

        $update->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Password - Rent.it</title>
    <link rel="stylesheet" href="/rent.it/styles/pages.css">
    <link rel="stylesheet" href="/rent.it/styles/main.css">
    <link rel="stylesheet" href="/rent.it/styles/card.css">
</head>

<body>

<?php include __DIR__ . "/../templates/header.php"; ?>

<div class="reset-wrapper">
    <div class="reset-card">

        <h2>Reset Your Password</h2>

        <?php if ($error !== ''): ?>
            <div class="flash-message error"><?= sanitize($error) ?></div>
        <?php endif; ?>

        <?php if ($success !== ''): ?>
            <div class="flash-message success"><?= sanitize($success) ?></div>

            <button
                onclick="window.location.href='/rent.it/account/login.php'"
                class="reset-success-btn">
                Go To Login
            </button>

            <!-- only shows form if not successful -->
        <?php else: ?>
            <form method="POST">

                <label>New Password:</label>
                <input type="password" name="password" required>

                <label>Confirm Password:</label>
                <input type="password" name="confirm_password" required>

                <input type="submit" value="Reset Password">
            </form>
        <?php endif; ?>

    </div>
</div>

</body>
</html>
