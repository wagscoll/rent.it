<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!empty($_SESSION['flash_message'])):
    $type = $_SESSION['flash_type'] ?? "info";
?>
    <div class="flash-message <?= htmlspecialchars($type) ?>">
        <?= htmlspecialchars($_SESSION['flash_message']) ?>
    </div>

<?php
    unset($_SESSION['flash_message'], $_SESSION['flash_type']);
endif;
?>
