<?php
require __DIR__ . "/../db/config.php";
require __DIR__ . "/../utils/helpers.php";
include __DIR__ . "/../templates/notification.php";

require_login();

// Validate item_id
if (!isset($_GET['item_id']) || !is_numeric($_GET['item_id'])) {
    $_SESSION['flash_message'] = "Invalid item selected.";
    $_SESSION['flash_type'] = "error";
    header("Location: /rent.it/index.php");
    exit;
}

$item_id = intval($_GET['item_id']);
$renter_id = $_SESSION['user_id'];

// Fetch listing + owner
$stmt = $conn->prepare("
    SELECT title, owner_id
    FROM listings
    WHERE id = ?
");

// ITEM_ID param bind from above
$stmt->bind_param("i", $item_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result->num_rows !== 1) {
    $_SESSION['flash_message'] = "Listing not found.";
    $_SESSION['flash_type'] = "error";
    header("Location: /rent.it/index.php");
    exit;
}

$listing = $result->fetch_assoc();
$owner_id = $listing["owner_id"];

// Prevent own from self-requesting, flash banner
if ($owner_id === $renter_id) {
    $_SESSION['flash_message'] = "You cannot request your own item.";
    $_SESSION['flash_type'] = "error";
    header("Location: /rent.it/index.php");
    exit;
}

$error = "";

// Handle submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $message = trim($_POST['message'] ?? '');
    
    //This is where a renter can specify rental details
    if ($message === "") {
        $error = "Please enter a message for the owner."; 
    } else {

        //Assuming no error (safety check) proceed to insert record for owner's review
        $stmt = $conn->prepare("
            INSERT INTO rental_requests (item_id, renter_id, owner_id, message)
            VALUES (?, ?, ?, ?)
        ");
                                 //int    , int       , int      , string
        $stmt->bind_param("iiis", $item_id, $renter_id, $owner_id, $message);

        if ($stmt->execute()) {
            $_SESSION['flash_message'] = "Request sent!";
            $_SESSION['flash_type'] = "success";
            header("Location: /rent.it/requests/my_requests.php");
            exit;
        }

        $stmt->close();

        $error = "An error occurred while sending the request.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Request Rental</title>
    <link rel="stylesheet" href="/rent.it/styles/pages.css">
    <link rel="stylesheet" href="/rent.it/styles/main.css">
    <link rel="stylesheet" href="/rent.it/styles/card.css">
</head>

<body>

<?php include __DIR__ . "/../templates/header.php"; ?>
<?php include __DIR__ . "/../templates/notification.php"; ?>

<div class="request-wrapper">

    <div class="request-card">

        <h2>Request to Rent: <?= sanitize($listing['title']) ?></h2>

        <?php if (!empty($error)): ?>        <!-- Display error if exists -->
            <div class="flash-message error"><?= sanitize($error) ?></div>
        <?php endif; ?>

        <form method="POST">

            <p><label class="neon-title" style="margin-bottom: 0px;">Your Message to the Owner:</label></p>
            <textarea name="message" rows="5" required></textarea>

            <button type="submit">Send Request</button>

        </form>

    </div>

</div>

</body>
</html>
