<?php
require __DIR__ . "/db/config.php";
require __DIR__ . "/utils/helpers.php";
include __DIR__ . "/templates/notification.php";


// Fetch all listings that are tagged as 'available'
$stmt = $conn->prepare("
    SELECT l.id, l.title, l.description, l.category, l.price_per_day, l.image_path, l.created_at,
           u.username AS owner_name
    FROM listings l
    INNER JOIN users u ON l.owner_id = u.id
    WHERE l.availability = 'available'
    ORDER BY l.created_at DESC
");

$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Rent.it - Home</title>
    <link rel="stylesheet" href="/rent.it/styles/pages.css">
    <link rel="stylesheet" href="/rent.it/styles/main.css">
    <link rel="stylesheet" href="/rent.it/styles/card.css">
</head>

<body>

<?php include __DIR__ . "/templates/header.php"; ?>

<main class="container">

    <!--SIDEBAR FILTER -->
    <aside class="sidebar">
        <h2 class="filter-title">Filter</h2>

        <form onsubmit="event.preventDefault();">

            <!-- SEARCH -->
            <div class="sidebar-title">
                <label for="searchInput">Search:</label>
            </div>
            <div class="sidebar-entry">
                <input type="text" id="searchInput" placeholder="Search items...">
            </div>

            <!-- CATEGORY -->
            <div class="sidebar-title">
                <label for="categoryFilter">Category:</label>
            </div>
            <div class="sidebar-entry">
                <select id="categoryFilter">
                    <option value="">All</option>
                    <option value="Sports">Sports</option>
                    <option value="Tools">Tools</option>
                    <option value="Outdoors">Outdoors</option>
                    <option value="Electronics">Electronics</option>
                    <option value="Home Goods">Home Goods</option>
                    <option value="Automotive">Automotive</option>
                    <option value="Photography">Photography</option>
                    <option value="Party/Event">Party/Event</option>
                    <option value="Miscellaneous">Miscellaneous</option>
                </select>
            </div>

            <!-- PRICE RANGE -->
            <div class="sidebar-title">
                <label>Price Range:</label>
            </div>
            <div class="sidebar-entry">
                <input type="number" id="priceMin" placeholder="Min $" min="0">
                <input type="number" id="priceMax" placeholder="Max $" min="0">
            </div>

        </form>
    </aside>


    <!-- MAIN CONTENT - Listings -->
<section class="main-content">

    <div class="neon-title">
        <h1>Featured Listings</h1>

        <div style="margin-right: 80px;">

            <!-- if logged in, show add listing button -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <button onclick="window.location.href='/rent.it/listing/create_listing.php'">
                Add New Listing
            </button>
        </div>
        <?php endif; ?>
    </div>

        <div class="outer-grid-border">

            <div class="card-container">

                <?php if ($result->num_rows > 0): ?>

                    <!-- Loop through listings -->
                    <?php while ($row = $result->fetch_assoc()): ?>

                        <?php
                        // Resolve image path using helper
                        $img = resolve_image_path($row['image_path'], __DIR__);
                        ?>

                        <div class="card"
                            data-title="<?= strtolower(sanitize($row['title'])); ?>"
                            data-description="<?= strtolower(sanitize($row['description'])) ?>"
                            data-category="<?= sanitize($row['category']) ?>"
                            data-price="<?= (int)$row['price_per_day'] ?>">

                            <div class="card-header">
                                <h3><?= sanitize($row['title']) ?></h3>                 <!-- $ /day -->
                                <span class="rating">$<?= number_format($row['price_per_day'], 2) ?>/day</span>
                            </div>

                            <img src="<?= sanitize($img) ?>" alt="Listing Image">

                            <p><?= nl2br(sanitize($row['description'])) ?></p>

                            <p class="meta">
                                <strong>Category:</strong> <?= sanitize($row['category']) ?><br>
                                <strong>Owner:</strong> <?= sanitize($row['owner_name']) ?><br>
                                <small>Listed on <?= sanitize($row['created_at']) ?></small>
                            </p>

                            <div class="actions">
                                <button onclick="window.location.href='/rent.it/requests/request_rental.php?item_id=<?= $row['id'] ?>'">
                                    Request Rental
                                </button>
                            </div>

                        </div>

                    <?php endwhile; ?>

                <?php else: ?>

                    <p class="no-listings">No listings available yet.</p>

                <?php endif; ?>

            </div>
        </div>

    </section>
</main>
<script src="/rent.it/scripts/filter.js"></script>

</body>
</html>
