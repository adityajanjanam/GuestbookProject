<?php include 'db.php'; session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guestbook</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
</head>
<body class="container mt-4">
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <a class="navbar-brand" href="index.php">Guestbook</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <?php if (isset($_SESSION['username'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <h1>Guestbook</h1>

    <?php if (isset($_SESSION['username'])): ?>
        <form action="index.php" method="POST" class="mb-4">
            <div class="mb-3">
                <label for="message" class="form-label">Your Message</label>
                <textarea name="message" id="message" class="form-control" required></textarea>
            </div>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>
    <?php else: ?>
        <p>Please <a href="login.php">log in</a> OR <a href="register.php">Sign Up </a> to leave a message.</p>
    <?php endif; ?>

    <?php
    // CSRF token generation
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            die("Invalid CSRF token");
        }

        $username = $_SESSION['username'];
        $message = htmlspecialchars($_POST['message']);

        // Error handling with try...catch
        try {
            $stmt = $conn->prepare("INSERT INTO guestbook (username, message) VALUES (?, ?)");
            if ($stmt === false) {
                throw new Exception("Error preparing statement: " . $conn->error);
            }
            $stmt->bind_param("ss", $username, $message);
            $stmt->execute();
            $stmt->close();

            // Redirect to clear the form and show new entry
            header("Location: index.php");
            exit();
        } catch (Exception $e) {
            echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    }

    // Retrieve and display all guestbook entries
    $result = $conn->query("SELECT * FROM guestbook ORDER BY date_posted DESC");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="card mb-3">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . htmlspecialchars($row['username']) . '</h5>';
            echo '<p class="card-text">' . htmlspecialchars($row['message']) . '</p>';
            echo '<p class="card-text"><small class="text-muted">Posted on ' . $row['date_posted'] . '</small></p>';
            if (isset($_SESSION['username']) && ($_SESSION['username'] == $row['username'] || $_SESSION['role'] == 'admin')) {
                echo '<a href="edit.php?id=' . $row['id'] . '" class="btn btn-warning btn-sm">Edit</a> ';
                echo '<a href="delete.php?id=' . $row['id'] . '" class="btn btn-danger btn-sm">Delete</a>';
            }
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo '<p>No messages yet. Be the first to leave one!</p>';
    }

    $conn->close();
    ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
