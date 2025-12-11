<?php
require __DIR__ . "/../db/config.php";
require __DIR__ . "/../utils/helpers.php";
include __DIR__ . "/../templates/notification.php";

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['flash_message'] = "Invalid request method."; //safety check
    $_SESSION['flash_type'] = "error";
    header("Location: /rent.it/requests/owner_requests.php");
    exit;
}

// Gather input
$request_id = $_POST['request_id'] ?? null;
$new_status = $_POST['new_status'] ?? null;
$owner_id   = $_SESSION['user_id'];

// if missing or invalid
if (!$request_id || !$new_status) {
    $_SESSION['flash_message'] = "Invalid request.";
    $_SESSION['flash_type'] = "error";
    header("Location: /rent.it/requests/owner_requests.php");
    exit;
}

// Validate allowed statuses
$valid = ["approved", "denied"];

$validStatus = false; 
foreach ($valid as $s) { // check if in allowed list
    if ($new_status === $s) {
        $validStatus = true;
    }
}

if (!$validStatus) {
    $_SESSION['flash_message'] = "Invalid status value.";
    $_SESSION['flash_type'] = "error";
    header("Location: /rent.it/requests/owner_requests.php");
    exit;
}

// Confirm owner owns the request
$verify = $conn->prepare("
    SELECT id 
    FROM rental_requests
    WHERE id = ? AND owner_id = ?
");
$verify->bind_param("ii", $request_id, $owner_id);
$verify->execute();

$res = $verify->get_result();
$verify->close();

// If no request found or not owned by user -> deny
if ($res->num_rows !== 1) {
    $_SESSION['flash_message'] = "You do not have permission to modify this request.";
    $_SESSION['flash_type'] = "error";
    header("Location: /rent.it/requests/owner_requests.php");
    exit;
}

// Update request status
$stmt = $conn->prepare("
    UPDATE rental_requests
    SET status = ?
    WHERE id = ?
");
$stmt->bind_param("si", $new_status, $request_id);
$stmt->execute();
$stmt->close();

// Flash message based on status
if ($new_status === "approved") {
    $_SESSION['flash_message'] = "Request approved!";
    $_SESSION['flash_type'] = "success";
}

if ($new_status === "denied") {
    $_SESSION['flash_message'] = "Request denied.";
    $_SESSION['flash_type'] = "error";
}

// Redirect to inbox
header("Location: /rent.it/requests/owner_requests.php");
exit;
