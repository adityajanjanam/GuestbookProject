<?php
include 'db.php';
session_start();

if (isset($_SESSION['username']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM guestbook WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: index.php");
exit();
?>
