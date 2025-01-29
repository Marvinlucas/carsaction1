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

// Check if the car ID is provided
if (isset($_GET["id"])) {
    $car_id = $_GET["id"];

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
    $seller_id = $row["user_id"];

    // Delete the car from the database
    $stmt = $conn->prepare("DELETE FROM sellcars WHERE car_id = ? AND sellers_id = ?");
    if (!$stmt) {
        die("Error preparing query: " . $conn->error);
    }

    $stmt->bind_param("ii", $car_id, $seller_id);

    if (!$stmt->execute()) {
        die("Error executing query: " . $stmt->error);
    }

    // Redirect back to the car listing page
    header("Location: userCarlist.php");
    exit();
} else {
    // Car ID is not provided, redirect back to the car listing page
    header("Location: userIndex.php");
    exit();
}
?>
