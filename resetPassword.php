<?php
session_start();
require_once 'include/config.php';
require_once 'include/head.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token exists in the 'password_reset' table
    $sql = "SELECT * FROM password_reset WHERE token = '$token'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Token is valid, display the password reset form
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $newPassword = $_POST["new_password"];
            $confirmPassword = $_POST["confirm_password"];

            // Validate password (you can add more validation)
            if (strlen($newPassword) < 8) {
                echo "Password must be at least 8 characters long.";
            } elseif ($newPassword !== $confirmPassword) {
                echo "Passwords do not match.";
            } else {
                // Hash the new password
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                // Get the email associated with the token
                $row = $result->fetch_assoc();
                $email = $row["email"];

                // Update the user's password in the 'user' table
                $updateSql = "UPDATE user SET password = '$hashedPassword' WHERE email = '$email'";
                if ($conn->query($updateSql) === TRUE) {
                    // Delete the used token from the 'password_reset' table
                    $deleteSql = "DELETE FROM password_reset WHERE token = '$token'";
                    $conn->query($deleteSql);

                    echo "Password updated successfully.";
                } else {
                    echo "Error updating password: " . $conn->error;
                }
            }
        }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <!-- Add Bootstrap CSS link here -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Reset Password</h2>
                    </div>
                    <div class="card-body">
                        <form method="post" action="">
                            <div class="form-group">
                                <label for="new_password">New Password:</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" required>
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Confirm Password:</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Reset Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Bootstrap JS and jQuery links at the end of the body -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

      <?php include('include/footer.php'); ?>
</body>
</html>
<?php
    } else {
        echo "Invalid or expired token.";
    }
} else {
    echo "Token not provided.";
}
?>
