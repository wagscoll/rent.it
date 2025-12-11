<?php
require __DIR__ . "/../db/config.php";
require __DIR__ . "/../utils/helpers.php";
include __DIR__ . "/../templates/notification.php";

require_login();

$user_id = $_SESSION['user_id'];

// Validate listing ID
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    $_SESSION['flash_message'] = "Invalid listing ID.";
    $_SESSION['flash_type'] = "error";
    header("Location: /rent.it/listing/my_listings.php");
    exit;
}

$listing_id = (int) $_GET['id'];

// Fetch listing if user owns it
$listing = get_listing_owned_by_user($conn, $listing_id, $user_id);

if (!$listing) {
    $_SESSION['flash_message'] = "Listing not found or permission denied.";
    $_SESSION['flash_type'] = "error";
    header("Location: /rent.it/listing/my_listings.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Cancel
    if (isset($_POST['cancel'])) {
        header("Location: /rent.it/listing/my_listings.php");
        exit;
    }

    // Confirm Delete
    if (isset($_POST['confirm'])) {

        // Delete image file if exists
        if (!empty($listing['image_path'])) {
            $filePath = __DIR__ . "/../" . $listing['image_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $stmt = $conn->prepare("
            DELETE FROM listings
            WHERE id = ? AND owner_id = ?
        ");
        
        $stmt->bind_param("ii", $listing_id, $user_id);
        $stmt->execute();
        $stmt->close();

        $_SESSION['flash_message'] = "Listing deleted successfully.";
        $_SESSION['flash_type'] = "success";

        header("Location: /rent.it/listing/my_listings.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Delete Listing - Rent.it</title>

    <link rel="stylesheet" href="/rent.it/styles/pages.css">
    <link rel="stylesheet" href="/rent.it/styles/main.css">
    <link rel="stylesheet" href="/rent.it/styles/card.css">
</head>

<body>

<?php include __DIR__ . "/../templates/header.php"; ?>

<div class="delete-wrapper">
    <div class="delete-card">

        <h1>Confirm Delete</h1>

        <p>
            Are you sure you want to delete:<br>
            <strong><?= sanitize($listing['title']); ?></strong>?
        </p>

        <form method="POST">
            <div class="button-row">

                <button type="submit" name="confirm" class="confirm-btn">
                    Yes, Delete
                </button>

                <button type="submit" name="cancel" class="cancel-btn">
                    Cancel
                </button>

            </div>
        </form>

    </div>
</div>

</body>
</html>
