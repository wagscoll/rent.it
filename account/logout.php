<?php
require __DIR__ . "/../db/config.php";
include __DIR__ . "/../templates/notification.php";


$_SESSION = [];
session_destroy();

// Start a new session for flash messaging
session_start();
$_SESSION['flash_message'] = "You have been logged out.";
$_SESSION['flash_type'] = "info";

// Redirect to login
header("Location: /rent.it/account/login.php");
exit;
?>