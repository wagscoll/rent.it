<?php
require __DIR__ . "/../db/config.php";
require __DIR__ . "/../utils/helpers.php";
include __DIR__ . "/../templates/notification.php";

require_login();

$errors = [];

// Validate listing ID
    // ctype_digit checks for positive integers only
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    $_SESSION['flash_message'] = "Invalid listing ID.";
    $_SESSION['flash_type'] = "error";
    header("Location: /rent.it/listing/my_listings.php");
    exit;
}

$listing_id = (int) $_GET['id'];
$user_id    = $_SESSION['user_id'];

// Fetch listing safely
$listing = get_editable_listing($conn, $listing_id, $user_id);

if (!$listing) {
    $_SESSION['flash_message'] = "Listing not found or access denied.";
    $_SESSION['flash_type'] = "error";
    header("Location: /rent.it/listing/my_listings.php");
    exit;
}

// Pre-fill values
$title          = $listing['title'];
$description    = $listing['description'];
$category       = $listing['category'];
$price_per_day  = $listing['price_per_day'];
$item_condition = $listing['item_condition'];
$deposit        = $listing['deposit'];
$availability   = $listing['availability'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title          = trim($_POST['title'] ?? '');
    $description    = trim($_POST['description'] ?? '');
    $category       = trim($_POST['category'] ?? '');
    $price_per_day  = floatval($_POST['price_per_day'] ?? 0);
    $item_condition = $_POST['item_condition'] ?? 'good';
    $deposit        = floatval($_POST['deposit'] ?? 0);
    $availability   = $_POST['availability'] ?? 'available';

    if ($title === '' || $description === '' || $_POST['price_per_day'] === '') {
        $errors[] = "Title, description, and price are required.";
    }

    if ($price_per_day < 0) {
        $errors[] = "Price must be non-negative.";
    }

    if (empty($errors)) {

        $stmt = $conn->prepare("
            UPDATE listings
            SET title = ?, description = ?, category = ?, price_per_day = ?,
                item_condition = ?, deposit = ?, availability = ?
            WHERE id = ? AND owner_id = ?
        ");

        $stmt->bind_param(
            "sssdsdsii",
            $title,             // string
            $description,       // string
            $category,          // string
            $price_per_day,     // double
            $item_condition,    // string
            $deposit,           // double
            $availability,      // string
            $listing_id,        // int
            $user_id            // int
        );

        if ($stmt->execute()) {
            $_SESSION['flash_message'] = "Listing updated successfully!";
            $_SESSION['flash_type'] = "success";
            header("Location: /rent.it/listing/my_listings.php");
            exit;
        } else {
            $errors[] = "Database error while updating listing.";
        }

        $stmt->close();
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Listing - Rent.it</title>
    <link rel="stylesheet" href="/rent.it/styles/pages.css">
    <link rel="stylesheet" href="/rent.it/styles/main.css">
    <link rel="stylesheet" href="/rent.it/styles/card.css">
</head>

<body>

<?php include __DIR__ . "/../templates/header.php"; ?>

<div class="edit-wrapper">
    <div class="edit-card">

        <h1>Edit Listing</h1>

        <?php foreach ($errors as $e): ?>
            <div class="error"><?= sanitize($e); ?></div>
        <?php endforeach; ?>

        <?php
        $categories = [
            "Sports",
            "Tools",
            "Outdoors",
            "Electronics",
            "Home Goods",
            "Automotive",
            "Photography",
            "Party/Event",
            "Miscellaneous"
        ];
        ?>

        <form method="POST">

            <label>Title</label>
            <input type="text" name="title" value="<?= sanitize($title); ?>" required>

            <label>Description</label>
            <textarea name="description" rows="5" required><?= sanitize($description); ?></textarea>

            <label>Category</label>
            <select name="category" required>
                <option value="">-- Select Category --</option>

                <?php foreach ($categories as $c): ?>
                    <option value="<?= sanitize($c) ?>"
                        <?php if ($category === $c) echo "selected"; ?>>
                        <?= sanitize($c) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Price Per Day ($)</label>
            <input type="number" step="0.01" name="price_per_day" value="<?= sanitize($price_per_day); ?>" required>

            <label>Condition</label>
            <select name="item_condition">
                <?php
                $conditions = ["new", "good", "fair", "poor"];
                foreach ($conditions as $cond):
                ?>
                    <option value="<?= $cond ?>"
                        <?php if ($item_condition === $cond) echo "selected"; ?>>
                        <?= ucfirst($cond) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Deposit ($)</label>
            <input type="number" step="0.01" name="deposit" value="<?= sanitize($deposit); ?>" required>

            <label>Availability</label>
            <select name="availability">
                <option value="available" <?php if ($availability === "available") echo "selected"; ?>>Available</option>
                <option value="unavailable" <?php if ($availability === "unavailable") echo "selected"; ?>>Unavailable</option>
            </select>

            <button type="submit">Save Changes</button>
        </form>

    </div>
</div>

</body>
</html>
