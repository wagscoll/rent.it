<?php
session_start();
require __DIR__ . "/../db/config.php";
require __DIR__ . "/../utils/helpers.php";
include __DIR__ . "/../templates/notification.php";

require_login();

$errors = [];

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $current_password = $_POST['current_password'] ?? '';
    $new_password     = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($current_password === '' || $new_password === '' || $confirm_password === '') {
        $errors[] = "All fields are required.";
    }

    if ($new_password !== $confirm_password) {
        $errors[] = "New passwords do not match.";
    }

    if (strlen($new_password) < 6) {
        $errors[] = "New password must be at least 6 characters.";
    }

    if (empty($errors)) {

        $user_id = $_SESSION['user_id'];
        $userRow = fetch_user_password_hash($conn, $user_id); // Fetch stored pw hash

        if (!$userRow) {
            $errors[] = "User not found.";
        } else {
            $stored_hash = $userRow['password_hash'];

            if (!password_verify($current_password, $stored_hash)) {
                $errors[] = "Current password is incorrect.";
            } else {
                $new_hash = password_hash($new_password, PASSWORD_DEFAULT);

                if (update_user_password($conn, $user_id, $new_hash)) {

                    $_SESSION['flash_message'] = "Password updated successfully!";
                    $_SESSION['flash_type'] = "success";

                    header("Location: change_password.php");
                    exit;

                } else {
                    $errors[] = "Failed to update password. Try again later.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Rent.it - Change Password</title>
    <link rel="stylesheet" href="/rent.it/styles/pages.css">
    <link rel="stylesheet" href="/rent.it/styles/main.css">
    <link rel="stylesheet" href="/rent.it/styles/card.css">
</head>

<body>

<?php include __DIR__ . "/../templates/header.php"; ?>

<div class="create-wrapper">

    <div class="create-card">

        <div class="neon-title">
            <h1>Change Password</h1>
        </div>

        <!-- Flash message -->
        <?php if (!empty($_SESSION['flash_message'])): ?>
            <div class="flash-message <?= sanitize($_SESSION['flash_type'] ?? 'info') ?>">
                <?= sanitize($_SESSION['flash_message']); ?>
            </div>
            <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
        <?php endif; ?>

        <!-- Inline errors -->
        <?php foreach ($errors as $e): ?>
            <div class="flash-message error"><?= sanitize($e) ?></div>
        <?php endforeach; ?>

        <form method="POST">

            <label>Current Password</label>
            <input type="password" name="current_password" required>

            <label>New Password</label>
            <input type="password" name="new_password" required>

            <label>Confirm New Password</label>
            <input type="password" name="confirm_password" required>

            <button type="submit">Update Password</button>
        </form>

        <br>

        <p><a href="/rent.it/index.php">Back to Home</a></p>

    </div>
</div>

</body>
</html>
