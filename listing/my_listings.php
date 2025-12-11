<?php
require __DIR__ . "/../db/config.php";
require __DIR__ . "/../utils/helpers.php";
include __DIR__ . "/../templates/notification.php";

require_login();

$user_id = $_SESSION['user_id'];

// Fetch all listings owned by user
$stmt = $conn->prepare("
    SELECT id, title, description, category, price_per_day, created_at,
           item_condition, deposit, availability, image_path
    FROM listings
    WHERE owner_id = ?
    ORDER BY created_at DESC
");

$stmt->bind_param("i", $user_id);
$stmt->execute();
$listings = $stmt->get_result();
$stmt->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Listings - Rent.it</title>

    <link rel="stylesheet" href="/rent.it/styles/pages.css">
    <link rel="stylesheet" href="/rent.it/styles/main.css">
    <link rel="stylesheet" href="/rent.it/styles/card.css">
</head>

<body>

    <?php include __DIR__ . "/../templates/header.php"; ?>
    <?php include __DIR__ . "/../templates/notification.php"; ?>

    <main class="container">
        <section class="main-content">
            <div class="neon-title">

                <h1>My Listings</h1>
                <div style="margin-right: 40px;">
                    <button onclick="window.location.href='../listing/create_listing.php'">
                        Add New Listing
                    </button>
                </div>
            </div>

            <div class="outer-grid-border">
                <div class="card-container">

                    <?php if ($listings->num_rows > 0): ?> <!-- Has Listings -->

                        <?php while ($row = $listings->fetch_assoc()): ?> <!-- Loop listings for user -->

                            <?php $img = get_image_url($row); ?> <!-- Get image URL -->

                            <div class="card"> <!-- Listing Card -->

                                <div class="card-header">
                                    <h3><?= sanitize($row['title']); ?></h3>
                                    <span class="rating"> <!-- Price per day (repurposed form rating) -->
                                        $<?= number_format($row['price_per_day'], 2); ?>/day
                                    </span>
                                </div>

                                <img class="listing-image" src="<?= sanitize($img); ?>" alt="Listing Image">

                                <!-- New lines added to maintain structure -->
                                <p><?= nl2br(sanitize($row['description'])); ?></p>

                                <p style="margin-top: 8px; font-size: 0.9rem;">

                                    <strong>Category:</strong>
                                    <?= sanitize($row['category'] ?: 'None'); ?><br>

                                    <!-- Uppercase first letter of conditional -->
                                    <strong>Condition:</strong>
                                    <?= sanitize(ucfirst($row['item_condition'])); ?><br>

                                    <strong>Deposit:</strong>
                                    $<?= number_format($row['deposit'], 2); ?><br>

                                    <!-- Availability with color coding -->
                                    <?php
                                    if ($row['availability'] === 'available') {
                                        $availClass = 'availability-available';
                                    } else {
                                        $availClass = 'availability-unavailable';
                                    }
                                    ?>

                                    <strong>Availability:</strong>
                                    <span class="<?= $availClass; ?>">
                                        <?= sanitize(ucfirst($row['availability'])); ?>
                                    </span><br>

                                    <small>Listed on <?= sanitize($row['created_at']); ?></small>
                                </p>

                                <div class="actions-row"> <!-- Edit/Delete Buttons -->

                                    <button onclick="window.location.href='edit_listing.php?id=<?= $row['id']; ?>'">
                                        Edit
                                    </button>

                                    <button onclick="window.location.href='delete_listing.php?id=<?= $row['id']; ?>'"
                                        style="background:#ff6b6b; color:black;">
                                        Delete
                                    </button>

                                </div>

                            </div>

                        <?php endwhile; ?>

                    <?php else: ?> </div> <!-- No Listings -->

                        <p class="no-listings">
                            You have no listings yet.<br><br>
                            <button onclick="window.location.href='/listing/create_listing.php'">
                                Create Your First Listing
                            </button>
                        </p>

                    <?php endif; ?>

                </div>
            </div>

        </section>
    </main>

</body>

</html>