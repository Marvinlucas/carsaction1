<?php
session_start();
require_once 'include/config.php';
require_once 'include/head.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sold Cars</title>
    <!-- Include Bootstrap CSS from a CDN -->
   
</head>
<body>
<?php include('include/top_bar.php'); ?>
<div class="container">
   
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

        $dataPerMonth = array(); // Initialize an array to store data per month

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $date = new DateTime($row["date_sold"]);
              $monthYear = $date->format('F Y'); // 'F' represents the full month name, and 'Y' represents the year.


                if (!isset($dataPerMonth[$monthYear])) {
                    $dataPerMonth[$monthYear] = array(
                        'cars' => 0,
                        'price' => 0
                    );
                }

                $dataPerMonth[$monthYear]['cars']++;
                $dataPerMonth[$monthYear]['price'] += $row["sold_price"];
            }
        }

        $months = array();
        $carsPerMonth = array();
        $pricePerMonth = array();

        foreach ($dataPerMonth as $month => $data) {
            array_push($months, $month);
            array_push($carsPerMonth, $data['cars']);
            array_push($pricePerMonth, $data['price']);
        }

       
    } else {
        // User is not logged in, redirect to the login page
        header("Location: login.php");
        exit();
    }
    ?>
    <br>
   
</div>

<!-- Add Chart.js library from a CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Create div elements for the pie charts -->
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h2>Total Sold Cars Per Month</h2>
            <canvas id="carsPerMonthChart"></canvas>
        </div>
        <div class="col-md-6">
            <h2>Total Sold Price Per Month</h2>
            <canvas id="pricePerMonthChart"></canvas>
        </div>
    </div>
</div>
<!-- JavaScript to create the pie charts -->
<script>
   // Chart for total sold cars per month
var carsPerMonthChart = new Chart(document.getElementById('carsPerMonthChart').getContext('2d'), {
    type: 'pie',
    data: {
        labels: <?php echo json_encode($months); ?>,
        datasets: [{
            label: 'Total Sold Cars Per Month',
            data: <?php echo json_encode($carsPerMonth); ?>,
            backgroundColor: [
                'rgba(255, 99, 132, 0.6)',
                'rgba(54, 162, 235, 0.6',
                'rgba(75, 192, 192, 0.6',
                'rgba(255, 206, 86, 0.6',
                'rgba(153, 102, 255, 0.6',
                'rgba(255, 159, 64, 0.6',
                'rgba(255, 99, 132, 0.6',
            ],
        }]
    }
});

// Chart for total sold price per month
var pricePerMonthChart = new Chart(document.getElementById('pricePerMonthChart').getContext('2d'), {
    type: 'pie',
    data: {
        labels: <?php echo json_encode($months); ?>,
        datasets: [{
            label: 'Total Sold Price Per Month',
            data: <?php echo json_encode($pricePerMonth); ?>,
            backgroundColor: [
                'rgba(255, 99, 132, 0.6)',
                'rgba(54, 162, 235, 0.6',
                'rgba(75, 192, 192, 0.6',
                'rgba(255, 206, 86, 0.6',
                'rgba(153, 102, 255, 0.6',
                'rgba(255, 159, 64, 0.6',
                'rgba(255, 99, 132, 0.6',
            ],
        }]
    }
});

</script>

<!-- Include Bootstrap JS from a CDN (optional) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></
