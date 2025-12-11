<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header class="site-header">
    <nav class="nav-bar">

        <!-- LEFT SIDE NAVIGATION -->
        <div class="nav-left">
            <a href="/rent.it/index.php" class="nav-logo">Rent.it</a>
            <a href="/rent.it/index.php" class="nav-link">Browse</a>

            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/rent.it/listing/create_listing.php" class="nav-link">Add Listing</a>
                <a href="/rent.it/listing/my_listings.php" class="nav-link">My Listings</a>

                <a href="/rent.it/requests/owner_requests.php" class="nav-link">Incoming Requests</a>
                <a href="/rent.it/requests/my_requests.php" class="nav-link">My Requests</a>

                <a href="/rent.it/account/profile.php" class="nav-link">My Profile</a>
            <?php endif; ?>
        </div>

        <!-- RIGHT SIDE NAVIGATION -->
        <div class="nav-right">
            <?php if (!isset($_SESSION['user_id'])): ?>

                <a href="/rent.it/account/register.php" class="nav-link">Register</a>
                <a href="/rent.it/account/login.php" class="nav-link">Login</a>

            <?php else: ?>

                <span class="nav-username">
                    Welcome, <?= htmlspecialchars($_SESSION['username']); ?>
                </span>

                <a href="/rent.it/account/logout.php" class="nav-link logout-link">Logout</a>
            <?php endif; ?>
        </div>

    </nav>
</header>

<?php include __DIR__ . "/notification.php"; ?>
