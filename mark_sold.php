<?php
session_start();
require_once 'include/config.php';

// Check if the user is logged in
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    $username = $_SESSION["username"];
} else {
    // User is not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the car ID and sold price from the form
    $buyer_name = $_POST["buyer_name"];
    $car_id = $_POST["carId"];
    $soldPrice = $_POST['soldPrice'];

    // Fetch seller ID based on the logged-in username
    $stmt = $conn->prepare("SELECT user_id FROM user WHERE username = ?");
    if (!$stmt) {
        die("Error preparing query: " . $conn->error);
    }

    $stmt->bind_param("s", $username);

    if (!$stmt->execute()) {
        die("Error executing query: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $user_id = $row["user_id"];

// Update the car status as sold and set the sale date
$stmt = $conn->prepare("UPDATE sellcars SET sold = 1, buyer_name = ?, sold_price = ?, date_sold = NOW() WHERE car_id = ? AND sellers_id = ?");
if (!$stmt) {
    die("Error preparing query: " . $conn->error);
}

$stmt->bind_param("sidi",$buyer_name, $soldPrice, $car_id, $user_id);

if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
}


    // Redirect back to the car listing page
    header("Location: userCarlist.php");
    exit();
} else {
    // Form is not submitted, redirect back to the car listing page
    header("Location: userCarlist.php");
    exit();
}
?>
