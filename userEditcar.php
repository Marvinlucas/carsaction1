<?php
session_start();
require_once 'include/config.php'; // Include your database connection file
require_once 'include/head.php';

$carId = '';
$carModel = '';
$carYear = '';
$carPrice = '';
$downpayment = '';
$monthlyPayment = '';
$interest_rate = '';
$loan_term = '';
$location = '';
$carDescription = '';
$updateSuccess = false; // Initialize the updateSuccess variable

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    $username = $_SESSION["username"];
} else {
    header("Location: login.php");
    exit();
}

if (isset($_GET["car_id"])) {
    $carId = $_GET["car_id"];
    $query = "SELECT * FROM sellcars WHERE car_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $carId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $carModel = $row["model"];
        $carYear = $row["year"];
        $carPrice = $row["price"];
        $downpayment = $row["downpayment"];
        $monthlyPayment = $row["monthly_payment"];
        $interest_rate = $row["interest_rate"];
        $loan_term = $row["loan_term"];
        $location = $row["location"];
        $carDescription = $row["description"];
    } else {
        echo "Car not found.";
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    // Get data from the form
    $carModel = $_POST["model"];
    $carYear = $_POST["year"];
    $carPrice = $_POST["price"];
    $downpayment = $_POST["downpayment"];
    $monthlyPayment = $_POST["monthly_payment"];
    $interest_rate = $_POST["interest_rate"];
    $loan_term = $_POST["loan_term"];
    $location = $_POST["location"];
    $carDescription = $_POST["description"];

    // Update the data in the database
    $stmt = $conn->prepare("UPDATE sellcars SET 
        model = ?,
        year = ?,
        price = ?,
        downpayment = ?,
        monthly_payment = ?,
        interest_rate = ?,
        loan_term = ?,
        location = ?,
        description = ?
        WHERE car_id = ?");
    $stmt->bind_param("sdddiissii", $carModel, $carYear, $carPrice, $downpayment, $monthlyPayment, $interest_rate, $loan_term, $location, $carDescription, $carId);
    
    if ($stmt->execute()) {
        $updateSuccess = true;
    } else {
        echo "Error updating data: " . $stmt->error;
        $updateSuccess = false;
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Sell Car</title>
   

    <style>
    .image-input {
        margin-bottom: 10px;
    }
    </style>
</head>

<body>
    <?php include('include/top_bar.php'); ?>
    <div class="container">
        <h1>Sell Car</h1>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <div id="update-alert" class="alert alert-success" style="display: none;"></div>

                <div class="form-group">
                    <label for="model">Model:</label>
                    <input type="text" class="form-control" id="model" name="model" required  value="<?php echo htmlspecialchars($carModel); ?>">
                </div>

                <div class="form-group">
                    <label for="year">Year:</label>
                    <input type="number" class="form-control" id="year" name="year" required value="<?php echo htmlspecialchars($carYear); ?>">
                </div>

                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="number" class="form-control" id="price" name="price" onkeyup="formatPrice(this)" required value="<?php echo htmlspecialchars($carPrice); ?>">
                </div>


                <div class="form-group">
                    <label for="price">Downpayment:</label>
                    <input type="number" class="form-control" id="downpayment" name="downpayment" onkeyup="formatPrice(this)" value="<?php echo htmlspecialchars($downpayment); ?>">
                </div>


                <div class="form-group">
                    <label for="price">Monthly payment:</label>
                    <input type="number" class="form-control" id="monthly_payment" name="monthly_payment" onkeyup="formatPrice(this)" value="<?php echo htmlspecialchars($monthlyPayment); ?>">
                </div>
              <div class="form-group">
                <label for="interestRate">Interest Rate (%):</label>
                <input type="number" class="form-control" id="interest_rate" name="interest_rate" step="0.01" min="0.00" max="9999.99" value="<?php echo htmlspecialchars($interest_rate); ?>">
              </div>
               <div class="form-group">
                <label for="loanTerm">Loan Term (months):</label>
                <input type="number" class="form-control" id="loan_term" name="loan_term" value="<?php echo htmlspecialchars($loan_term); ?>">
            </div>

                <a href="loancalculator.php" id="openLoanCalculator">Open Payment Calculator if Needed!</a>

        

                <div class="form-group">
                    <label for="location">Location:</label>
                    <input type="text" class="form-control" id="location" name="location" required value="<?php echo htmlspecialchars($location); ?>">
                </div>

               <div class="form-group">
    <label for="description">Description:</label>
    <textarea class="form-control" id="description" name="description" required><?php echo htmlspecialchars($carDescription); ?></textarea>

           
                </div>
            </div>
        </div>

       
<div class="form-group" id="image-container">
    <!-- No initial image field here -->
</div>
<input type="hidden" name="car_id" value="<?php echo $carId; ?>">
        <input type="hidden" name="update" value="true">
    <input type="submit" class="btn btn-primary" value="Update">
    </form>
</div>


    <!-- Add Bootstrap JavaScript -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
 

   <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Intercept the form submission
            document.getElementById("update-form").addEventListener("submit", function (event) {
                event.preventDefault(); // Prevent the default form submission
                
                // Collect form data
                var formData = new FormData(this);

                // Send an AJAX request to handle the form submission
                fetch("<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>", {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    var updateAlert = document.getElementById("update-alert");

                    if (data.success) {
                        // Display a success message if the update was successful
                        updateAlert.innerText = "Data updated successfully!";
                        updateAlert.classList.add("alert-success");
                    } else {
                        // Display an error message if the update failed
                        updateAlert.innerText = "Error updating data. Please try again.";
                        updateAlert.classList.add("alert-danger");
                    }

                    // Show the alert message
                    updateAlert.style.display = "block";
                })
                .catch(error => {
                    console.error(error);
                });
            });
        });
    </script>

    <?php include('include/footer.php'); ?>
</body>

</html>