<?php
session_start();
require_once 'include/config.php';
require_once 'include/head.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sold Cars</title>
   
</head>
<body>
<?php include('include/top_bar.php'); ?>
<div class="container">
    <br>
    <a href="userDashboard.php" class="btn btn-primary">Total Report</a>
    <h1 class="mt-4">Sold Cars</h1>
    <?php
    // Check if the user is logged in
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        $username = $_SESSION["username"];

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
        $user_id = $row["user_id"];

        // Fetch sold cars for the logged-in user
        $stmt = $conn->prepare("SELECT make, model, buyer_name, sold_price, date_sold FROM sellcars WHERE sellers_id = ? AND sold = 1");
        if (!$stmt) {
            die("Error preparing query: " . $conn->error);
        }

        $stmt->bind_param("i", $user_id);

        if (!$stmt->execute()) {
            die("Error executing query: " . $stmt->error);
        }

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo '<table class="table table-striped table-bordered">';
            echo '<thead><tr><th>Brand</th><th>Model</th><th>Buyer Name</th><th>Sold Price</th><th>Date Sold</th></tr></thead>';
            echo '<tbody>';
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row["make"] . '</td>';
                echo '<td>' . $row["model"] . '</td>';
                echo '<td>' . $row["buyer_name"] . '</td>';
                echo '<td>' . $row["sold_price"] . '</td>';
                // Format the date to "Month Year" format
                $date = new DateTime($row["date_sold"]);
                echo '<td>' . $date->format('F Y') . '</td>'; // 'F' represents the full month name, and 'Y' represents the year.
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
        } else {
            echo "No sold cars found for this user.";
        }
    } else {
        // User is not logged in, redirect to the login page
        header("Location: login.php");
        exit();
    }
    ?>
    <br>
</div>

<!-- Include Bootstrap JS from a CDN (optional) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
