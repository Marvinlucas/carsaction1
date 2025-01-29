<?php
session_start();
require_once 'include/config.php';
require_once 'include/head.php';

// Check if the user is logged in
if (isset($_SESSION["adminloggedin"]) && $_SESSION["adminloggedin"] === true) {
    $username = $_SESSION["username"];
} else {
    // User is not logged in, redirect to the login page
    header("Location: login_admin.php");
    exit();
}

// Fetch available cars from the database
$queryCars = "SELECT COUNT(*) as car_count FROM sellcars";
$resultCars = mysqli_query($conn, $queryCars);
$carCount = mysqli_fetch_assoc($resultCars)['car_count'];

// Fetch user count from the database
$queryUsers = "SELECT COUNT(*) as user_count FROM user";
$resultUsers = mysqli_query($conn, $queryUsers);
$userCount = mysqli_fetch_assoc($resultUsers)['user_count'];


?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>
 
    <style type="text/css">
    <style type="text/css">
        .card {
            width: 200px;
            height: 200px;
            margin: 10px;

        }

        .card-body {
            text-align: center;
            background-color: #f8f9fa; /* Use a light background color */
            border-radius: 10px;
            padding: 20px;
        }

        .card-title {
            font-size: 18px;
            font-weight: bold;
        }

        .card-text {
            font-size: 24px; /* Increase font size for emphasis */
            margin-top: 10px;
        }

        .container {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <?php include('include/top_bar_admin.php'); ?>

    <div class="container mt-4">
        <div class="row">
              <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Users</h5>
                        <p class="card-text"><?php echo $userCount; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Available Cars Count</h5>
                        <p class="card-text"> <?php echo $carCount; ?></p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <?php include('include/footer.php'); ?>
</body>

</html>
