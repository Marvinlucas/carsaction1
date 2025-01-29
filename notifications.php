<?php
session_start();
require_once 'include/config.php'; // Include your database connection logic
require_once 'include/head.php'; 

// Check if the user is logged in as a buyer
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // User is not logged in as a buyer, redirect to the appropriate page
    header("Location: userLogin.php");
    exit();
}

// Initialize variables for error handling
$errorMsg = "";

// Establish a database connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Fetch notifications for the logged-in buyer, ordered by created_at in descending order
$buyer_id = $_SESSION["user_id"]; // Assuming you have a 'buyer_id' field in the notifications table
$sql = "SELECT id, message, status FROM notifications WHERE buyer_id = ? ORDER BY created_at DESC";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $buyer_id);
    $stmt->execute();

    // Check for errors in the SQL query execution
    if ($stmt->errno) {
        die("SQL Error: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $stmt->close();
    
    if ($result !== false) { // Check if $result is not false
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Notifications</title>
           
        </head>
        <body>
            <?php include('include/top_bar.php'); ?>
            
            <div class="container ">
                <h1 class="mt-5">Notifications</h1>
                <div class="list-group mt-3">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            // Display each notification message
                            echo '<div class="list-group-item">';
                            echo htmlspecialchars($row['message']);
                            echo '</div>';
                            
                            // Check if the status is "unread" and update it to "read"
                            if ($row['status'] === 'unread') {
                                $notification_id = $row['id'];
                                $update_sql = "UPDATE notifications SET status = 'read' WHERE id = ?";
                                if ($update_stmt = $conn->prepare($update_sql)) {
                                    $update_stmt->bind_param("i", $notification_id);
                                    $update_stmt->execute();
                                    $update_stmt->close();
                                }
                            }
                        }
                    } else {
                        echo '<div class="alert alert-info mt-3">No notifications found.</div>';
                    }
                    ?>
                </div>
            </div>

            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
             
        </body>
        </html>
        <?php
    } else {
        // Handle the case where $result is false, e.g., display an error message
        echo '<div class="alert alert-danger mt-3">An error occurred while fetching notifications.</div>';
    }
} else {
    // Handle the case where the SQL query preparation failed, e.g., display an error message
    echo '<div class="alert alert-danger mt-3">An error occurred while preparing the SQL query.</div>';
}

// Close the database connection
mysqli_close($conn);
?>


