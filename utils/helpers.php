<?php

// sanitize output to prevent < > " ' symbols from breaking HTML
function sanitize($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Checks if $_SESSION['user_id'] exists. If not, redirects to login page.
function require_login() {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['flash_message'] = "Please log in to continue.";
        $_SESSION['flash_type'] = "error";
        header("Location: /rent.it/account/login.php");
        exit;
    }
}

//Binds the user ID, executes, returns the row as an associative array.
// implemented from: https://www.w3schools.com/php/func_mysqli_fetch_assoc.asp
// Returns null if user not found
function fetch_user_password_hash($conn, $user_id) {
    $stmt = $conn->prepare("SELECT password_hash FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Updates the user's password hash
function update_user_password($conn, $user_id, $hash) {
    $stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
    $stmt->bind_param("si", $hash, $user_id);
    return $stmt->execute();
}

// Ensures email is unique, returns user row if exists
function email_exists($conn, $email) {
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Stores reset token and expiry timestamp for user, timestamp expiration not tested
function store_reset_token($conn, $user_id, $token, $expires) {
    $stmt = $conn->prepare("
        UPDATE users
        SET reset_token = ?, reset_expires = ?
        WHERE id = ?
    ");
    $stmt->bind_param("ssi", $token, $expires, $user_id);
    return $stmt->execute();
}

//Binds the username, executes, returns the row (or null).
function find_user_by_username($conn, $username) {
    $stmt = $conn->prepare("
        SELECT id, username, password_hash
        FROM users
        WHERE username = ?
        LIMIT 1
    ");

    if (!$stmt) {
        return null;
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// fetch user details by ID
function get_user_by_id($conn, $user_id) {
    $stmt = $conn->prepare("
        SELECT username, email, created_at
        FROM users
        WHERE id = ?
        LIMIT 1
    ");

    if (!$stmt) {
        return null;
    }

    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}


function username_exists($conn, $username) {
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// fetch user details by reset token for password reset
function get_user_by_reset_token($conn, $token) {
    $stmt = $conn->prepare("
        SELECT id, reset_expires
        FROM users
        WHERE reset_token = ?
        LIMIT 1
    ");

    if (!$stmt) {
        return null;
    }

    $stmt->bind_param("s", $token);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// removes reset token and expiry from user record
function clear_reset_token($conn, $user_id) {
    $stmt = $conn->prepare("
        UPDATE users
        SET reset_token = NULL, reset_expires = NULL
        WHERE id = ?
    ");
    $stmt->bind_param("i", $user_id);
    return $stmt->execute();
}

// used in loop to verify listing ownership
function get_listing_owned_by_user($conn, $listing_id, $user_id) {
    $stmt = $conn->prepare("
        SELECT id, title, image_path
        FROM listings
        WHERE id = ? AND owner_id = ?
        LIMIT 1
    ");

    if (!$stmt) {
        return null;
    }

    $stmt->bind_param("ii", $listing_id, $user_id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

// if user is owner or renter of request, allow deletion
function user_can_delete_request($conn, $request_id, $user_id) {
    $stmt = $conn->prepare("
        SELECT id
        FROM rental_requests
        WHERE id = ?
        AND (renter_id = ? OR owner_id = ?)
        LIMIT 1
    ");

    if (!$stmt) {
        return false;
    }

    $stmt->bind_param("iii", $request_id, $user_id, $user_id);
    $stmt->execute();

    return (bool) $stmt->get_result()->fetch_assoc();
}

// Fetch listing details for editing, ensuring ownership
function get_editable_listing($conn, $listing_id, $user_id) {
    $stmt = $conn->prepare("
        SELECT id, title, description, category, price_per_day,
               item_condition, deposit, availability
        FROM listings
        WHERE id = ? AND owner_id = ?
        LIMIT 1
    ");

    if (!$stmt) {
        return null;
    }

    $stmt->bind_param("ii", $listing_id, $user_id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

// Returns the image URL or placeholder if not exists
function get_image_url($row) {
    if (!empty($row['image_path'])) {
        $local = __DIR__ . '/../' . $row['image_path'];
        if (file_exists($local)) {
            return "/rent.it/" . $row['image_path'];
        }
    }
    return "/rent.it/images/placeholder.png";
}

// Handles image upload, returns path or null
function upload_image($file, &$errors)
{
    if (!$file || empty($file['name'])) { // exists
        return null;
    }

    $uploadDir = __DIR__ . "/../uploads/";

    if (!is_dir($uploadDir)) { // create directory if missing
        mkdir($uploadDir, 0777, true); // read/write/execute perms for all
    }

    $filename = time() . "_" . basename($file['name']); // filename formatting
    $targetFile = $uploadDir . $filename;

    if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
        $errors[] = "Image upload failed.";
        return null;
    }

    return "uploads/" . $filename; // relative path for location reference in DB storage
}

// Resolves image path, returns placeholder if missing
function resolve_image_path($path, $root)
{
    if ($path === null || $path === "") {
        return "/rent.it/images/placeholder.png";
    }

    $full = $root . "/" . $path;

    if (file_exists($full)) {
        return "/rent.it/" . $path;
    }

    return "/rent.it/images/placeholder.png";
}






