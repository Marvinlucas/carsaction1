<?php
session_start();
require_once 'include/config.php'; // Include your database connection logic
require_once 'include/head.php';

// Initialize the alert message
$alertMessage = "";

// Check if the user is logged in
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    $buyer_id = $_SESSION["user_id"]; // Assuming you have a "buyer_id" in the buyers table
    $username = $_SESSION["username"];
    $buyer_firstname = $_SESSION["firstname"];
    $buyer_lastname = $_SESSION["lastname"];
} else {
    // User is not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}

// Initialize sellerId with a default value or handle the case where it's not provided
$sellerId = NULL;

// Retrieve seller_id from the URL
if (isset($_GET['sellers_id'])) {
    $sellerId = $_GET['sellers_id'];
}

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] === "POST" && $sellerId !== null) {
    $downpayment = $_POST["downpayment"];
    $interestRate = $_POST["interest_rate"];
    $loanTerm = $_POST["loan_term"];
    $monthlyInstallment = $_POST["monthly_installment"];

    // Validate and sanitize the data (you can add more validation as needed)
    $downpayment = filter_var($downpayment, FILTER_SANITIZE_NUMBER_INT);
    $interestRate = filter_var($interestRate, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $loanTerm = filter_var($loanTerm, FILTER_SANITIZE_NUMBER_INT);
    $monthlyInstallment = filter_var($monthlyInstallment, FILTER_SANITIZE_NUMBER_INT); // Assuming it's an integer


$carDetails = $conn->query("SELECT make, model, price FROM sellcars WHERE sellers_id = $sellerId");

if (!$carDetails) {
    // Handle the query error
    $alertMessage = "Error fetching car details: " . $conn->error;
} else {
    if ($carDetails->num_rows > 0) {
        $carData = $carDetails->fetch_assoc();
        $carBrand = $carData['make'];
        $carModel = $carData['model'];

        // Use prepared statements to insert data into the database
        $sql = "INSERT INTO loans (username, firstname, lastname, downpayment, interest_rate, loan_term, monthly_installment, sellerId, buyerId, make, model, timestamp) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Bind parameters
        $stmt->bind_param("sssiidiiiss", $username, $buyer_firstname, $buyer_lastname, $downpayment, $interestRate, $loanTerm, $monthlyInstallment, $sellerId, $buyer_id, $carBrand, $carModel);

            // Execute the statement
            if ($stmt->execute()) {
                // Successful insertion
                $alertMessage = "Loan application submitted successfully. Wait for the seller to approve your loan request.";
            } else {
                // Error during insertion
                $alertMessage = "Error submitting loan application: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            // Error in preparing the statement
            $alertMessage = "Error preparing the statement: " . $conn->error;
        }
    } else {
        // Handle the case where no car details were found for the given sellerId
        $alertMessage = "Car details not found for this seller.";
    }
}
}
// Now, let's fetch data from the 'carselled' and 'loan' tables using a JOIN and WHERE clause
$sql = "SELECT c.make, c.model, c.price, l.* 
        FROM sellcars c
        INNER JOIN loans l ON c.sellers_id = l.sellerId
        WHERE c.sellers_id = $sellerId";

$result = $conn->query($sql);

// Initialize sellerId with a default value or handle the case where it's not provided
$carId = NULL;

// Retrieve seller_id from the URL
if (isset($_GET['car_id'])) {
    $carId = $_GET['car_id'];

    // Fetch downpayment, monthly_payment, interest_rate, and loan_term from the sellcars table for the specific seller
    $carDetails = $conn->query("SELECT downpayment, monthly_payment, interest_rate, loan_term FROM sellcars WHERE car_id = $carId");

    if (!$carDetails) {
        // Handle the query error
        $alertMessage = "Error fetching car details: " . $conn->error;
    } else {
        if ($carDetails->num_rows > 0) {
            $carData = $carDetails->fetch_assoc();
            $downpayment = $carData['downpayment'];
            $monthlyPayment = $carData['monthly_payment'];
            $interestRate = $carData['interest_rate'];
            $loanTerm = $carData['loan_term'];
        } else {
            // Handle the case where no car details were found for the given sellerId
            $alertMessage = "Car details not found for this seller.";
        }
    }
}

// You can loop through $result to display the data or perform other actions
?>

<!DOCTYPE html>
<html>

<head>
    <title>CarSaction - Apply for a Loan</title>

</head>

<body>
  <?php include('include/top_bar.php'); ?>

    <div class="container">
        <h1 class="mt-5">Apply for a Loan</h1>
        <?php
        if (!empty($alertMessage)) {
            echo '<div class="alert alert-success">' . $alertMessage . '</div>';
        }
        ?>
       <form id="loanApplicationForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?sellers_id=<?php echo $sellerId; ?>">
    <div class="form-group">
        <label for="downpayment">Downpayment (₱):</label>
        <input type="number" class="form-control" id="downpayment" name="downpayment" required value="<?php echo $downpayment; ?>">
    </div>
    <div class="form-group">
        <label for="interestRate">Interest Rate (%):</label>
        <input type="number" class="form-control" id="interest_rate" name="interest_rate" step="0.01" min="0.00" max="9999.99" required value="<?php echo $interestRate; ?>">
    </div>
    <div class="form-group">
        <label for="loanTerm">Loan Term (months):</label>
        <input type="number" class="form-control" id="loan_term" name="loan_term" required value="<?php echo $loanTerm; ?>">
    </div>
    <div class="form-group">
        <label for="monthlyInstallment">Monthly Installment (₱):</label>
        <input type="number" class="form-control" id="monthly_installment" name="monthly_installment" required value="<?php echo $monthlyPayment; ?>">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>

       
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script>
        // JavaScript for form submission and handling
        // ...
    </script>

    <?php include('include/footer.php'); ?>
</body>

</html>
