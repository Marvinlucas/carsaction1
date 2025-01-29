<?php
session_start();
require_once 'include/config.php'; // Include your database connection logic
require_once 'include/head.php';

// Check if the user is logged in as a seller
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    $sellerId = $_SESSION["user_id"];

    // Fetch the seller's first name and last name from the sellers table
    $sql = "SELECT firstname, lastname FROM user WHERE user_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $sellerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows === 1) {
            $sellerData = $result->fetch_assoc();
            $sellerFirstName = $sellerData["firstname"];
            $sellerLastName = $sellerData["lastname"];
        }
    }
} else {
    // User is not logged in as a seller, redirect to the appropriate page
    header("Location: login_dashboard.php");
    exit();
}

// ...

// Check if the request has an "id" parameter
if (isset($_GET["id"])) {
    $request_id = $_GET["id"];

    // Fetch the buyer request with the specified ID
    $sql = "SELECT * FROM loans WHERE id = ? AND sellerId = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("is", $request_id, $sellerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();

            // Fetch additional loan details based on the request ID
            $sqlLoanDetails = "SELECT make, model, downpayment, interest_rate, monthly_installment, loan_term FROM loans WHERE id = ?";
            if ($stmtLoanDetails = $conn->prepare($sqlLoanDetails)) {
                $stmtLoanDetails->bind_param("i", $request_id);
                $stmtLoanDetails->execute();
                $resultLoanDetails = $stmtLoanDetails->get_result();
                $stmtLoanDetails->close();

                if ($resultLoanDetails->num_rows === 1) {
                    $loanDetails = $resultLoanDetails->fetch_assoc();
                    $make = $loanDetails["make"];
                    $model = $loanDetails["model"];
                    $downpayment = $loanDetails["downpayment"];
                    $interestRate = $loanDetails["interest_rate"];
                    $monthlyInstallment = $loanDetails["monthly_installment"];
                    $loanTerm = $loanDetails["loan_term"];
                }
            }

            // Perform approval logic here, update the database accordingly
            // Example: Update the status of the request to "approved"
            $update_sql = "UPDATE loans SET status = 'approved' WHERE id = ?";
            if ($stmt2 = $conn->prepare($update_sql)) {
                $stmt2->bind_param("i", $request_id);

                if ($stmt2->execute()) {
                    // Approval successful, you can perform any additional actions here
                    // For example, you may want to send a notification to the buyer

                    // Construct the notification message with the seller's name and loan details
                    $notificationMessage = "Your loan request has been approved by {$sellerFirstName} {$sellerLastName}.\n
                    Car Details:\nMake: {$make}\nModel: {$model}\n
                    Loan Details:\nDownpayment: ₱{$downpayment}\nInterest Rate: {$interestRate}%\nMonthly Installment: ₱{$monthlyInstallment}\nLoan Term: {$loanTerm} months";
                    
                    // Get the buyer's ID who requested the loan (assuming you have a 'buyerId' field in your loan table)
                    $buyerId = $row["buyerId"];

                    // Set the status to "unread" for the notification
                    $status = "unread";

                    // Insert the notification into the 'notifications' table, including the timestamp
                    $insertNotificationSql = "INSERT INTO notifications (buyer_id, message, status, created_at) VALUES (?, ?, ?, NOW())";
                    if ($stmt3 = $conn->prepare($insertNotificationSql)) {
                        $stmt3->bind_param("iss", $buyerId, $notificationMessage, $status);
                        $stmt3->execute();
                        $stmt3->close();
                    }

                    // Redirect back to the seller dashboard after approval
                    header("Location: loanApproval.php");
                    exit();
                } else {
                    // Error during approval
                    // You can handle this case as needed
                    echo "Error approving request: " . $stmt2->error;
                }

                $stmt2->close();
            }
        }
    }


// Move the HTML code inside this block
?>

<!DOCTYPE html>
<html>

<head>
    <title>Approve Request</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>

<body>
    <?php include('include/top_bar.php'); ?>

    <div class="container">
        <h1 class="mt-5">Approve Request</h1>
        <p>Request ID: <?php echo $row["id"]; ?></p>
        <!-- Display request details here -->
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>


        <?php
    } else {
        // If the "id" parameter is not valid or the request doesn't belong to the seller, redirect to the seller dashboard
        header("Location: seller_dashboard.php");
        exit();
    }

?>
</body>
</html>