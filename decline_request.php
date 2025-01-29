<?php
session_start();
require_once 'include/config.php'; // Include your database connection logic

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    $sellerId = $_SESSION["user_id"];
} else {
    // User is not logged in as a seller, redirect to the appropriate page
    header("Location: login_dashboard.php");
    exit();
}

// Check if the "action" parameter is set to "decline" and if "id" is provided in the URL
if (isset($_GET["action"]) && $_GET["action"] === "decline" && isset($_GET["id"])) {
    $loanId = $_GET["id"];

    // Update the status in the loans table to "declined"
    $sql = "UPDATE loans SET status = 'rejected' WHERE id = ? AND sellerId = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $loanId, $sellerId);
        if ($stmt->execute()) {
            // Redirect back to the seller dashboard after declining the loan
            header("Location: loanApproval.php");
            exit();
        } else {
            // Handle SQL execution error
            echo "Error executing SQL query: " . $conn->error;
        }
        $stmt->close();
    } else {
        // Handle SQL statement preparation error
        echo "Error preparing SQL statement: " . $conn->error;
    }
}
?>
