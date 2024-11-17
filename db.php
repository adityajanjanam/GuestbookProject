<?php
// db.php

$servername = "localhost";
$username = "root";
$password = ""; // Default XAMPP password is empty
$database = "guestbook_db";

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
