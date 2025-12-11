<?php
require __DIR__ . "/../db/config.php";
require __DIR__ . "/../utils/helpers.php";
include __DIR__ . "/../templates/notification.php";

require_login();

$errors = [];

// Default form values
$title = "";
$description = "";
$category = "";
$price_per_day = "";
$item_condition = "good";
$deposit = 0;
$availability = "available";

// Default categories, can be expanded
$default_categories = [
    "Tools",
    "Sports",
    "Electronics",
    "Outdoor",
    "Appliances",
    "Party Supplies"
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                                    // ternary operators to avoid blank values throwing errors
    $title          = trim($_POST['title'] ?? '');      // i.e. use default values if not set
    $description    = trim($_POST['description'] ?? '');
    $category       = trim($_POST['category'] ?? '');
    $price_per_day  = trim($_POST['price_per_day'] ?? '');
    $item_condition = $_POST['item_condition'] ?? 'good';
    $deposit        = $_POST['deposit'] ?? 0;
    $availability   = $_POST['availability'] ?? 'available';

    // Validation - required fields
    if ($title === '' || $description === '' || $price_per_day === '') {
        $errors[] = "Title, description, and price are required.";
    }

    // Validation - numeric fields
    if (!is_numeric($price_per_day) || floatval($price_per_day) < 0) {
        $errors[] = "Price must be a non-negative number.";
    }

    if (!is_numeric($deposit) || floatval($deposit) < 0) {
        $errors[] = "Deposit must be a non-negative number.";
    }

    // Image upload, if not provided, returns null defaults to placeholder.png
    $image_path = upload_image($_FILES['image'] ?? null, $errors);

    // Insert listing into database 
    if (empty($errors)) {

        $owner_id = $_SESSION['user_id'];

        $stmt = $conn->prepare("
            INSERT INTO listings (
                owner_id, title, description, category,
                price_per_day, item_condition, deposit, availability, image_path
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
      
        $stmt->bind_param(
            "isssdsdss",
            $owner_id,          // int
            $title,             // string
            $description,       // string
            $category,          // string
            $price_per_day,     // double
            $item_condition,    // string
            $deposit,           // double
            $availability,      // string
            $image_path         // string or null
        );

        if ($stmt->execute()) {
            $_SESSION['flash_message'] = "Listing created successfully!";
            $_SESSION['flash_type'] = "success";
            header("Location: /rent.it/listing/my_listings.php");
            exit;
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Create Listing - Rent.it</title>
    <link rel="stylesheet" href="/rent.it/styles/pages.css">
    <link rel="stylesheet" href="/rent.it/styles/main.css">
    <link rel="stylesheet" href="/rent.it/styles/card.css">
</head>

<body>

<?php include __DIR__ . "/../templates/header.php"; ?>



<div class="req-wrapper">
    <div class="neon-title">
        <h1>Create a New Listing</h1>
    </div>


    <div class="create-card">

        <?php foreach ($errors as $e): ?>
            <div class="error"><?= sanitize($e) ?></div>
        <?php endforeach; ?>

        <form method="POST" enctype="multipart/form-data">

            <label class="neon-title" style="margin-bottom: 0px; letter-spacing: 2px;">Title</label>
            <input type="text" name="title" value="<?= sanitize($title) ?>" required>

            <label class="neon-title" style="margin-bottom: 0px; letter-spacing: 2px;">Description</label>
            <textarea name="description" rows="2" required><?= sanitize($description) ?></textarea>

            <!-- Correct Category Dropdown -->
            <label class="neon-title" style="margin-bottom: 0px; letter-spacing: 2px;">Category</label>
            <select name="category" required>
                <option value="">-- Select Category --</option>
                
                <?php foreach ($default_categories as $c): ?>
                    <option value="<?= sanitize($c) ?>"
                        <?php if ($category === $c) echo "selected"; ?>>
                        <?= sanitize($c) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label class="neon-title" style="margin-bottom: 0px; letter-spacing: 2px;">Price Per Day ($)</label>
            <input type="number" step="0.01" name="price_per_day"
                   value="<?= sanitize($price_per_day) ?>" required>

            <label class="neon-title" style="margin-bottom: 0px; letter-spacing: 2px;">Condition</label>
            <select name="item_condition">
                <option value="new" <?php if ($item_condition === "new") echo "selected"; ?>>New</option>
                <option value="good" <?php if ($item_condition === "good") echo "selected"; ?>>Good</option>
                <option value="fair" <?php if ($item_condition === "fair") echo "selected"; ?>>Fair</option>
                <option value="poor" <?php if ($item_condition === "poor") echo "selected"; ?>>Poor</option>
            </select>

            <label class="neon-title" style="margin-bottom: 0px; letter-spacing: 2px;">Deposit ($)</label>
            <input type="number" step="0.01" name="deposit" 
                   value="<?= sanitize($deposit) ?>">

            <label class="neon-title" style="margin-bottom: 0px; letter-spacing: 2px;">Availability</label>
            <select name="availability">
                <option value="available" <?php if ($availability === "available") echo "selected"; ?>>Available</option>
                <option value="unavailable" <?php if ($availability === "unavailable") echo "selected"; ?>>Unavailable</option>
            </select>

            <label class="neon-title" style="margin-bottom: 0px; letter-spacing: 2px;">Image</label>
            <input type="file" name="image" accept="image/*">

            <button type="submit">Create Listing</button>
        </form>

    </div>
</div>

</body>
</html>
