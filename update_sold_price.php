<?php
session_start();
require_once 'include/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the user is logged in
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        $username = $_SESSION["username"];
    } else {
        // User is not logged in, return an error response
        http_response_code(401); // Unauthorized
        echo json_encode(array("message" => "User not logged in"));
        exit();
    }

    // Retrieve car ID and sold price from POST data
    $carId = $_POST['carId'];
    $soldPrice = $_POST['soldPrice'];

    // Prepare and execute an SQL UPDATE statement
    $stmt = $conn->prepare("UPDATE sellcars SET sold_price = ? WHERE car_id = ?");
    if (!$stmt) {
        http_response_code(500); // Internal Server Error
        echo json_encode(array("message" => "Error preparing query: " . $conn->error));
        exit();
    }

    $stmt->bind_param("di", $soldPrice, $carId);
    if (!$stmt->execute()) {
        http_response_code(500); // Internal Server Error
        echo json_encode(array("message" => "Error executing query: " . $stmt->error));
        exit();
    }

    // Close the database connection
    $stmt->close();
    $conn->close();

    // Return a success response
    http_response_code(200); // OK
    echo json_encode(array("message" => "Car marked as sold successfully"));
} else {
    // Invalid request method, return an error response
    http_response_code(405); // Method Not Allowed
    echo json_encode(array("message" => "Invalid request method"));
}
?>
