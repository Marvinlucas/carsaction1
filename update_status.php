<?php
session_start();
  require_once 'include/config.php'; // Include your database configuration here

if (!isset($_SESSION['buyer_id'])) {
    // Handle authentication or redirect as needed
    exit();
}

if (isset($_POST['sellerId'])) {
    $buyerId = $_SESSION['buyer_id']; // Ensure consistent case
    $sellerId = $_POST['sellerId'];

  

    // Update the status to "read" in the messages table
    $updateQuery = "UPDATE messages SET status = 'read' WHERE sender_id = $sellerId AND receiver_id = $buyerId";
    $result = mysqli_query($conn, $updateQuery);

    if (!$result) {
        // Handle the update error
        echo "Error updating status: " . mysqli_error($conn);
    }
}
?>
