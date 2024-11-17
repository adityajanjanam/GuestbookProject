<!-- Profile Page (profile.php) -->
<?php include 'db.php'; session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
</head>
<body class="container mt-4">
    <h2>Your Profile</h2>

    <?php
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }

    $username = $_SESSION['username'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo '<div class="card mb-3">';
        echo '<div class="card-body">';
        echo '<p><strong>Username:</strong> ' . htmlspecialchars($user['username']) . '</p>';
        echo '<p><strong>Email:</strong> ' . htmlspecialchars($user['email']) . '</p>';
        echo '<p><strong>Member since:</strong> ' . $user['date_created'] . '</p>';
        echo '<p><strong>Role:</strong> ' . htmlspecialchars($user['role']) . '</p>';
        echo '</div>';
        echo '</div>';

        // Profile Picture Section
        if (!empty($user['avatar'])) {
            echo '<img src="' . htmlspecialchars($user['avatar']) . '" alt="Profile Picture" class="img-thumbnail mb-3" width="150">';
        } else {
            echo '<p>No profile picture uploaded.</p>';
        }

        // Profile Picture Upload Form
        echo '<form action="profile.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="avatar" class="form-label">Upload Profile Picture</label>
                    <input type="file" name="avatar" id="avatar" class="form-control">
                </div>
                <button type="submit" name="upload_avatar" class="btn btn-primary">Upload</button>
              </form>';
    } else {
        echo '<div class="alert alert-danger">User not found.</div>';
    }

    // Handle Profile Picture Upload
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['upload_avatar'])) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES["avatar"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $validExtensions = ["jpg", "jpeg", "png", "gif"];

        // Check if file is an image and has a valid extension
        if (in_array($imageFileType, $validExtensions)) {
            if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $targetFile)) {
                $stmt = $conn->prepare("UPDATE users SET avatar = ? WHERE username = ?");
                $stmt->bind_param("ss", $targetFile, $_SESSION['username']);
                $stmt->execute();
                echo '<div class="alert alert-success">Profile picture updated successfully.</div>';
                header("Refresh:0");
            } else {
                echo '<div class="alert alert-danger">Error uploading file.</div>';
            }
        } else {
            echo '<div class="alert alert-danger">Invalid file type. Only JPG, JPEG, PNG & GIF are allowed.</div>';
        }
    }
    ?>

    <a href="index.php" class="btn btn-secondary">Back to Guestbook</a>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
