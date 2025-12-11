<?php
require __DIR__ . "/../db/config.php";
require __DIR__ . "/../utils/helpers.php";
include __DIR__ . "/../templates/notification.php";

require_login();

$renter_id = $_SESSION['user_id'];

// Fetch rental requests made by this user
$stmt = $conn->prepare("
    SELECT r.id, r.message, r.status, r.created_at,
           l.title,
           u.username AS owner_name
    FROM rental_requests r
    INNER JOIN listings l ON r.item_id = l.id 
    INNER JOIN users u ON r.owner_id = u.id
    WHERE r.renter_id = ?
    ORDER BY r.created_at DESC
");

$stmt->bind_param("i", $renter_id);
$stmt->execute();
$requests = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Rental Requests - Rent.it</title>

    <link rel="stylesheet" href="/rent.it/styles/pages.css">
    <link rel="stylesheet" href="/rent.it/styles/main.css">
    <link rel="stylesheet" href="/rent.it/styles/card.css">
</head>

<body>

<?php include __DIR__ . "/../templates/header.php"; ?>
<?php include __DIR__ . "/../templates/notification.php"; ?>

<div class="req-wrapper">

    <div class="neon-title"><h1>My Rental Requests</h1></div>

    <?php if ($requests->num_rows === 0): ?> <!-- No Requests -->

        <p class="neon-title">
            You haven’t submitted any rental requests yet.
        </p>

    <?php else: ?> <!-- Has Requests -->

        <?php while ($row = $requests->fetch_assoc()): ?>   <!-- Loop requests -->

            <?php
            $status_class = "";
            if ($row['status'] === "pending") {
                $status_class = "status-pending";
            }
            if ($row['status'] === "approved") {
                $status_class = "status-approved";
            }
            if ($row['status'] === "denied") {
                $status_class = "status-denied";
            }
            ?>

            <div class="req-card">

                <h3><?= sanitize($row['title']); ?></h3>

                <p><strong>Owner:</strong> <?= sanitize($row['owner_name']); ?></p>

                <p>
                    <strong>Message:</strong><br>
                    <?= nl2br(sanitize($row['message'])); ?>
                </p>

                <p class="status <?= $status_class; ?>">
                    Status: <?= sanitize(ucfirst($row['status'])); ?>
                </p>

                <form action="/rent.it/requests/delete_request.php" method="POST">
                    <input type="hidden" name="request_id" value="<?= $row['id']; ?>">
                    <button type="submit" class="delete-btn">Delete Request</button>
                </form>

                <small style="color:#bbb;">
                    Submitted on <?= sanitize($row['created_at']); ?>
                </small>

            </div>

        <?php endwhile; ?>

    <?php endif; ?>

</div>

</body>
</html>
