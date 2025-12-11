<?php
require __DIR__ . '/../db/config.php';
require __DIR__ . '/../utils/helpers.php';

require_login();

// Validate request ID
if (!isset($_POST['request_id']) || !ctype_digit($_POST['request_id'])) {
    $_SESSION['flash_message'] = "Invalid rental request.";
    $_SESSION['flash_type'] = "error";
    header("Location: /rent.it/requests/my_requests.php");
    exit;
}

$request_id = (int) $_POST['request_id'];
$user_id = $_SESSION['user_id'];

// Check permission -- just a safety check, shouldn't 
if (!user_can_delete_request($conn, $request_id, $user_id)) {
    $_SESSION['flash_message'] = "You are not allowed to delete this request.";
    $_SESSION['flash_type'] = "error";
    header("Location: /rent.it/requests/my_requests.php");
    exit;
}


// Delete the record
$stmt = $conn->prepare("
    DELETE FROM rental_requests
    WHERE id = ?
    AND (renter_id = ? OR owner_id = ?)
");

$stmt->bind_param("iii", $request_id, $user_id, $user_id);
$stmt->execute();
$stmt->close();

$_SESSION['flash_message'] = "Rental request deleted successfully.";
$_SESSION['flash_type'] = "success";

// Redirect 
$redirect = "/rent.it/requests/my_requests.php";

if (isset($_SERVER['HTTP_REFERER'])) {
    // Only allow internal redirects
    if (str_contains($_SERVER['HTTP_REFERER'], "rent.it")) {
        $redirect = $_SERVER['HTTP_REFERER'];
    }
}

header("Location: $redirect");
exit;
