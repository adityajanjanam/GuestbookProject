<?php include 'db.php'; session_start(); ?>

<!-- Edit Page (edit.php) -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Message</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
</head>
<body class="container mt-4">
    <h2>Edit Message</h2>

    <?php
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }

    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $stmt = $conn->prepare("SELECT * FROM guestbook WHERE id = ? AND username = ?");
        $stmt->bind_param("is", $id, $_SESSION['username']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $message = $row['message'];
        } else {
            echo '<div class="alert alert-danger">Message not found or you do not have permission to edit this message.</div>';
            exit();
        }
    } else {
        echo '<div class="alert alert-danger">Invalid request.</div>';
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
        $updated_message = htmlspecialchars($_POST['message']);

        $stmt = $conn->prepare("UPDATE guestbook SET message = ? WHERE id = ? AND username = ?");
        $stmt->bind_param("sis", $updated_message, $id, $_SESSION['username']);
        $stmt->execute();

        header("Location: index.php");
        exit();
    }
    ?>

    <form action="edit.php?id=<?php echo $id; ?>" method="POST">
        <div class="mb-3">
            <label for="message" class="form-label">Edit Your Message</label>
            <textarea name="message" id="message" class="form-control" required><?php echo htmlspecialchars($message); ?></textarea>
        </div>
        <button type="submit" name="update" class="btn btn-primary">Update</button>
    </form>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>