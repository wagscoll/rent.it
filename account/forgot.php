<?php
require __DIR__ . "/../db/config.php";
require __DIR__ . "/../utils/helpers.php";
include __DIR__ . "/../templates/notification.php";

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? ""); // Sanitize input 

    if ($email === "") {
        $error = "Please enter your email.";
    } else {

        // Check if user exists
        $userRow = email_exists($conn, $email);

        if ($userRow) {
            $user_id = $userRow['id']; // Get user ID from fetched row

            // Create secure reset token -- implemented from: https://security.stackexchange.com/questions/40310/generating-an-unguessable-token-for-confirmation-e-mails/40315#40315
            $token = bin2hex(random_bytes(32));
            $expires = date("Y-m-d H:i:s", time() + 3600);

            // Store token in database
            if (store_reset_token($conn, $user_id, $token, $expires)) {

                $reset_link = "/rent.it/account/reset.php?token=" . $token;

                $success = 
                "   Password reset link generated:<br>
                    <a href=\"$reset_link\">$reset_link</a>
                    <br><br><small>Link expires in 1 hour.</small>
                ";

            } else {
                $error = "Failed to generate reset link. Please try again.";
            }

        } else {
            $error = "No user found with that email.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Forgot Password - Rent.it</title>
    <link rel="stylesheet" href="/rent.it/styles/pages.css">
    <link rel="stylesheet" href="/rent.it/styles/main.css">
    <link rel="stylesheet" href="/rent.it/styles/card.css">
</head>

<body>

<?php include __DIR__ . "/../templates/header.php"; ?>

<div class="req-wrapper">

    <!-- Page Title -->
    <div class="neon-title">
        <h2>Forgot Password</h2>
    </div>

    <!-- Display Error Message -->
    <?php if ($error !== ""): ?>
        <div class="flash-message error"><?= sanitize($error) ?></div>
    <?php endif; ?>

    <!-- Display Success Message -->
    <?php if ($success !== ""): ?>
        <div class="flash-message success"><?= $success ?></div>
    <?php endif; ?>

    <!-- Only show form if we have NOT succeeded -->
    <?php if ($success === ""): ?>
        <form method="POST">
            <div class="neon-title" style="margin-bottom: 10px;">
                <label>Please enter your email to receive a password reset link.</label>
    </div>
                <input type="email" name="email" required>

            <input type="submit" value="Send Reset Link">
        </form>
    <?php endif; ?>

</div>

</body>
</html>
