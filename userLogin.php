<?php
session_start();
require_once 'include/config.php';
require_once 'include/head.php';


?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
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
                <h1 class="card-title">User Login</h1>
                <?php
                   // Check if the user's account is banned
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user_query = "SELECT * FROM user WHERE username = ?";

    $user_stmt = mysqli_prepare($conn, $user_query);
    if (!$user_stmt) {
        die("Error in user prepared statement: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($user_stmt, "s", $username);
    mysqli_stmt_execute($user_stmt);

    $user_result = mysqli_stmt_get_result($user_stmt);

    // Check if the user exists
    if ($user_row = mysqli_fetch_assoc($user_result)) {
        $storedPasswordHash = $user_row['password']; // Get the hashed password from the database

        // Check if the entered password matches the hashed password
        if (password_verify($password, $storedPasswordHash)) {
            // Check if the user's account is banned
           if ($user_row['status'] == "Denied") {
                    echo '<div class="alert alert-danger" role="alert">Your account was declined by admin.</div>';
                } elseif ($user_row['status'] == "Approved" && $user_row['approved'] == 0) {
                    echo '<div class="alert alert-danger" role="alert">Your account has been banned.</div>';
                } elseif ($user_row['status'] == "pending") {
                    echo '<div class="alert alert-warning" role="alert">Your account is in the Pending List. Please wait for approval from the admin.</div>';
                } else {
                // Login successful, set session variables
                $_SESSION["loggedin"] = true;
                $_SESSION["username"] = $username;
                $_SESSION["user_id"] = $user_row['user_id'];
                $_SESSION["firstname"] = $user_row['firstname'];
                $_SESSION["lastname"] = $user_row['lastname'];

                if ($user_row['status'] == "Approved" && $user_row['approved'] == 1) {
                    // Redirect to the appropriate index page if the account is approved
                    header("Location: userIndex.php");
                    exit();
                }
            }
        } else {
            echo '<div class="alert alert-danger" role="alert">Incorrect Username or Password!</div>';
        }
    } else {
        echo '<div class="alert alert-danger" role="alert">User not found!</div>';
    }
}
                ?>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="mt-4">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" name="username" id="username" class="form-control" required>
                        <a href="usernameSearch.php">find your username</a>
                    </div>

                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                          <a href="forgetPassword.php">Forget Password</a>                        
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <button type="submit" class="btn btn-primary btn-block btn-lg">Login</button>
                        </div>
                        <div class="col-md-6">
                           <a href="usersRegistration.php" class="btn btn-block btn-lg" style="background-color: darkgray;">Register</a>
                        </div>
                    </div>
                    <div class="form-group" style="text-align: center;">
                        <a href="index.php">Back to Homepage</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
      <?php include('include/footer.php'); ?>
</body>
</html>
