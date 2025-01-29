<?php
session_start();
require_once 'include/config.php';
require_once 'include/head.php';
// Check if the user is logged in
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    $username = $_SESSION["username"];
} else {
    // User is not logged in, redirect to the login page
    header("Location: login_dashboard.php");
    exit();
}

// Fetch available cars from the database

?>


<!DOCTYPE html>
<html>

<head>
    <title>CarSaction</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>

<body>
    <?php include('include/navbar_admin.php'); ?>



    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <?php include('include/footer.php'); ?>
</body>

</html>