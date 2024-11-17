<?php include 'db.php'; session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <script>
        function validatePassword() {
            const password = document.getElementById("password").value;
            if (password.length < 8) {
                alert("Password must be at least 8 characters long.");
                return false;
            }
            if (!/[A-Z]/.test(password)) {
                alert("Password must contain at least one uppercase letter.");
                return false;
            }
            if (!/[a-z]/.test(password)) {
                alert("Password must contain at least one lowercase letter.");
                return false;
            }
            if (!/[0-9]/.test(password)) {
                alert("Password must contain at least one digit.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body class="container mt-4">
    <h2>Register</h2>
    <form action="register.php" method="POST" class="mb-4" onsubmit="return validatePassword()">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" id="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <button type="submit" name="register" class="btn btn-primary">Register</button>
    </form>

    <?php
    // CSRF token generation
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            die("Invalid CSRF token");
        }

        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        try {
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $password);
            if ($stmt->execute()) {
                echo '<div class="alert alert-success">Registration successful. <a href="login.php">Login here</a>.</div>';
            } else {
                throw new Exception("Registration failed: " . $stmt->error);
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
