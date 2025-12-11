<?php

// SESSION 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// DATABASE CONNECTION
$db_host = "localhost";
$db_user = "root";
$db_pass = "";       
$db_name = "rentit";

// Connect to MySQL
$conn = new mysqli($db_host, $db_user, $db_pass);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Create db if it doesn't exist
if (!$conn->query("CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")) {
    die("Failed creating database `$db_name`: " . $conn->error);
}

$conn->select_db($db_name);
$conn->set_charset("utf8mb4");


// USERS TABLE
// id, email, username, password_hash, failed_attempts, last_failed_at, created_at
$createUsersTableSQL = "
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL UNIQUE,
        username VARCHAR(100) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        failed_attempts INT DEFAULT 0,
        last_failed_at TIMESTAMP NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

// safety check to create users table
if (!$conn->query($createUsersTableSQL)) {
    die("Error creating users table: " . $conn->error);
}


// Safe Column Helper
function ensureColumnExists($conn, $table, $definition)
{
    $parts = explode(' ', trim($definition), 2);
    $column = $parts[0];

    $result = $conn->query("SHOW COLUMNS FROM `$table` LIKE '$column'");

    if ($result && $result->num_rows === 0) {

        // if column missing, add it
        if (!$conn->query("ALTER TABLE `$table` ADD $definition")) {
            die("Failed adding column $column: " . $conn->error);
        }
    }
}

// if columns missing, add them, allows for integration of pw failure tracking, not yet implemented
ensureColumnExists($conn, "users", "failed_attempts INT DEFAULT 0");
ensureColumnExists($conn, "users", "last_failed_at TIMESTAMP NULL");

?>