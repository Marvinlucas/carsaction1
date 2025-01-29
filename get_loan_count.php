<?php
session_start();
require_once 'include/config.php';

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    $sellerId = $_SESSION["user_id"];
} else {
    // User is not logged in as a seller, redirect to the appropriate page
    header("Location: login_dashboard.php");
    exit();
}

try {
    // Perform the database query to count pending loans
    $sql = "SELECT COUNT(*) AS count FROM loans WHERE sellerId = :sellerId AND status = 'pending'";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':sellerId', $sellerId, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch the count
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Return the count as JSON
    header('Content-Type: application/json');
    echo json_encode($result);
} catch (PDOException $e) {
    // Handle database errors gracefully
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Database error']);
}
?>
