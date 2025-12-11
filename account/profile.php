<?php
require __DIR__ . "/../db/config.php";
require __DIR__ . "/../utils/helpers.php";
include __DIR__ . "/../templates/notification.php";

require_login();

$user_id = $_SESSION['user_id'];
$user = get_user_by_id($conn, $user_id);

// Safety check - ensure user exists
if (!$user) {
    $_SESSION['flash_message'] = "User not found.";
    $_SESSION['flash_type'] = "error";
    header("Location: /rent.it/index.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Profile - Rent.it</title>

    <link rel="stylesheet" href="/rent.it/styles/pages.css">
    <link rel="stylesheet" href="/rent.it/styles/main.css">
    <link rel="stylesheet" href="/rent.it/styles/card.css">
</head>

<body>

<?php include __DIR__ . "/../templates/header.php"; ?>
<?php include __DIR__ . "/../templates/notification.php"; ?>

<div class="profile-wrapper">   
    <div class="profile-card">      <!-- puts profile info into a card view -->
        <h1>My Profile</h1>

        <!-- Username -->
        <div class="profile-item">
            <span class="profile-label">Username:</span>
            <?= sanitize($user['username']) ?>
        </div>

        <!-- Email -->
        <div class="profile-item">
            <span class="profile-label">Email:</span>
            <?= sanitize($user['email']) ?>
        </div>

        <!-- Member Since -->
        <div class="profile-item">
            <span class="profile-label">Member Since:</span>
            <?= sanitize($user['created_at']) ?>
        </div>

        <!-- Profile Links - same as headers, maybe redundant? -->
        <div class="profile-links">

            <!-- Change Password -->
            <a class="btn-primary" href="/rent.it/account/change_password.php">
                Change Password
            </a>

            <a class="btn-secondary" href="/rent.it/listing/my_listings.php">
                My Listings
            </a>

            <a class="btn-secondary" href="/rent.it/requests/my_requests.php">
                My Rental Requests
            </a>

            <a class="btn-secondary" href="/rent.it/requests/owner_requests.php">
                Incoming Requests
            </a>

        </div>
    </div>
</div>

</body>
</html>
