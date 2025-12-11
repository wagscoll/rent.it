<?php
require __DIR__ . "/../db/config.php";
require __DIR__ . "/../utils/helpers.php";
include __DIR__ . "/../templates/notification.php";

// Already logged in? Go home
if (isset($_SESSION['user_id'])) {
    header("Location: /rent.it/index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}


$username = trim($_POST['username'] ?? ''); // Sanitize input (if exists), otherwise, empty string
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    $_SESSION['flash_message'] = "Please enter both username and password.";
    $_SESSION['flash_type'] = "error";
    header("Location: login.php");
    exit;
}

// Look up user by username
$user = find_user_by_username($conn, $username);

// Verify password, if user found
if ($user && is_string($user['password_hash'])) {

    //if password matches
    if (password_verify($password, $user['password_hash'])) {

        // Login successful
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['flash_message'] = "Welcome back, " . sanitize($user['username']) . "!";
        $_SESSION['flash_type'] = "success";

        header("Location: /rent.it/index.php");
        exit;
    }
}

// Otherwise: login failed
$_SESSION['flash_message'] = "Invalid username or password.";
$_SESSION['flash_type'] = "error";
header("Location: login.php");
exit;
