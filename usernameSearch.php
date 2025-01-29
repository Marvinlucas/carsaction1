<?php
session_start();
require_once 'include/config.php';
require_once 'include/head.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Find Username</title>
    <!-- Include Bootstrap CSS -->
   
</head>
<body>

<?php include('include/top_bar_rev.php'); ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
        	<div class="card">
                    <div class="card-header">
            <h2 class="text-center">Find Username</h2>
                </div>
            <form method="post" class="mt-4">
                <div class="mb-3" style="margin-left: 10px;">

                	<i> You can find your username using your email account.</i><br><br>
                    <label for="email" class="form-label">Enter your email:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="Find" style="text-align: center; ">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
            </form>

            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Check if the form has been submitted

                // Retrieve the email entered by the user
                $email = $_POST['email'];

                // Perform a database query to find the username associated with the provided email
                $query = "SELECT username FROM user WHERE email = '$email'";
                // Execute the query and fetch the result
                $result = mysqli_query($conn, $query);

                if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    if ($row) {
                        $username = $row['username'];
                        echo '<div class="alert alert-success mt-3" role="alert">Your username is: ' . $username . '</div>';
                    } else {
                        echo '<div class="alert alert-danger mt-3" role="alert">No username found for this email.</div>';
                    }
                } else {
                    echo '<div class="alert alert-danger mt-3" role="alert">Error querying the database.</div>';
                }

                // Close the database connection here
            }
            ?>
            <a href="userLogin.php">Back to Login page</a>
        </div>
    </div>
</div>
</div>

<!-- Include Bootstrap JS (optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.5.0/dist/js/bootstrap.min.js"></script>
  <?php include('include/footer.php'); ?>
</body>
</html>
