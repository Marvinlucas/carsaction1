<?php
session_start();
require_once 'include/config.php'; // Include your database connection logic
require_once 'include/head.php';

// Check if the user is logged in as a seller
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    $sellerId = $_SESSION["user_id"];
} else {
    // User is not logged in as a seller, redirect to the appropriate page
    header("Location: login_dashboard.php");
    exit();
}

// Initialize the alert message
$alertMessage = "";

$sql = "SELECT id, firstname, lastname, make, model, downpayment, interest_rate, loan_term, monthly_installment, status, timestamp 
        FROM loans WHERE sellerId = ? ORDER BY timestamp DESC";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $sellerId);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            // You have results, proceed to display them
        } else {
            $alertMessage = "No loan requests found."; // Display a message if there are no results
        }
    } else {
        $alertMessage = "Error executing SQL query: " . $conn->error; // Handle SQL execution error
    }
} else {
    $alertMessage = "Error preparing SQL statement: " . $conn->error; // Handle SQL statement preparation error
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Seller Dashboard</title>
    
</head>

<body>
    <?php include('include/top_bar.php'); ?>

    <div class="container">
        <h2>Loan Requests:</h2>
        <?php
        if (!empty($alertMessage)) {
            echo '<div class="alert alert-info">' . $alertMessage . '</div>';
        }
        ?>

        <div class="table-responsive"> <!-- Add the table-responsive class here -->
            <table class="table">
                <thead>
                    <tr>
                        <th>BuyerFullname</th>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Downpayment</th>
                        <th>Interest Rate</th>
                        <th>Loan Term</th>
                        <th>Monthly Installment</th>
                        <th>Date</th>
                        <th>Action</th>
                        <th></th>
                        <th>Status</th> <!-- Add this column for approval status -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["firstname"] . " " . $row["lastname"] . "</td>";
                        echo "<td>" . $row["make"] . "</td>";
                        echo "<td>" . $row["model"] . "</td>";
                        echo "<td>" . $row["downpayment"] . " ₱</td>";
                        echo "<td>" . $row["interest_rate"] . "%</td>";
                        echo "<td>" . $row["loan_term"] . " months</td>";
                        echo "<td>" . $row["monthly_installment"] . " ₱</td>";
                        echo "<td>" . $row["timestamp"] . "</td>";

                        // Add Approve and Decline buttons
                        echo '<td>
                                <a href="approved_request.php?id=' . $row["id"] . '&action=approve" onclick="return confirmApprove()"  class= "btn btn-success">Approve</a>
                            </td>';

                        echo '<td>
                                <a href="decline_request.php?id=' . $row["id"] . '&action=decline" onclick="return confirmDecline()" class= "btn btn-danger">Decline</a>
                            </td>';

                        echo '<td>';
                        if ($row["status"] == 'pending') {
                            echo '<span class="text-warning">Pending</span>';
                           
                        } elseif ($row["status"] == 'approved') {
                            echo '<span class="text-success">Approved</span>';
                        } elseif ($row["status"] == 'rejected') {
                            echo '<span class="text-danger">Declined</span>';
                        }
                        echo '</td>';
                    }
                    ?>
                </tbody>
            </table>
        </div> <!-- Close the table-responsive div -->

    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
   <script>
    // JavaScript function to display a confirmation dialog for Approve action
    function confirmApprove() {
        return confirm("Are you sure you want to approve this loan?");
    }

    // JavaScript function to display a confirmation dialog for Decline action
    function confirmDecline() {
        return confirm("Are you sure you want to decline this loan?");
    }
</script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

      <?php include('include/footer.php'); ?>
</body>

</html>
