<?php
session_start();
require_once 'include/config.php';
require_once 'include/head.php';

// Check if the admin is already logged in
if (isset($_SESSION["adminloggedin"]) && $_SESSION["adminloggedin"] === true) {
    // Admin is logged in, redirect to the admin dashboard
    header("Location: admin_dashboard.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate Admin credentials
    $admin = validateAdmin($conn, $username, $password);

    if ($admin) {
        // Verify the hashed password
        $hashedPassword = $admin['admin_password'];

        if (password_verify($password, $hashedPassword)) {
            // Password is correct, login successful, set session variables
            $_SESSION["adminloggedin"] = true;
            $_SESSION["username"] = $username;
            $_SESSION["admin_id"] = $admin['admin_id'];

            // Redirect to the home page or dashboard
            header("Location: admin_dashboard.php");
            exit();
        } else {
            // Invalid password, display error message
            echo "Invalid username or password.";
        }
    } else {
        // Invalid username, display error message
        echo "Invalid username or password.";
    }
}
?>



<!-- Rest of your HTML code remains the same -->

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        .register-link {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card mt-5">
            <div class="card-body">
                <h1 class="card-title">Admin Login</h1>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="mt-4">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" name="username" id="username" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <button type="submit" class="btn btn-primary btn-block btn-lg">Login</button>
                    </div>
                    <p class="register-link text-center">Don't have an account? <a href="addAdmin.php">Register</a></p>
                </form>
            </div>
        </div>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>
