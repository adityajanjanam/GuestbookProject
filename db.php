<?php
// db.php

// Use environment variables for database configuration
$servername = getenv('DB_HOST') ?: 'localhost';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: ''; // Default XAMPP password is empty
$database = getenv('DB_NAME') ?: 'guestbook_db';

$conn = new mysqli($servername, $username, $password, $database);

// Check connection with error handling
try {
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die("<div class='alert alert-danger'>" . htmlspecialchars($e->getMessage()) . "</div>");
}
?>
