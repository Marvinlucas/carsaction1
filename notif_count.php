<?php
session_start();
require_once 'include/config.php'; // Include your database connection logic

// Check if the user is logged in, and get the buyer_id from the session
if (!isset($_SESSION['user_id'])) {
    die("User is not logged in.");
}

$buyer_id = $_SESSION['user_id']; // Update this as per your session management

// Use prepared statements to prevent SQL injection
$query = "SELECT COUNT(*) AS notification_count FROM notifications WHERE buyer_id = ? AND status = 'unread'";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $buyer_id);

// Execute the prepared statement
if (mysqli_stmt_execute($stmt)) {
    // Bind the result
    mysqli_stmt_bind_result($stmt, $notification_count);

    // Fetch the result
    mysqli_stmt_fetch($stmt);

    // Return the notification count as a JSON response
    $response = array('count' => $notification_count);
    echo json_encode($response);
} else {
    die("Query failed: " . mysqli_error($conn));
}

// Close the prepared statement and database connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
