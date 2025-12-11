<?php
require __DIR__ . "/../db/config.php";
require __DIR__ . "/../utils/helpers.php";
include __DIR__ . "/../templates/notification.php";

require_login();

$owner_id = $_SESSION['user_id'];

// Fetch rental requests sent *to* this owner
$stmt = $conn->prepare("
    SELECT r.id, r.message, r.status, r.created_at,
           l.title,
           u.username AS renter_name
    FROM rental_requests r
    INNER JOIN listings l ON r.item_id = l.id
    INNER JOIN users u ON r.renter_id = u.id
    WHERE r.owner_id = ?
    ORDER BY r.created_at DESC
");
$stmt->bind_param("i", $owner_id);
$stmt->execute();
$requests = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Incoming Rental Requests - Rent.it</title>
    <link rel="stylesheet" href="/rent.it/styles/pages.css">
    <link rel="stylesheet" href="/rent.it/styles/main.css">
    <link rel="stylesheet" href="/rent.it/styles/card.css">
</head>

<body>

<?php include __DIR__ . "/../templates/header.php"; ?>
<?php include __DIR__ . "/../templates/notification.php"; ?>

<div class="req-wrapper">

    <div class="neon-title"><h1>Incoming Rental Requests</h1></div>

    <?php if ($requests->num_rows === 0): ?>

        <div class="neon-title"><h2>You currently have no incoming rental requests.</h2></div>

    <?php else: ?>

        <?php while ($row = $requests->fetch_assoc()): ?>

            <?php
            $statusClass = "";
            if ($row['status'] === "pending") {
                $statusClass = "status-pending";
            }
            if ($row['status'] === "approved") {
                $statusClass = "status-approved";
            }
            if ($row['status'] === "denied") {
                $statusClass = "status-denied";
            }
            ?>

            <div class="req-card">

                <h3><?= sanitize($row['title']); ?></h3>

                <p><strong>From:</strong> <?= sanitize($row['renter_name']); ?></p>

                <p>
                    <strong>Message:</strong><br>
                    <?= nl2br(sanitize($row['message'])); ?>
                </p>

                <p class="status <?= $statusClass; ?>">
                    Status: <?= sanitize($row['status']); ?>
                </p>

                <!-- Action Buttons -->
                <?php if ($row['status'] === "pending"): ?>
                        <!-- Approve Button -->
                    <form action="/rent.it/requests/update_request_status.php" method="POST" style="display:inline;">
                        <input type="hidden" name="request_id" value="<?= $row['id']; ?>">
                        <input type="hidden" name="new_status" value="approved">
                        <button class="action-btn approve-btn" type="submit">Approve</button>
                    </form>

                        <!-- Deny Button -->
                    <form action="/rent.it/requests/update_request_status.php" method="POST" style="display:inline;">
                        <input type="hidden" name="request_id" value="<?= $row['id']; ?>">
                        <input type="hidden" name="new_status" value="denied">
                        <button class="action-btn deny-btn" type="submit">Deny</button>
                    </form>
                <?php endif; ?>

                        <!-- Delete Button -->
                <form action="/rent.it/requests/delete_request.php" method="POST" style="display:inline;">
                    <input type="hidden" name="request_id" value="<?= $row['id']; ?>">
                    <button class="action-btn delete-btn" type="submit">Delete Request</button>
                </form>

                        <!-- Timestamp -->
                <small style="color:#bbb; display:block; margin-top:12px;">
                    Received on <?= sanitize($row['created_at']); ?>
                </small>
            </div>

        <?php endwhile; ?>
    <?php endif; ?>

</div>

</body>
</html>
