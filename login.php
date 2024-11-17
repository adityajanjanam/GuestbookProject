<?php 
include 'db.php'; 
session_start(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
</head>
<body class="container mt-4">
    <h2>Login</h2>
    <form action="login.php" method="POST" class="mb-4">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" id="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <button type="submit" name="login" class="btn btn-primary">Login</button>
    </form>

    <?php
    // CSRF token generation
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            die("<div class='alert alert-danger'>Invalid CSRF token</div>");
        }

        $username = htmlspecialchars($_POST['username']);
        $password = $_POST['password'];

        // Error handling using try-catch
        try {
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            if ($stmt === false) {
                throw new Exception("Error preparing statement: " . $conn->error);
            }
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    // Successful login
                    session_regenerate_id(true); // Prevent session fixation
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    header("Location: index.php");
                    exit();
                } else {
                    echo '<div class="alert alert-danger">Invalid password.</div>';
                }
            } else {
                echo '<div class="alert alert-danger">User not found.</div>';
            }
            $stmt->close();
        } catch (Exception $e) {
            echo '<div class="alert alert-danger">' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    }

    $conn->close();
    ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
