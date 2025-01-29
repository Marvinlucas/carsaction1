<?php
session_start();
require_once 'include/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_car"])) {
    $carId = $_POST["car_id"];

    // Delete related records from the 'loans' table
    $deleteLoansQuery = "DELETE FROM loans WHERE sellerId = ?";
    $stmtLoans = mysqli_prepare($conn, $deleteLoansQuery);
    mysqli_stmt_bind_param($stmtLoans, "i", $carId);

    if (mysqli_stmt_execute($stmtLoans)) {
        // Now, delete the car from the 'carselled' table
        $deleteCarQuery = "DELETE FROM sellcars WHERE car_id = ?";
        $stmtCar = mysqli_prepare($conn, $deleteCarQuery);
        mysqli_stmt_bind_param($stmtCar, "i", $carId);

        if (mysqli_stmt_execute($stmtCar)) {
            header("Location: " . $_SERVER['HTTP_REFERER']); // Redirect back to the same page
            exit();
        } else {
            echo "Error deleting car: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmtCar);
    } else {
        echo "Error deleting car loans: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmtLoans);
    mysqli_close($conn);
}
?>
